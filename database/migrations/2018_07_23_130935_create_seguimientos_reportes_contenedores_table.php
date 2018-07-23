<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSeguimientosReportesContenedoresTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('seguimientos_reportes_contenedores', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('reportes_contenedores_id')->unsigned();
			$table->string('estatus_seguimiento', 45)->comment('1 - En proceso, Procesando/En acción - Inicia acciones de atención al reporte
2 - En espera - Se necesita que algo suceda para proceder
3 - Descartado - No cumple con las caracteristicas de un reporte o es irrelevante
4 - Finalizado con éxito
5 - Finalizado sin éxito
');
			$table->text('observaciones', 65535)->nullable();
			$table->text('imagen', 65535)->nullable();
			$table->string('usuario_id')->comment('1 - Generado
2 - Atendido / En proceso
3 - Terminado ');
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
		Schema::drop('seguimientos_reportes_contenedores');
	}

}
