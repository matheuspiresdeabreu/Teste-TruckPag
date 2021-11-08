<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEnderecosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('enderecos', function (Blueprint $table) {
            $table->increments("endereco_id");
            $table->string("logradouro");
            $table->integer("numero");
            $table->string("bairro");
            $table->integer("municipio_id")->unsigned();

        });

        Schema::table('enderecos', function($table)
        {
            $table->foreign('municipio_id')->references('municipio_id')->on('municipios');


        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('model_enderecos');
    }
}
