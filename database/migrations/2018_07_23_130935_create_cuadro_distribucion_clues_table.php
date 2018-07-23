<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCuadroDistribucionCluesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('cuadro_distribucion_clues', function(Blueprint $table)
		{
			$table->increments('id');
			$table->timestamp('fecha')->default(DB::raw('CURRENT_TIMESTAMP'));
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
		Schema::drop('cuadro_distribucion_clues');
	}

}
