<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\OwnerRequest;
use App\Models\Owners;
use App\Repositories\Eloquent\Repository;
use Illuminate\Http\Request;

class OwnersController extends Controller
{
    protected $user;
    protected $owners;

    public function __construct(Owners $owners)
    {
        $this->user = Auth()->guard('api')->user();
        $this->owners = new Repository($owners);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $owners = $this->owners->getModel()->where('user_id', $this->user->id)
                    ->orderBy('name', 'asc')
                    ->paginate(env('APP_PAGINATE'));

        return compact('owners');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  App\Http\Requests\OwnerRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(OwnerRequest $request)
    {
        $owner = $this->owners->getModel();
        $owner->user_id = $this->user->id;
        $owner->fill($request->all());
        $owner->save();

        if($owner->id){
            return $owner;
        }
        return $this->error('Erro ao cadastrar proprietário.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $owner = $this->owners->getModel()->where('user_id', $this->user->id)->find($id);

        if($owner->id){
            return $owner;
        }
        return $this->error('Nenhum proprietário encontrado.');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  App\Http\Requests\OwnerRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(OwnerRequest $request, $id)
    {
        $owner = $this->owners->getModel()->where('user_id', $this->user->id)->find($id);

        if($owner->id){
            $owner->fill($request->all());
            if($owner->save()){
                return $this->success('Dados atualizados com sucesso.');
            }
            return $this->error('Erro ao atualizar dados.');
        }
        return $this->error('Proprietário não encontrado.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $owner = $this->owners->getModel()->where('user_id', $this->user->id)->find($id);

        if($owner->id){
            if($owner->delete()){
                return $this->success('Proprietário excluído com sucesso.');
            }
            return $this->error('Erro ao excluir proprietário.');
        }
        return $this->error('Proprietário não encontrado.');


    }
}
