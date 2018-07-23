<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToPiramidePoblacionalTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('piramide_poblacional', function(Blueprint $table)
		{
			$table->foreign('clues_id', 'fk_piramide_poblacional_1')->references('id')->on('clues')->onUpdate('NO ACTION')->onDelete('NO ACTION');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('piramide_poblacional', function(Blueprint $table)
		{
			$table->dropForeign('fk_piramide_poblacional_1');
		});
	}

}
