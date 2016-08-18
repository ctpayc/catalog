<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Liteweb\Catalog\Models\Catalog;
use Liteweb\Catalog\Models\DefaultParam;

class DatabaseSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $this->call('DefaultParamSeeder');

        $this->command->info('Таблица дефолтных параметров заполнена!');

        $this->call('CatalogSeeder');

        $this->command->info('Таблица каталогов заполнена!');

    }

}

/**
* заполнение таблицы default_params
*/

class DefaultParamSeeder extends Seeder {

  public function run()
  {
    DB::table('default_params')->delete();

    $params = Storage::get('default_params/default_params.json');
    $default_params = json_decode($params, JSON_UNESCAPED_SLASHES);

    foreach ($default_params as $key => $param) {
        DefaultParam::create([
            'type'  => $param['param-type'],
            'name'    => $param['name'],
            'params'  => $param,
        ]);
    }
    // DB::select(DB::raw("SELECT setval(pg_get_serial_sequence('default_params', 'id'), max(id)) FROM default_params"));
  }
}

/**
* заполнение таблицы catalogs
*/

class CatalogSeeder extends Seeder {

  public function run()
  {
    DB::table('catalogs')->delete();

    $flat = Storage::get('default_params/fields_kvartira_num.json');
    $cottage = Storage::get('default_params/fields_cottage.json');
    $taunhouse = Storage::get('default_params/fields_taunhouse.json');
    $garage = Storage::get('default_params/fields_garage.json');
    $dacha = Storage::get('default_params/fields_dacha.json');
    $earth = Storage::get('default_params/fields_earth.json');
    $catalog_array = [
        ['id'     => 1, 'parent' => 0, 'name'   => 'Недвижимость', 'fields' => '{}'],
        ['id'     => 2, 'parent' => 1, 'name'   => 'Жилая', 'fields' => '{}'],
        ['id'     => 3, 'parent' => 1, 'name'   => 'Не жилая', 'fields' => '{}'],
        ['id'     => 4, 'parent' => 2, 'name'   => 'Квартира', 'fields' => json_decode($flat, JSON_UNESCAPED_SLASHES)],
        ['id'     => 5, 'parent' => 2, 'name'   => 'Коттедж', 'fields' => json_decode($cottage, JSON_UNESCAPED_SLASHES)],
        ['id'     => 6, 'parent' => 2, 'name'   => 'Танхаус', 'fields' => json_decode($taunhouse, JSON_UNESCAPED_SLASHES)],
        ['id'     => 7, 'parent' => 3, 'name'   => 'Гараж', 'fields' => json_decode($garage, JSON_UNESCAPED_SLASHES)],
        ['id'     => 8, 'parent' => 3, 'name'   => 'Дача', 'fields' => json_decode($dacha, JSON_UNESCAPED_SLASHES)],
        ['id'     => 9, 'parent' => 3, 'name'   => 'Земля', 'fields' => json_decode($earth, JSON_UNESCAPED_SLASHES)],
        ['id'     => 10, 'parent' => 3, 'name'   => 'Офис', 'fields' => '{}']
    ];

//    $this->command->info($cottage);
    foreach ($catalog_array as $catalog) {
        Catalog::create([
            'id'      => $catalog['id'],
            'parent'  => $catalog['parent'],
            'name'    => $catalog['name'],
            'fields'  => $catalog['fields'],
        ]);
    }
    // DB::select(DB::raw("SELECT setval(pg_get_serial_sequence('catalogs', 'id'), max(id)) FROM catalogs"));
  }
}

