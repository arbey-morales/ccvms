<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToCuadroDistribucionJurisdiccionalesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('cuadro_distribucion_jurisdiccionales', function(Blueprint $table)
		{
			$table->foreign('pedidos_estatales_id', 'fk_cuadro_distribucion_jurisdiccionales_pedidos_estatales_1')->references('id')->on('pedidos_estatales')->onUpdate('NO ACTION')->onDelete('NO ACTION');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('cuadro_distribucion_jurisdiccionales', function(Blueprint $table)
		{
			$table->dropForeign('fk_cuadro_distribucion_jurisdiccionales_pedidos_estatales_1');
		});
	}

}
