<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateColoniasTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('colonias', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('codigo_postal');
			$table->string('nombre', 100)->nullable();
			$table->integer('oficina_postal')->nullable();
			$table->string('asentamientoCPC_id', 5)->nullable();
			$table->integer('entidades_id')->unsigned()->index('fk_colonias_entidades_idx');
			$table->integer('municipios_id')->unsigned()->index('fk_colonias_municipios_idx');
			$table->integer('tipos_asentamiento_id')->unsigned();
			$table->integer('tipos_zona_id')->unsigned();
			$table->integer('ciudades_id')->unsigned()->index('fk_colonias_ciudades_idx');
			$table->timestamps();
			$table->softDeletes();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('colonias');
	}

}
