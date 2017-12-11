<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePersonasTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('personas', function(Blueprint $table)
		{
			$table->string('id', 32)->primary();
			$table->string('servidor_id', 4);
			$table->integer('incremento');
			$table->integer('clues_id')->unsigned()->index('fk_personas_clues1_idx');
			$table->integer('paises_id')->unsigned()->index('fk_personas_paises1_idx');
			$table->integer('entidades_federativas_nacimiento_id')->unsigned()->index('fk_personas_entidades_nacimientox1_idx');
			$table->integer('entidades_federativas_domicilio_id')->unsigned()->index('fk_personas_entidades_domiciliox1_idx');
			$table->integer('municipios_id')->unsigned()->index('fk_personas_municipiosx1_idx');
			$table->integer('localidades_id')->unsigned()->index('fk_personas_localidadesx1_idx');
			$table->integer('colonias_id')->unsigned()->nullable()->index('fk_personas_colonias_idx');
			$table->string('agebs_id', 20)->nullable()->index('fk_personas_agebs1_idx');
			$table->integer('instituciones_id')->unsigned()->nullable()->index('fk_personas_instituciones1_idx');
			$table->integer('codigos_censos_id')->unsigned()->nullable()->index('fk_personas_codigos1_idx');
			$table->integer('tipos_partos_id')->unsigned()->index('fk_personas_tipos_parto1_idx');
			$table->string('folio_certificado_nacimiento', 45)->nullable();
			$table->string('nombre', 50);
			$table->string('apellido_paterno', 50);
			$table->string('apellido_materno', 50);
			$table->string('curp', 18)->nullable();
			$table->string('genero', 1)->default('M');
			$table->date('fecha_nacimiento')->nullable();
			$table->string('descripcion_domicilio')->nullable();
			$table->string('calle', 45)->nullable();
			$table->string('numero', 45)->nullable();
			$table->integer('codigo_postal')->nullable();
			$table->string('sector', 10)->nullable();
			$table->string('manzana', 10)->nullable();
			$table->integer('telefono_casa')->nullable();
			$table->integer('telefono_celular')->nullable();
			$table->string('tutor');
			$table->date('fecha_nacimiento_tutor')->nullable();
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
		Schema::drop('personas');
	}

}
