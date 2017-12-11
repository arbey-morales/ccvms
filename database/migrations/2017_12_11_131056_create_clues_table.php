<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCluesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('clues', function(Blueprint $table)
		{
			$table->increments('id')->comment('identificador de la CLUES (autoincremental)');
			$table->integer('jurisdicciones_id')->unsigned()->index('relCluesJurisdicciones_idx')->comment('JurIsdiccion al que pertenece la CLUES');
			$table->integer('entidades_id')->unsigned()->default(7)->index('relCluesEntidad_idx')->comment('Entidad a la que pertenece la CLUES');
			$table->integer('municipios_id')->unsigned()->index('relCluesMunicipios_idx')->comment('Municipio al que pertenece la CLUES');
			$table->integer('localidades_id')->unsigned()->index('relCluesLocalidades_idx')->comment('Localidad al que pertenece la CLUES');
			$table->integer('instituciones_id')->unsigned()->index('relCluesInstitucion_idx')->comment('Institucion a la que pertenece la CLUES');
			$table->integer('regiones_id')->nullable();
			$table->integer('estratos_id')->nullable();
			$table->integer('tipologias_id')->unsigned()->index('relCluesTipologias_idx');
			$table->integer('tipos_unidades_id')->unsigned()->index('relCluesTiposUnidad_idx');
			$table->integer('estatus_id')->unsigned()->index('relCluesEstatus_idx');
			$table->string('servidor', 4);
			$table->string('clues', 12)->comment('CLave Unica de Establecimientos de Salud');
			$table->string('nombre', 120)->comment('Nombre de la unidad de salud');
			$table->string('domicilio', 200)->comment('Direccion de la unidad de salud, calle, numero, colonia, ciudad o municipio.');
			$table->integer('codigo_postal');
			$table->float('numero_longitud', 10, 0)->nullable();
			$table->float('numero_latitud', 10, 0)->nullable();
			$table->integer('consultorios')->nullable();
			$table->integer('camas')->nullable();
			$table->date('fecha_construccion')->nullable();
			$table->date('fecha_inicio_operacion')->nullable();
			$table->string('telefono1', 20)->nullable();
			$table->string('telefono2', 20)->nullable();
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
		Schema::drop('clues');
	}

}
