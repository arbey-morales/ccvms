<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToAgebsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('agebs', function(Blueprint $table)
		{
			$table->foreign('localidades_id', 'fk_agebs_localidades')->references('id')->on('localidades')->onUpdate('CASCADE')->onDelete('CASCADE');
			$table->foreign('municipios_id', 'fk_agebs_municipios')->references('id')->on('municipios')->onUpdate('CASCADE')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('agebs', function(Blueprint $table)
		{
			$table->dropForeign('fk_agebs_localidades');
			$table->dropForeign('fk_agebs_municipios');
		});
	}

}
