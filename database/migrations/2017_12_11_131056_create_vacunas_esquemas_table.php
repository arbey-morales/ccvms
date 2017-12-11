<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateVacunasEsquemasTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('vacunas_esquemas', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('vacunas_id')->unsigned()->index('fk_vacunas_esquemas_vacunas1_idx');
			$table->integer('esquemas_id')->unsigned()->index('fk_vacunas_esquemas_esquemas1_idx');
			$table->integer('tipo_aplicacion')->comment('1- Aplicación única 2- Primera dosis 3 -Segunda dosis 4 -Tercera dosis 5 - Cuarta dosis 6 - Refuerzo');
			$table->integer('orden_esquema')->nullable()->default(1);
			$table->integer('fila');
			$table->integer('columna');
			$table->integer('intervalo_inicio')->nullable()->comment('Limite inferior en días');
			$table->integer('intervalo_inicio_anio')->default(0);
			$table->integer('intervalo_inicio_mes')->default(0);
			$table->integer('intervalo_inicio_dia')->default(0);
			$table->integer('intervalo_fin')->nullable()->comment('Limite superior en días');
			$table->integer('intervalo_fin_anio')->default(0);
			$table->integer('intervalo_fin_mes')->default(0);
			$table->integer('intervalo_fin_dia')->default(0);
			$table->integer('edad_ideal')->nullable()->comment('Edad óptima para recibir la vacuna, Si rebasa deben seguirse otras reglas');
			$table->integer('edad_ideal_anio')->default(0);
			$table->integer('edad_ideal_mes')->default(0);
			$table->integer('edad_ideal_dia')->default(0);
			$table->integer('margen_anticipacion')->default(0)->comment('En días,esto se resta al intervalo_inicial lo cual amplia el intervalo, pudiendo aplicarse una vacuna antes de lo previsto idealmente');
			$table->integer('dias_entre_siguiente_dosis')->nullable()->comment('Días a agregar si rebasa el maximo_ideal');
			$table->integer('entre_siguiente_dosis_anio')->default(0);
			$table->integer('entre_siguiente_dosis_mes')->default(0);
			$table->integer('entre_siguiente_dosis_dia')->default(0);
			$table->integer('etiqueta_ideal')->nullable();
			$table->integer('etiqueta_ideal_anio')->default(0);
			$table->integer('etiqueta_ideal_mes')->default(0);
			$table->integer('etiqueta_ideal_dia')->default(0);
			$table->integer('etiqueta_no_ideal')->nullable();
			$table->integer('etiqueta_no_ideal_anio')->default(0);
			$table->integer('etiqueta_no_ideal_mes')->default(0);
			$table->integer('etiqueta_no_ideal_dia')->default(0);
			$table->float('dosis_requerida', 10, 0)->nullable();
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
		Schema::drop('vacunas_esquemas');
	}

}
