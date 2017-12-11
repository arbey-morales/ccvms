<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateMunicipiosTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('municipios', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('clave', 10)->index('idx_clave_municipios');
			$table->string('nombre', 30);
			$table->integer('entidades_id')->unsigned()->default(7)->index('fk_municipios_entidades_federativas_1');
			$table->integer('jurisdicciones_id')->unsigned()->index('fk_municipios_jurisdicciones_1');
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
		Schema::drop('municipios');
	}

}
