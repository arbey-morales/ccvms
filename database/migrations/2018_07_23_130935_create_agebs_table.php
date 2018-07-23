<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAgebsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('agebs', function(Blueprint $table)
		{
			$table->string('id', 20)->unique('id_UNIQUE');
			$table->integer('municipios_id')->unsigned()->index('fk_agebs_1_idx');
			$table->integer('localidades_id')->unsigned()->index('fk_agebs_2_idx');
			$table->string('usuario_id');
			$table->timestamps();
			$table->softDeletes();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('agebs');
	}

}
