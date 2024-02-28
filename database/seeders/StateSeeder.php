<?php

namespace Database\Seeders;

use App\Interfaces\CityRepositoryInterface;
use App\Interfaces\StateRepositoryInterface;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\App;

class StateSeeder extends Seeder
{
    private StateRepositoryInterface $stateRepository;
    private CityRepositoryInterface $cityRepository;

    public function __construct()
    {
        $this->stateRepository = App::make('App\Interfaces\StateRepositoryInterface');
        $this->cityRepository = App::make('App\Interfaces\CityRepositoryInterface');
    }

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statesSource = base_path('brazil_states_cities.json');
        $statesSource = file_get_contents($statesSource);
        $statesSource = json_decode($statesSource, true);

        foreach ($statesSource['states'] as $stateSource){
            $checkExisting = $this->stateRepository->getAll(
                params: [
                    [
                        'column' => 'name',
                        'operator' => '=',
                        'value' => $stateSource['name']
                    ]
                ]
            );

            if(sizeof($checkExisting) == 0){
                $state = $this->stateRepository->create(data: [
                    'name' => $stateSource['name'],
                    'short_name' => $stateSource['short_name']
                ]);

                foreach ($stateSource['cities'] as $citySource){
                    $this->cityRepository->create([
                        'state_id' => $state->id,
                        'name' => $citySource['name'],
                        'short_name' => $citySource['short_name']
                    ]);
                }
            }
        }

    }
}
