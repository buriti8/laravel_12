<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PList;

class ListsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $list = new PList();

        collect(config('lists.default'))->each(function ($options, $list_name) use ($list) {
            collect($options)->each(function ($option_name, $option_key) use ($list, $list_name) {
                $exist = PList::where('list', $list_name)->where('option_key', $option_key)->first();
                if (!$exist) {
                    $list->newQuery()->create([
                        'list' => $list_name,
                        'option_key' => $option_key,
                        'option' => $option_name,
                        'status' => 1,
                    ]);
                }
            });
        });
    }
}
