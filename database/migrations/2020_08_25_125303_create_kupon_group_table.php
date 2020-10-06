<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateKuponGroupTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kupon_group', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name','255');
            $table->text('description');
            $table->text('foto_path');
            $table->date('expired');
            $table->integer('total');
            $table->double('amount_per_kupon');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('kupon_group');
    }
}
