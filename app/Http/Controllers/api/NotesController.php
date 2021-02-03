<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\NoteRequest;
use App\Models\Notes;
use App\Repositories\Eloquent\Repository;
use Illuminate\Http\Request;

class NotesController extends Controller
{
    protected $user;
    protected $notes;

    public function __construct(Notes $notes)
    {
        $this->user = Auth()->guard('api')->user();
        $this->notes = new Repository($notes);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $notes = $this->notes->getModel()->where('user_id', $this->user->id)
                                         ->where('uid', $request->uid)
                                         ->with('user')
                                         ->orderBy('id', 'desc')
                                         ->paginate(env('APP_PAGINATE'));

        return compact('notes');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  App\Http\Requests\NoteRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(NoteRequest $request)
    {
        $note = $this->notes->getModel();
        $note->user_id = $this->user->id;
        $note->fill($request->all());
        $note->save();

        if($note->id){
            return $note->fresh('user');
        }

        return $this->error('Erro ao cadastrar nota.');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  App\Http\Requests\NoteRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(NoteRequest $request, $id)
    {
        $note = $this->notes->getModel()->where('user_id', $this->user->id)->find($id);

        if($note->id){
            $note->fill($request->all());

            if($note->save()){
                return $this->success('Nota atualizada com sucesso.');
            }
            return $this->error('Erro ao atualizar a nota.');
        }
        return $this->error('Nota não encontrada.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $note = $this->notes->getModel()->where('user_id', $this->user->id)->find($id);

        if($note->id){
            if($note->delete()){
                return $this->success('Nota excluída com sucesso.');
            }
            return $this->error('Erro ao excluir a nota.');
        }
        return $this->error('Nota não encontrada.');
    }
}
