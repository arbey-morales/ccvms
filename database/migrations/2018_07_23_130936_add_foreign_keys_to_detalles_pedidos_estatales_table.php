<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToDetallesPedidosEstatalesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('detalles_pedidos_estatales', function(Blueprint $table)
		{
			$table->foreign('insumos_clave', 'fk_detalles_pedidos_estatales_insumos')->references('clave')->on('insumos')->onUpdate('CASCADE')->onDelete('CASCADE');
			$table->foreign('pedidos_estatales_id', 'fk_pedidos_estatales_detalles_pedidos_estatales_1')->references('id')->on('pedidos_estatales')->onUpdate('CASCADE')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('detalles_pedidos_estatales', function(Blueprint $table)
		{
			$table->dropForeign('fk_detalles_pedidos_estatales_insumos');
			$table->dropForeign('fk_pedidos_estatales_detalles_pedidos_estatales_1');
		});
	}

}
