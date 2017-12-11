<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateJurisdiccionesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('jurisdicciones', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('entidades_id')->unsigned()->default(7)->index('fk_jurisdicciones_entidades_idx');
			$table->integer('clues_id')->unsigned()->index('fk_jurisdicciones_clues_idx');
			$table->string('clave', 2)->index('idx_clave_jurisdicciones');
			$table->string('nombre', 50);
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
		Schema::drop('jurisdicciones');
	}

}
