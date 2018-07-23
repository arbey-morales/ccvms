<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTiposContenedoresTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('tipos_contenedores', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('clave', 45);
			$table->string('nombre', 45);
			$table->string('imagen', 100)->nullable()->default('default');
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
		Schema::drop('tipos_contenedores');
	}

}
