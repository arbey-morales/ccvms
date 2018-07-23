<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePoblacionObjetivoConapoTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('poblacion_objetivo_conapo', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('anio');
			$table->integer('municipios_id')->unsigned()->index('fk_poblacion_objetivo_RENAPO_1_idx');
			$table->integer('hombres_0')->default(0);
			$table->integer('mujeres_0')->default(0);
			$table->integer('hombres_1')->default(0);
			$table->integer('mujeres_1')->default(0);
			$table->integer('hombres_2')->default(0);
			$table->integer('mujeres_2')->default(0);
			$table->integer('hombres_3')->default(0);
			$table->integer('mujeres_3')->default(0);
			$table->integer('hombres_4')->default(0);
			$table->integer('mujeres_4')->default(0);
			$table->integer('hombres_5')->default(0);
			$table->integer('mujeres_5')->default(0);
			$table->integer('hombres_6')->default(0);
			$table->integer('mujeres_6')->default(0);
			$table->integer('hombres_7')->default(0);
			$table->integer('mujeres_7')->default(0);
			$table->integer('hombres_8')->default(0);
			$table->integer('mujeres_8')->default(0);
			$table->integer('hombres_9')->default(0);
			$table->integer('mujeres_9')->default(0);
			$table->integer('hombres_10')->default(0);
			$table->integer('mujeres_10')->default(0);
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
		Schema::drop('poblacion_objetivo_conapo');
	}

}
