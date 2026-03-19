<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\PurchaseRequest;
use App\Http\Requests\AddressRequest;
use Illuminate\Http\Request;
use Stripe\StripeClient;

class PurchaseController extends Controller
{
    public function show(Item $item)
    {
        $item->load(['image']);

        $payments = [
            'convenience' => 'コンビニ支払い',
            'card' => 'カード支払い',
        ];

        $user = Auth::user(); // 配送先表示用

        $shipping = session("purchase.shipping.{$item->id}", [
            'postal_code'   => $user->postal_code,
            'address_line1' => $user->address_line1,
            'address_line2' => $user->address_line2,
        ]);

        return view('purchase.show', compact('item', 'user', 'payments', 'shipping'));
    }

    public function store(PurchaseRequest $request, Item $item)
    {
        $validated = $request->validated();
        $user = Auth::user();

        $shipping = session("purchase.shipping.{$item->id}", []);

        $postal = $shipping['postal_code'] ?? $user->postal_code;
        $line1  = $shipping['address_line1'] ?? $user->address_line1;
        $line2  = $shipping['address_line2'] ?? $user->address_line2 ?? '';

        if (empty($postal) || empty($line1)) {
            return back()->withErrors([
                'shipping' => '配送先が未設定です。住所変更から配送先を入力してください。',
            ])->withInput();
        }

        if ($item->is_sold) {
            return back()->with('error', 'この商品は売り切れです');
        }

        $paymentMethodType = $validated['payment_method'] === 'convenience'
            ? 'konbini'
            : 'card';

        $stripe = new StripeClient(config('services.stripe.secret'));

        $session = $stripe->checkout->sessions->create([
            'mode' => 'payment',
            'payment_method_types' => [$paymentMethodType],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'jpy',
                    'unit_amount' => (int) $item->price,
                    'product_data' => [
                        'name' => $item->title,
                    ],
                ],
                'quantity' => 1,
            ]],
            'customer_email' => $user->email,
            'success_url' => route('purchase.success', ['item' => $item->id]) . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('purchase.cancel', ['item' => $item->id]),
            'metadata' => [
                'buyer_id' => $user->id,
                'item_id' => $item->id,
                'payment_method' => $validated['payment_method'],
                'shipping_postal_code' => $postal,
                'shipping_address_line1' => $line1,
                'shipping_address_line2' => $line2,
            ],
        ]);

        return redirect()->away($session->url);
    }

    public function editAddress(Item $item)
    {
        $user = auth()->user();

        $shipping = session("purchase.shipping.{$item->id}", [
            'postal_code' => $user->postal_code,
            'address_line1' => $user->address_line1,
            'address_line2' => $user->address_line2,
        ]);

        return view('purchase.address', compact('item', 'user', 'shipping'));
    }

    public function updateAddress(AddressRequest $request, Item $item)
    {
        $validated = $request->validated();

        session([
            "purchase.shipping.{$item->id}" => [
                'postal_code' => $validated['postal_code'],
                'address_line1' => $validated['address_line1'],
                'address_line2' => $validated['address_line2'] ?? '',
            ]
        ]);

        return redirect()
            ->route('purchase.show', $item);
    }

    public function success(Request $request, Item $item)
    {
        $sessionId = $request->query('session_id');

        if (empty($sessionId)) {
            return redirect()->route('items.index')->with('error', '決済情報を確認できませんでした');
        }

        $stripe = new StripeClient(config('services.stripe.secret'));

        try {
            $checkoutSession = $stripe->checkout->sessions->retrieve($sessionId);
        } catch (\Exception $e) {
            return redirect()->route('items.index')->with('error', '決済情報の取得に失敗しました');
        }

        $paymentMethod = $checkoutSession->metadata->payment_method ?? null;

        if ($paymentMethod === 'convenience') {
            session()->forget('purchase.shipping.' . $item->id);

            return redirect()->route('items.index')->with(
                'success',
                'コンビニ支払いを受け付けました。入金確認後に購入確定となります。'
            );
        }

        if (($checkoutSession->payment_status ?? null) !== 'paid') {
            return redirect()->route('items.index')->with('error', '決済が完了していません');
        }

        try {
            DB::transaction(function () use ($item, $checkoutSession) {
                $lockedItem = Item::where('id', $item->id)
                    ->lockForUpdate()
                    ->firstOrFail();

                if ($lockedItem->is_sold) {
                    throw new \RuntimeException('この商品は売り切れです');
                }

                Order::create([
                    'buyer_id' => $checkoutSession->metadata->buyer_id,
                    'item_id' => $lockedItem->id,
                    'payment_method' => $checkoutSession->metadata->payment_method,
                    'shipping_postal_code' => $checkoutSession->metadata->shipping_postal_code,
                    'shipping_address_line1' => $checkoutSession->metadata->shipping_address_line1,
                    'shipping_address_line2' => $checkoutSession->metadata->shipping_address_line2,
                ]);

                $lockedItem->update([
                    'is_sold' => true,
                ]);
            });
        } catch (\RuntimeException $e) {
            return redirect()->route('items.index')->with('error', $e->getMessage());
        }

        session()->forget('purchase.shipping.' . $item->id);

        return redirect()->route('items.index')->with('success', '購入が完了しました');
    }

    public function cancel(Item $item)
    {
        return redirect()->route('purchase.show', $item)->with('error', '決済をキャンセルしました');
    }
}
