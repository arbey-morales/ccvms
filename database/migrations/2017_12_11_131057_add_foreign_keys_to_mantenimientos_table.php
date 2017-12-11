<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToMantenimientosTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('mantenimientos', function(Blueprint $table)
		{
			$table->foreign('contenedores_id', 'fk_mantenimientos_contenedores')->references('id')->on('contenedores')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('tipos_mantenimientos_id', 'fk_mantenimientos_tipos_mantenimientos')->references('id')->on('tipos_mantenimientos')->onUpdate('NO ACTION')->onDelete('NO ACTION');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('mantenimientos', function(Blueprint $table)
		{
			$table->dropForeign('fk_mantenimientos_contenedores');
			$table->dropForeign('fk_mantenimientos_tipos_mantenimientos');
		});
	}

}
