<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToDetallesCuadroDistribucionJurisdiccionalesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('detalles_cuadro_distribucion_jurisdiccionales', function(Blueprint $table)
		{
			$table->foreign('cuadro_distribucion_jurisdiccionales_id', 'fk_detalles_cuadro_distribucion_jurisdicciones_c_d_j')->references('id')->on('cuadro_distribucion_jurisdiccionales')->onUpdate('CASCADE')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('detalles_cuadro_distribucion_jurisdiccionales', function(Blueprint $table)
		{
			$table->dropForeign('fk_detalles_cuadro_distribucion_jurisdicciones_c_d_j');
		});
	}

}
