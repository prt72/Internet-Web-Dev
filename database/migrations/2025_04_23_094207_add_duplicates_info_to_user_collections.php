<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDuplicatesInfoToUserCollections extends Migration
{
    public function up()
    {
        Schema::table('user_collections', function (Blueprint $table) {
            $table->json('duplicates_info')->nullable()->after('quantity');
        });
    }

    public function down()
    {
        Schema::table('user_collections', function (Blueprint $table) {
            $table->dropColumn('duplicates_info');
        });
    }
}