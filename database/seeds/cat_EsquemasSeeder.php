<?php

use Illuminate\Database\Seeder;

class cat_EsquemasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $archivo_csv = storage_path().'/app/seeds/ccvms_work_table_esquemas.csv';
        $query = sprintf("
            LOAD DATA local INFILE '%s' 
            INTO TABLE esquemas
            FIELDS TERMINATED BY ',' 
            OPTIONALLY ENCLOSED BY '\"' 
            ESCAPED BY '\"' 
            LINES TERMINATED BY '\\n'", addslashes($archivo_csv));
        DB::connection()->getpdo()->exec($query);
    }
}
