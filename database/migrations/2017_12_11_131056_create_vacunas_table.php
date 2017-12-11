<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateVacunasTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('vacunas', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('vias_administracion_id')->unsigned()->index('fk_vacunas_vias_administracion1_idx')->comment('Vías de administración del biológico');
			$table->string('insumos_clave', 25)->nullable()->index('fk_vacunas_insumos_idx');
			$table->string('clave', 45)->index('fk_vacunas_insumos_idx1');
			$table->string('nombre');
			$table->integer('orden_esquema')->default(0);
			$table->string('color_rgb', 10)->nullable();
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
		Schema::drop('vacunas');
	}

}
