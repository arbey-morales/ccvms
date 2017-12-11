<?php

use Illuminate\Database\Seeder;

class cat_EntidadesFederativasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $archivo_csv = '/app/seeds/ccvms_work_table_entidades_federativas.csv';
        $query = sprintf("
            LOAD DATA local INFILE '%s' 
            INTO TABLE entidades_federativas
            FIELDS TERMINATED BY ',' 
            OPTIONALLY ENCLOSED BY '\"' 
            ESCAPED BY '\"' 
            LINES TERMINATED BY '\\n'", addslashes($archivo_csv));
        DB::connection()->getpdo()->exec($query);
    }
}
