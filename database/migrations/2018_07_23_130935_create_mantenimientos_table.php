<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateMantenimientosTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('mantenimientos', function(Blueprint $table)
		{
			$table->string('id', 32)->primary();
			$table->string('folio', 45)->nullable();
			$table->string('incremento', 11);
			$table->string('servidor', 4);
			$table->string('contenedores_id', 32)->index('fk_mantenimientos_contenedores_idx');
			$table->integer('tipos_mantenimientos_id')->unsigned()->index('fk_mantenimientos_tipos_mantenimientos_idx');
			$table->string('fecha_atencion', 45)->nullable();
			$table->string('fecha_solicitud', 45)->nullable();
			$table->string('recibe_conformidad', 45)->nullable();
			$table->string('status_final', 45)->nullable();
			$table->string('status_inicial', 45)->nullable();
			$table->string('descripcion_falla', 45)->nullable();
			$table->string('observacion_mantenimiento', 45)->nullable();
			$table->string('usuario_id', 45)->nullable();
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
		Schema::drop('mantenimientos');
	}

}
