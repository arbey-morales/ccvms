<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToMunicipiosTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('municipios', function(Blueprint $table)
		{
			$table->foreign('entidades_id', 'fk_municipios_entidades_federativas_1')->references('id')->on('entidades_federativas')->onUpdate('RESTRICT')->onDelete('RESTRICT');
			$table->foreign('jurisdicciones_id', 'fk_municipios_jurisdicciones_1')->references('id')->on('jurisdicciones')->onUpdate('RESTRICT')->onDelete('RESTRICT');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('municipios', function(Blueprint $table)
		{
			$table->dropForeign('fk_municipios_entidades_federativas_1');
			$table->dropForeign('fk_municipios_jurisdicciones_1');
		});
	}

}
