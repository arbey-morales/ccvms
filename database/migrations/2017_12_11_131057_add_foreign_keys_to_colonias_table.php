<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToColoniasTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('colonias', function(Blueprint $table)
		{
			$table->foreign('entidades_id', 'fk_colonias_entidades')->references('id')->on('entidades_federativas')->onUpdate('CASCADE')->onDelete('CASCADE');
			$table->foreign('municipios_id', 'fk_colonias_municipios')->references('id')->on('municipios')->onUpdate('CASCADE')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('colonias', function(Blueprint $table)
		{
			$table->dropForeign('fk_colonias_entidades');
			$table->dropForeign('fk_colonias_municipios');
		});
	}

}
