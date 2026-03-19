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
        return redirect()->route('items.index');
    }

    public function cancel(Item $item)
    {
        return redirect()->route('purchase.show', $item)->with('error', '決済をキャンセルしました');
    }
}
