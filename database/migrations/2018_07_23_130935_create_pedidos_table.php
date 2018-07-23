<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePedidosTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('pedidos', function(Blueprint $table)
		{
			$table->string('id');
			$table->integer('incremento');
			$table->string('servidor_id', 4);
			$table->integer('clues_id')->unsigned();
			$table->integer('anio')->unsigned();
			$table->string('tipo_pedido_id', 4);
			$table->string('descripcion');
			$table->string('folio', 45)->nullable();
			$table->date('fecha');
			$table->dateTime('fecha_concluido')->nullable();
			$table->dateTime('fecha_cancelacion')->nullable();
			$table->string('status', 45)->comment('BR BORRADOR
 ET EN TRANSITO
PS POR SURTIR
FI FINALIZADO
');
			$table->text('observaciones', 65535)->nullable();
			$table->integer('total_cantidad_solicitada')->nullable();
			$table->integer('total_cantidad_recibida')->nullable();
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
		Schema::drop('pedidos');
	}

}
