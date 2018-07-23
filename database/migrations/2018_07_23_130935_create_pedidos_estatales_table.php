<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePedidosEstatalesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('pedidos_estatales', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('proveedores_id')->unsigned()->index('fk_pedidos_estatales_proveedores_idx');
			$table->timestamp('fecha')->default(DB::raw('CURRENT_TIMESTAMP'));
			$table->string('descripcion', 45);
			$table->string('observacion')->nullable();
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
		Schema::drop('pedidos_estatales');
	}

}
