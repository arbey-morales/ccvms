<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePersonasVacunasEsquemasTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('personas_vacunas_esquemas', function(Blueprint $table)
		{
			$table->string('id', 32);
			$table->string('servidor_id', 4);
			$table->integer('incremento');
			$table->string('personas_id', 32)->index('fk_personas_vacunas_esquemas_personas1_idx');
			$table->integer('vacunas_esquemas_id')->unsigned()->index('fk_personas_vacunas_esquemas_1_idx');
			$table->dateTime('fecha_programada')->nullable();
			$table->dateTime('fecha_aplicacion')->nullable();
			$table->dateTime('fecha_caducidad')->nullable();
			$table->string('lote', 45)->nullable();
			$table->float('dosis', 10, 0)->nullable();
			$table->string('usuario_id');
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
		Schema::drop('personas_vacunas_esquemas');
	}

}
