<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToContenedoresTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('contenedores', function(Blueprint $table)
		{
			$table->foreign('clues_id', 'fk_contenedores_clues')->references('id')->on('clues')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('estatus_contenedor_id', 'fk_contenedores_estatus')->references('id')->on('estatus_contenedor')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('modelos_id', 'fk_contenedores_modelos')->references('id')->on('modelos')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('tipos_contenedores_id', 'fk_contenedores_tipos_contenedores')->references('id')->on('tipos_contenedores')->onUpdate('CASCADE')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('contenedores', function(Blueprint $table)
		{
			$table->dropForeign('fk_contenedores_clues');
			$table->dropForeign('fk_contenedores_estatus');
			$table->dropForeign('fk_contenedores_modelos');
			$table->dropForeign('fk_contenedores_tipos_contenedores');
		});
	}

}
