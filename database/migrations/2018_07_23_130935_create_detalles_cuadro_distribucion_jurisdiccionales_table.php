<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateDetallesCuadroDistribucionJurisdiccionalesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('detalles_cuadro_distribucion_jurisdiccionales', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('cuadro_distribucion_jurisdiccionales_id')->unsigned()->index('fk_detalles_cuadro_distribucion_jurisdiccionales_pedidos_es_idx');
			$table->string('insumos_id', 25);
			$table->integer('cantidad')->nullable();
			$table->string('lote', 45)->nullable();
			$table->dateTime('fecha_caducidad')->nullable();
			$table->string('pt', 20)->nullable();
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
		Schema::drop('detalles_cuadro_distribucion_jurisdiccionales');
	}

}
