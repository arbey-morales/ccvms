<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTiposPartosTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('tipos_partos', function(Blueprint $table)
		{
			$table->increments('id')->comment('Claves y nombres de los tipos de parto existentes');
			$table->string('clave', 4)->nullable();
			$table->string('descripcion', 45)->nullable();
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
		Schema::drop('tipos_partos');
	}

}
