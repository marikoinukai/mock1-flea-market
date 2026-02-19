<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddProfileAndAddressToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('icon_path')->nullable()->after('email');

            $table->string('postal_code', 8)->nullable()->after('password');
            $table->string('address_line1')->nullable()->after('postal_code');
            $table->string('address_line2')->nullable()->after('address_line1');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['icon_path', 'postal_code', 'address_line1', 'address_line2']);
        });
    }
}
