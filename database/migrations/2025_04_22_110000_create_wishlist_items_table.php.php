<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWishlistItemsTable extends Migration
{
    public function up()
    {
        Schema::create('wishlist_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('figurine_id');
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('figurine_id')->references('id')->on('figurines')->onDelete('cascade');

            // Prevent duplicate wishlist entries for the same user and figurine
            $table->unique(['user_id', 'figurine_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('wishlist_items');
    }
}
