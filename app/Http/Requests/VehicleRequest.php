<?php

namespace App\Http\Requests;

use App\Http\Requests\FormRequest;
use Illuminate\Support\Facades\Auth;

class VehicleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::guard('api')->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'zipCode' => 'required',
            'city' => 'required',
            'uf' => 'required',
            'vehicle_type' => 'required',
            'vehicle_brand' => 'required',
            'vehicle_model' => 'required',
            'vehicle_version' => 'required',
            'vehicle_regdate' => 'required',
            'vehicle_fuel' => 'required',
            'vehicle_price' => 'required',
            'vehicle_photos' => 'exists:vehicle_photos,vehicle_id'
        ];
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $this->merge([
            'vehicle_photos' => $this->id
        ]);
    }
}
