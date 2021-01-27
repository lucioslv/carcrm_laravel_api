<?php

use App\Http\Controllers\api\VehiclesController;
use Illuminate\Support\Facades\Route;

Route::apiResources([
    'vehicles' => VehiclesController::class
]);
