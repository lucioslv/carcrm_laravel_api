<?php

namespace App\Http\Controllers\api\uploads;

use App\Http\Controllers\Controller;
use App\Models\Vehicle;
use App\Models\Vehicle_photos;
use App\Repositories\Eloquent\Repository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic as Image;

class VehicleUploadController extends Controller
{
    protected $user;
    protected $vehicle;
    protected $vehicle_photos;

    public function __construct(Vehicle $vehicle, Vehicle_photos $vehicle_photos)
    {
        $this->user = Auth()->guard('api')->user();
        $this->vehicle = new Repository($vehicle);
        $this->vehicle_photos = new Repository($vehicle_photos);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $file = $request->file('file');
        $fileName = md5(uniqid(time())).strrchr($file->getClientOriginalName(), '.');
        $vehicle = $this->vehicle->getModel()->where('user_id', $this->user->id)
                        ->find($request->id);
        if(!$vehicle){
            return response()->json(['error' => 'Veículo não encontrado.']);
        }

        if($request->hasFile('file') && $file->isValid()){
            $photo = $this->vehicle_photos->create([
                'user_id' => $this->user->id,
                'vehicle_id' => $request->id,
                'img' => $fileName
            ]);

            if($photo->id){
                $img = Image::make($request->file)->orientate();
                $img->resize(1000, null, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });

                Storage::put('vehicles/'.$this->user->id.'/'.$photo->vehicle_id.'/'.$fileName, $img->encode(), 'public');

                return $photo;
            }
            return response()->json(['error' => 'Erro ao cadastrar imagem.']);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        foreach($request->order as $order => $id){
            $position = $this->vehicle_photos->getModel()->where('user_id', $this->user->id)->find($id);
            $position->order = $order;
            $position->save();
        }
        return response()->json(['success' => 'Posições atualizadas com sucesso.']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $photo = $this->vehicle_photos->getModel()->where('user_id', $this->user->id)->find(($id));
        if($photo->id){
            $path = 'vehicles/'.$this->user->id.'/'.$photo->vehicle_id.'/'.$photo->img;
            if(Storage::exists($path)){
                Storage::delete($path);
            }
            if($photo->delete()){
                return response()->json(['success' => 'Imagem removida com sucesso.']);
            }
            return response()->json(['error' => 'Erro ao remover imagem.']);
        }
        return response()->json(['error' => 'Imagem não encontrada.']);
    }
}
