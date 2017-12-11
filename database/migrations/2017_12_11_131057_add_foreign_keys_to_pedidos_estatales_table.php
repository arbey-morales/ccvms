<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToPedidosEstatalesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('pedidos_estatales', function(Blueprint $table)
		{
			$table->foreign('proveedores_id', 'fk_pedidos_estatales_proveedores')->references('id')->on('proveedores')->onUpdate('CASCADE')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('pedidos_estatales', function(Blueprint $table)
		{
			$table->dropForeign('fk_pedidos_estatales_proveedores');
		});
	}

}
