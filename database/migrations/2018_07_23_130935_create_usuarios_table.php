<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUsuariosTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('usuarios', function(Blueprint $table)
		{
			$table->string('id')->primary();
			$table->string('servidor_id', 4)->index('usuarios_servidor_id_foreign');
			$table->string('password', 60);
			$table->string('nombre');
			$table->string('apellidos');
			$table->string('avatar');
			$table->boolean('su')->default(0);
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
		Schema::drop('usuarios');
	}

}
