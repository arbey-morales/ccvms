<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateEstatusContenedorTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('estatus_contenedor', function(Blueprint $table)
		{
			$table->increments('id');
			$table->text('descripcion', 65535);
			$table->string('color', 30)->nullable();
			$table->string('icono', 30)->nullable();
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
		Schema::drop('estatus_contenedor');
	}

}
