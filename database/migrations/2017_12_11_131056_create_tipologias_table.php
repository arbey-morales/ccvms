<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTipologiasTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('tipologias', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('clave', 6)->index('idx_clave_tipologias');
			$table->string('tipo', 15)->nullable()->index('idx_tipo_tipologias');
			$table->string('descripcion', 45)->nullable();
			$table->string('nombre', 70);
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
		Schema::drop('tipologias');
	}

}
