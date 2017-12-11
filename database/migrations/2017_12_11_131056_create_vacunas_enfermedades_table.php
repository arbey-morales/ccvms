<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateVacunasEnfermedadesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('vacunas_enfermedades', function(Blueprint $table)
		{
			$table->integer('vacunas_id')->unsigned()->index('fk_vacunas_enfermedades_vacunas1_idx');
			$table->integer('enfermedades_id')->unsigned()->index('fk_vacunas_enfermedades_enfermedades1_idx');
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
		Schema::drop('vacunas_enfermedades');
	}

}
