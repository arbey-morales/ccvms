<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCuadroDistribucionJurisdiccionalesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('cuadro_distribucion_jurisdiccionales', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('pedidos_estatales_id')->unsigned()->index('fk_cuadro_distribucion_jurisdiccionales_pedidos_estatales_1_idx');
			$table->string('folio', 10);
			$table->dateTime('fecha')->nullable();
			$table->string('descripcion');
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
		Schema::drop('cuadro_distribucion_jurisdiccionales');
	}

}
