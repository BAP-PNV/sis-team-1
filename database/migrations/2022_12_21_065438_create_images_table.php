<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('images', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('owner_id');
            $table->foreign('owner_id')
                ->references('id')
                ->on('users')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->string('url', 100);
            $table->string('table',100);
            $table->float('size',10,2);
            $table->timestamps();
        });
    }

    /**
     * Enables us to hook into model event's
     *
     * @return void
     */
//    public static function boot()
//    {
//        parent::boot();
//
//        static::created(function ($product) {
//            $product->sku .= 'sku-' . $product->id;
//            $product->save();
//        });
//    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('images');
    }
};
