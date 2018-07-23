<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSincronizacionesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('sincronizaciones', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('servidor_id', 4)->index('sincronizaciones_servidor_id_foreign');
			$table->timestamp('fecha_generacion')->default(DB::raw('CURRENT_TIMESTAMP'));
			$table->timestamps();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('sincronizaciones');
	}

}
