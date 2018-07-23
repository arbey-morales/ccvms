<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateReportesContenedoresImagenesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('reportes_contenedores_imagenes', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('reportes_contenedores_id')->unsigned()->nullable();
			$table->text('imagen', 65535);
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
		Schema::drop('reportes_contenedores_imagenes');
	}

}
