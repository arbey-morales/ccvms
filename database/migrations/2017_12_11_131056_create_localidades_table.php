<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateLocalidadesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('localidades', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('clave', 10);
			$table->string('nombre', 70);
			$table->float('numero_latitud', 10, 0);
			$table->float('numero_longitud', 10, 0);
			$table->integer('numero_altitud')->nullable();
			$table->string('clave_carta', 6)->nullable();
			$table->integer('entidades_id')->unsigned();
			$table->integer('municipios_id')->unsigned()->index('relLocalidadesMunicipios_idx');
			$table->string('municipios_clave', 3)->nullable();
			$table->string('usuario_id');
			$table->timestamps();
			$table->softDeletes();
			$table->index(['municipios_clave','clave'], 'idx_clave_localidades');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('localidades');
	}

}
