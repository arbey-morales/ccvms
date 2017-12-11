<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateMovimientosTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('movimientos', function(Blueprint $table)
		{
			$table->integer('id')->primary();
			$table->string('tipo_movimiento', 4)->comment('ENT -> Entrada
SAL -> Salida');
			$table->string('fecha', 45)->nullable();
			$table->string('cancelado', 45)->nullable();
			$table->string('observacion', 45)->nullable();
			$table->string('usuario_id', 45)->nullable();
			$table->string('created_at', 45)->nullable();
			$table->string('updated_at', 45)->nullable();
			$table->string('deleted_at', 45)->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('movimientos');
	}

}
