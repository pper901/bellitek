<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('type'); // tool, part, device
            $table->string('category');
            $table->string('brand')->nullable();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->text('specification')->nullable();
            $table->text('content')->nullable();
            $table->integer('stock')->default(1);
            $table->decimal('price', 12, 2);

            $table->enum('condition', ['new', 'fairly used']);
            $table->enum('status', ['available', 'in_cart', 'sold'])->default('available');

            $table->unsignedBigInteger('user_id')->nullable(); // person who added to cart
            $table->softDeletes();
            $table->timestamps();
        });

    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
