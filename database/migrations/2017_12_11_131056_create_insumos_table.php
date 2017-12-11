<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateInsumosTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('insumos', function(Blueprint $table)
		{
			$table->string('clave', 25)->primary();
			$table->integer('unidades_medidas_id')->unsigned()->index('fk_insumos_unidades_medidas_1_idx')->comment('Catálogo de unidade de medida...');
			$table->string('tipo_insumo', 45)->default('BIO')->comment('BIO  -> Biológico
REF  -> Refacciones
MOB -> Mobiliario y equipo de red frío');
			$table->string('nombre')->nullable();
			$table->string('observacion')->nullable();
			$table->boolean('es_caduco')->nullable()->default(0)->comment('0 -> No caduca
1 -> Sí caduca');
			$table->boolean('requiere_mantenimiento')->nullable()->default(0)->comment('0 -> Sin mantenimiento1 -> Requiere mantenimiento');
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
		Schema::drop('insumos');
	}

}
