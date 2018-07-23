<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToLocalidadesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('localidades', function(Blueprint $table)
		{
			$table->foreign('municipios_id', 'fk_localidades_municipios_1')->references('id')->on('municipios')->onUpdate('NO ACTION')->onDelete('NO ACTION');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('localidades', function(Blueprint $table)
		{
			$table->dropForeign('fk_localidades_municipios_1');
		});
	}

}
