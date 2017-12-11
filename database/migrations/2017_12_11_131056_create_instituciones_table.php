<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateInstitucionesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('instituciones', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('clave', 6)->index('idx_clave_instituciones');
			$table->string('nombreCorto', 30)->nullable()->index('idx_nombreCorto_instituciones');
			$table->string('nombre', 80);
			$table->string('usuario_id')->nullable();
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
		Schema::drop('instituciones');
	}

}
