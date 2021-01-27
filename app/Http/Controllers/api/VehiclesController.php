<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Vehicle;
use App\Models\Vehicle_car_steering;
use App\Models\Vehicle_carcolor;
use App\Models\Vehicle_cubiccms;
use App\Models\Vehicle_doors;
use App\Models\Vehicle_exchange;
use App\Models\Vehicle_features;
use App\Models\Vehicle_financial;
use App\Models\Vehicle_fuel;
use App\Models\Vehicle_gearbox;
use App\Models\Vehicle_motorpower;
use App\Models\Vehicle_regdate;
use App\Models\Vehicle_type;
use App\Repositories\Eloquent\Repository;
use Illuminate\Http\Request;

class VehiclesController extends Controller
{
    protected $user;
    protected $vehicle;
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

    public function __construct(Vehicle $vehicle, Vehicle_regdate $vehicle_regdate, Vehicle_gearbox $vehicle_gearbox, Vehicle_fuel $vehicle_fuel, Vehicle_car_steering $vehicle_car_steering, Vehicle_motorpower $vehicle_motorpower, Vehicle_doors $vehicle_doors, Vehicle_carcolor $vehicle_carcolor, Vehicle_exchange $vehicle_exchange, Vehicle_financial $vehicle_financial, Vehicle_features $vehicle_features, Vehicle_cubiccms $vehicle_cubiccms, Vehicle_type $vehicle_type)
    {
        $this->user = Auth()->guard('api')->user();
        $this->vehicle = new Repository($vehicle);
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

    private function getData()
    {
        return [
            'vehicle_types' => $this->vehicle_type->all(),
            'regdate' => $this->vehicle_regdate->getModel()->orderBy('label', 'ASC')->get(),
            'gearbox' => $this->vehicle_gearbox->all(),
            'fuel' => $this->vehicle_fuel->all(),
            'car_steering' => $this->vehicle_car_steering->all(),
            'motorpower' => $this->vehicle_motorpower->all(),
            'doors' => $this->vehicle_doors->all(),
            'carcolor' => $this->vehicle_carcolor->all(),
            'exchange' => $this->vehicle_exchange->all(),
            'financial' => $this->vehicle_financial->all(),
            'features' => $this->vehicle_features->all(),
            'cubiccms' => $this->vehicle_cubiccms->all()
        ];
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        $vehicle = $this->vehicle->with('vehicle_photos')
                        ->firstOrCreate([
                            'user_id' => $this->user->id,
                            'status' => 0
                        ]);

        return array_merge(['vehicle' => $vehicle, $this->getData()]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
