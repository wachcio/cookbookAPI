<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Recipes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('recipes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description', 255)->nullable($value = false);
            $table->text('ingredients')->nullable($value = false);
            $table->text('execution')->nullable($value = false);
            $table->string('picture', 255)->nullable($value = false);
            $table->tinyInteger('rating')->nullable($value = false)->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('recipes');
    }
}