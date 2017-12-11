<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUsersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('users', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('idJurisdiccion');
			$table->string('direccion', 120)->nullable();
			$table->string('nombre');
			$table->string('paterno', 45)->nullable();
			$table->string('materno', 45)->nullable();
			$table->string('email')->unique();
			$table->string('password', 60);
			$table->string('remember_token', 100)->nullable();
			$table->string('foto', 150)->nullable();
			$table->boolean('activo')->default(1);
			$table->boolean('borrado')->nullable()->default(0);
			$table->boolean('asRoot')->default(0);
			$table->timestamp('creadoAl')->default(DB::raw('CURRENT_TIMESTAMP'));
			$table->integer('creadoUsuario')->nullable();
			$table->timestamp('modificadoAl')->default(DB::raw('CURRENT_TIMESTAMP'));
			$table->integer('modificadoUsuario')->nullable();
			$table->dateTime('borradoAl')->nullable();
			$table->integer('borradoUsuario')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('users');
	}

}
