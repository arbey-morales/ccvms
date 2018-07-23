<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePaisesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('paises', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('descripcion', 150)->nullable();
			$table->string('claveISOA2', 2)->nullable();
			$table->string('claveA3', 3)->nullable();
			$table->string('claveN3', 5)->nullable();
			$table->string('prefijoTelefono', 5)->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('paises');
	}

}
