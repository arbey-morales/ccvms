<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToInsumosTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('insumos', function(Blueprint $table)
		{
			$table->foreign('unidades_medidas_id', 'fk_insumos_unidades_medidas_1')->references('id')->on('unidades_medidas')->onUpdate('CASCADE')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('insumos', function(Blueprint $table)
		{
			$table->dropForeign('fk_insumos_unidades_medidas_1');
		});
	}

}
