<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTemperaturasContenedoresTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('temperaturas_contenedores', function(Blueprint $table)
		{
			$table->string('id', 32)->primary()->comment('		');
			$table->string('servidor_id', 4);
			$table->integer('incremento');
			$table->string('contenedores_id', 32)->index('fk_temperaturas_contenedores_contenedores_idx');
			$table->date('fecha');
			$table->time('hora');
			$table->float('temperatura', 10, 0);
			$table->text('observacion', 65535)->nullable();
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
		Schema::drop('temperaturas_contenedores');
	}

}
