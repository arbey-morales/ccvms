<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateNivelatencionTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('nivelatencion', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('clave', 2);
			$table->string('nombre', 30);
			$table->string('creadoPor', 10)->nullable();
			$table->string('actualizadoPor', 10)->nullable();
			$table->dateTime('creadoAl')->nullable();
			$table->dateTime('modificadoAl')->nullable();
			$table->dateTime('borradoAl')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('nivelatencion');
	}

}
