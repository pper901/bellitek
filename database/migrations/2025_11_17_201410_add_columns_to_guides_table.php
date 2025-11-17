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
        Schema::table('guides', function (Blueprint $table) {
            $table->string('device')->after('id');
            $table->string('category')->after('device');
            $table->string('brand')->after('category');
            $table->string('series')->nullable()->after('brand');
            $table->string('model')->after('series');
            $table->string('issue')->after('model');
        });
    }

    public function down()
    {
        Schema::table('guides', function (Blueprint $table) {
            $table->dropColumn(['device', 'category', 'brand', 'series', 'model', 'issue']);
        });
    }

};
