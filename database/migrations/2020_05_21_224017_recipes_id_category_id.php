<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RecipesIdCategoryId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('recipes_id_category_id', function (Blueprint $table) {
            $table->id();
            $table->mediumInteger('recipes_id')->nullable($value = false);
            $table->mediumInteger('category_id')->nullable($value = false);

            $table->foreign('recipes_id')->references('ID')->on('recipes');
            $table->foreign('category_id')->references('ID')->on('categories');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('recipes_id_category_id');
    }
}