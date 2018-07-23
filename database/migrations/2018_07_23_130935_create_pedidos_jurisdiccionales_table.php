<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePedidosJurisdiccionalesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('pedidos_jurisdiccionales', function(Blueprint $table)
		{
			$table->increments('id');
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
		Schema::drop('pedidos_jurisdiccionales');
	}

}
