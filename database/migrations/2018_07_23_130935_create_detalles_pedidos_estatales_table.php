<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateDetallesPedidosEstatalesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('detalles_pedidos_estatales', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('pedidos_estatales_id')->unsigned()->index('fk_pedidos_estatales_insumos_pedidos_estatales_1_idx');
			$table->string('insumos_clave', 25)->index('fk_detalles_pedidos_estatales_insumos_idx');
			$table->integer('cantidad');
			$table->dateTime('fecha')->nullable();
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
		Schema::drop('detalles_pedidos_estatales');
	}

}
