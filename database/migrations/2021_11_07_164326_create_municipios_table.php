<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMunicipiosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('municipios', function (Blueprint $table) {
            $table->increments("municipio_id");
            $table->string("nome");
            $table->integer("estado_id")->unsigned();

        });
        Schema::table('municipios', function($table)
        {
            $table->foreign('estado_id')->references('estado_id')->on('estados');


        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('model_municipios');
    }
}
