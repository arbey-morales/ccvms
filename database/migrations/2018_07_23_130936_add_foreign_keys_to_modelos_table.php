<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToModelosTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('modelos', function(Blueprint $table)
		{
			$table->foreign('marcas_id', 'fk_modelos_marcas_id')->references('id')->on('marcas')->onUpdate('NO ACTION')->onDelete('NO ACTION');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('modelos', function(Blueprint $table)
		{
			$table->dropForeign('fk_modelos_marcas_id');
		});
	}

}
