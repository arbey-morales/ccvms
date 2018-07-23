<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToDetallesCuadroDistribucionCluesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('detalles_cuadro_distribucion_clues', function(Blueprint $table)
		{
			$table->foreign('cuadro_distribucion_clues_id', 'fk_detalles_cuadro_distribucion_clues_cuadros_distribucion_clues')->references('id')->on('cuadro_distribucion_clues')->onUpdate('CASCADE')->onDelete('CASCADE');
			$table->foreign('insumos_clave', 'fk_detalles_cuadro_distribucion_clues_insumos')->references('clave')->on('insumos')->onUpdate('CASCADE')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('detalles_cuadro_distribucion_clues', function(Blueprint $table)
		{
			$table->dropForeign('fk_detalles_cuadro_distribucion_clues_cuadros_distribucion_clues');
			$table->dropForeign('fk_detalles_cuadro_distribucion_clues_insumos');
		});
	}

}
