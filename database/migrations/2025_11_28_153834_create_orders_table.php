<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->decimal('shipping_amount', 10, 2)->default(0);
            $table->decimal('items_total', 10, 2)->default(0);
            $table->decimal('grand_total', 10, 2)->default(0);

            $table->string('customer_name');
            $table->string('customer_phone');
            $table->string('customer_email');

            $table->string('address_line');
            $table->string('city');
            $table->string('state');
            $table->string('country')->default('Nigeria');

            $table->string('payment_status')->default('pending_payment');
            $table->string('order_status')->default('processing');

            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('orders');
    }
};
