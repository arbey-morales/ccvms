<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateDetallesCuadroDistribucionCluesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('detalles_cuadro_distribucion_clues', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('cuadro_distribucion_clues_id')->unsigned()->index('fk_detalles_cuadro_distribucion_clues_cuadros_distribucion__idx');
			$table->string('insumos_clave', 25)->index('fk_detalles_cuadro_distribucion_clues_insumos_idx');
			$table->integer('cantidad');
			$table->dateTime('fecha')->nullable();
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
		Schema::drop('detalles_cuadro_distribucion_clues');
	}

}
