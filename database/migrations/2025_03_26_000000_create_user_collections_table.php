<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserCollectionsTable extends Migration
{
    public function up()
    {
        Schema::create('user_collections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('figurine_id')->constrained('figurines')->onDelete('cascade');
        
            // user-specific data
            $table->string('name')->nullable();  // Added name column
            $table->enum('status', ['wishlist', 'owned'])->default('wishlist');
            $table->integer('quantity')->default(1);
            $table->date('purchase_date')->nullable(); // only for owned
            $table->string('condition')->nullable();   // only for owned
            $table->text('comment')->nullable();
            $table->string('purchase_source')->nullable();
            $table->boolean('is_tradable')->default(false);

            // NEW FIELDS for dashboard charts
            $table->string('edition')->nullable();
            $table->string('rarity')->nullable();
            $table->string('series')->nullable();
            
            // Add the 'category' field
            $table->string('category')->nullable();  // Add category column

            $table->timestamps();
        
            // prevent duplicate wishlist/collection for same user + figurine
            $table->unique(['user_id', 'figurine_id', 'status']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_collections');
    }
}
