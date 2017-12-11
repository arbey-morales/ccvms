<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToCluesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('clues', function(Blueprint $table)
		{
			$table->foreign('entidades_id', 'fk_clues_entidades_federativas')->references('id')->on('entidades_federativas')->onUpdate('CASCADE')->onDelete('CASCADE');
			$table->foreign('estatus_id', 'fk_clues_estatus')->references('id')->on('estatus')->onUpdate('CASCADE')->onDelete('CASCADE');
			$table->foreign('instituciones_id', 'fk_clues_instituciones')->references('id')->on('instituciones')->onUpdate('CASCADE')->onDelete('CASCADE');
			$table->foreign('jurisdicciones_id', 'fk_clues_jurisdicciones')->references('id')->on('jurisdicciones')->onUpdate('CASCADE')->onDelete('CASCADE');
			$table->foreign('localidades_id', 'fk_clues_localidades')->references('id')->on('localidades')->onUpdate('CASCADE')->onDelete('CASCADE');
			$table->foreign('municipios_id', 'fk_clues_municipios')->references('id')->on('municipios')->onUpdate('CASCADE')->onDelete('CASCADE');
			$table->foreign('tipologias_id', 'fk_clues_tipologias')->references('id')->on('tipologias')->onUpdate('CASCADE')->onDelete('CASCADE');
			$table->foreign('tipos_unidades_id', 'fk_clues_tipos_unidades')->references('id')->on('tipos_unidad')->onUpdate('CASCADE')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('clues', function(Blueprint $table)
		{
			$table->dropForeign('fk_clues_entidades_federativas');
			$table->dropForeign('fk_clues_estatus');
			$table->dropForeign('fk_clues_instituciones');
			$table->dropForeign('fk_clues_jurisdicciones');
			$table->dropForeign('fk_clues_localidades');
			$table->dropForeign('fk_clues_municipios');
			$table->dropForeign('fk_clues_tipologias');
			$table->dropForeign('fk_clues_tipos_unidades');
		});
	}

}
