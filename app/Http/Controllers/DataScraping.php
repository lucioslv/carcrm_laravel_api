<?php

namespace App\Http\Controllers;

use App\Models\Vehicle_brand;
use App\Models\Vehicle_car_steering;
use App\Models\Vehicle_carcolor;
use App\Models\Vehicle_cubiccms;
use App\Models\Vehicle_doors;
use App\Models\Vehicle_exchange;
use App\Models\Vehicle_features;
use App\Models\Vehicle_financial;
use App\Models\Vehicle_fuel;
use App\Models\Vehicle_gearbox;
use App\Models\Vehicle_model;
use App\Models\Vehicle_motorpower;
use App\Models\Vehicle_regdate;
use App\Models\Vehicle_type;
use App\Models\Vehicle_version;
use App\Repositories\Eloquent\Repository;

class DataScraping extends Controller
{
    protected $vehicle_brand;
    protected $vehicle_model;
    protected $vehicle_version;
    protected $vehicle_regdate;
    protected $vehicle_gearbox;
    protected $vehicle_fuel;
    protected $vehicle_car_steering;
    protected $vehicle_motorpower;
    protected $vehicle_doors;
    protected $vehicle_carcolor;
    protected $vehicle_exchange;
    protected $vehicle_financial;
    protected $vehicle_features;
    protected $vehicle_cubiccms;
    protected $vehicle_type;

    public function __construct(Vehicle_brand $vehicle_brand, Vehicle_model $vehicle_model, Vehicle_version $vehicle_version, Vehicle_regdate $vehicle_regdate, Vehicle_gearbox $vehicle_gearbox, Vehicle_fuel $vehicle_fuel, Vehicle_car_steering $vehicle_car_steering, Vehicle_motorpower $vehicle_motorpower, Vehicle_doors $vehicle_doors, Vehicle_carcolor $vehicle_carcolor, Vehicle_exchange $vehicle_exchange, Vehicle_financial $vehicle_financial, Vehicle_features $vehicle_features, Vehicle_cubiccms $vehicle_cubiccms, Vehicle_type $vehicle_type)
    {
        $this->vehicle_brand = new Repository($vehicle_brand);
        $this->vehicle_model = new Repository($vehicle_model);
        $this->vehicle_version = new Repository($vehicle_version);
        $this->vehicle_regdate = new Repository($vehicle_regdate);
        $this->vehicle_gearbox = new Repository($vehicle_gearbox);
        $this->vehicle_fuel = new Repository($vehicle_fuel);
        $this->vehicle_car_steering = new Repository($vehicle_car_steering);
        $this->vehicle_motorpower = new Repository($vehicle_motorpower);
        $this->vehicle_doors = new Repository($vehicle_doors);
        $this->vehicle_carcolor = new Repository($vehicle_carcolor);
        $this->vehicle_exchange = new Repository($vehicle_exchange);
        $this->vehicle_financial = new Repository($vehicle_financial);
        $this->vehicle_features = new Repository($vehicle_features);
        $this->vehicle_cubiccms = new Repository($vehicle_cubiccms);
        $this->vehicle_type = new Repository($vehicle_type);
    }

    public function index($vehicle_type_id){
        $this->marcas($vehicle_type_id);
        $this->carro();
        $this->moto();
        $this->types();
    }

    public function marcas($vehicle_type_id){
        if($vehicle_type_id == 2020){
            $data = json_decode(file_get_contents(public_path('2020.json')));
            $vehicle_brand = $data[1];
        }
        if($vehicle_type_id == 2060){
            $data = json_decode(file_get_contents(public_path('2060.json')));
            $vehicle_brand = $data[0];
        }

        foreach($vehicle_brand->values_list as $brand){
            $marca = $this->vehicle_brand->getModel()->firstOrCreate([
                'label' => $brand->label,
                'value' => $brand->value,
                'vehicle_type_id' => $vehicle_type_id
            ]);

            foreach($brand->values as $model){
                $this->vehicle_model->getModel()->firstOrCreate([
                    'brand_id' => $marca->value,
                    'label' => $model->label,
                    'value' => $model->value,
                    'vehicle_type_id' => $vehicle_type_id
                ]);

                foreach($model-> values as $version){
                    $this->vehicle_version->getModel()->firstOrCreate([
                        'brand_id' => $marca->value,
                        'model_id' => $model->value,
                        'label' => $version->label,
                        'value' => $version->value
                    ]);
                }
            }
        }
    }

    public function carro() {
        $data = json_decode(file_get_contents(public_path('2020.json')));

        $array = [
            [
                'data' => $data[2],
                'class' => $this->vehicle_regdate->getModel()
            ],
            [
                'data' => $data[3],
                'class' => $this->vehicle_gearbox->getModel()
            ],
            [
                'data' => $data[4],
                'class' => $this->vehicle_fuel->getModel()
            ],
            [
                'data' => $data[5],
                'class' => $this->vehicle_car_steering->getModel()
            ],
            [
                'data' => $data[6],
                'class' => $this->vehicle_motorpower->getModel()
            ],
            [
                'data' => $data[9],
                'class' => $this->vehicle_doors->getModel()
            ],
            [
                'data' => $data[12],
                'class' => $this->vehicle_carcolor->getModel()
            ],
            [
                'data' => $data[14],
                'class' => $this->vehicle_exchange->getModel()
            ],
            [
                'data' => $data[15],
                'class' => $this->vehicle_financial->getModel()
            ]
        ];

        foreach($array as $item){
            $item = (object) $item;

            foreach($item->data->values_list as $value){
                $valid = $item->class->where('value', $value->value)->first();
                if(empty($valid)){
                    $item->class->create((array) $value);
                }
            }
        }

        foreach($data[11]->values_list as $features_car){
            $valid = $this->vehicle_features->getModel()->where('value', $features_car->value)
                                                        ->where('vehicle_type_id', 2020)
                                                        ->first();
            $features_car->vehicle_type_id = 2020;
            if(empty($valid)){
                $this->vehicle_features->create((array) $features_car);
            }
        }
    }

    public function moto(){
        $data = json_decode(file_get_contents(public_path('2060.json')));

        foreach($data[3]->values_list as $value){
            $valid = $this->vehicle_cubiccms->getModel()->where('value', $value->value)->first();

            if(empty($valid)){
                $this->vehicle_cubiccms->create((array) $value);
            }
        }

        foreach($data[5]->values_list as $moto_features){
            $valid = $this->vehicle_features->getModel()->where('value', $moto_features->value)
                                                        ->where('vehicle_type_id', 2060)
                                                        ->first();
            $moto_features->vehicle_type_id = 2060;
            if(empty($valid)){
                $this->vehicle_features->create((array) $moto_features);
            }
        }
    }

    public function types(){
        $data = [
            [
                'label' => 'Carros, vans e utilitários',
                'value' => 2020
            ],
            [
                'label' => 'Motos',
                'value' => 2060
            ]
        ];

        foreach($data as $item){
            $valid = $this->vehicle_type->getModel()->where('value', $item['value'])->first();
            if(empty($valid)){
                $this->vehicle_type->create($item);
            }
        }
    }
}
