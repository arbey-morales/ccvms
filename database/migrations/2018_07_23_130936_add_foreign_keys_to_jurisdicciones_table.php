<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToJurisdiccionesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('jurisdicciones', function(Blueprint $table)
		{
			$table->foreign('entidades_id', 'fk_jurisdicciones_entidades')->references('id')->on('entidades_federativas')->onUpdate('CASCADE')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('jurisdicciones', function(Blueprint $table)
		{
			$table->dropForeign('fk_jurisdicciones_entidades');
		});
	}

}
