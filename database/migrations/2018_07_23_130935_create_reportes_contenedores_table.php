<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateReportesContenedoresTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('reportes_contenedores', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('contenedores_id', 32);
			$table->string('folio', 15);
			$table->integer('fallas_contenedores_id')->unsigned();
			$table->integer('estatus_reporte')->comment('1 - Generado/Iniciado/Laevantado
2 - En proceso/Atendiendo
3 - Terminado');
			$table->string('reporto')->comment('1 - Generado
2 - Atendido / En proceso
3 - Terminado ');
			$table->date('fecha')->nullable();
			$table->time('hora')->nullable();
			$table->text('observacion', 65535);
			$table->text('foto', 65535)->nullable();
			$table->text('foto2', 65535)->nullable();
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
		Schema::drop('reportes_contenedores');
	}

}
