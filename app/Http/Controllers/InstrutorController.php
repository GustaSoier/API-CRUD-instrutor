<?php

namespace App\Http\Controllers;

use App\Models\Instrutor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class InstrutorController extends Controller
{

    public $instrutor;

    public function __construct(Instrutor $instrutor) {
        $this -> instrutor = $instrutor;
}
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // return 'CHEGUEI AQUI - INDEX';
        // $instrutor = Instrutor::all();

        $instrutores = $this ->instrutor -> all();
        return response()->json($instrutores, 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    // public function create()
    // {
    //     //
    // }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $request -> validate($this->instrutor->Regras(),
        $this->instrutor->Feedbacks());

        $imagem = $request -> file('foto');

        $imagem_url = $imagem -> store('imagem', 'public');

        // dd($imagem_url);

        $instrutores = $this->instrutor->create([
            'nome' => $request->nome,
            'foto' => $imagem_url
            // 'foto' => $request->foto
        ]);

        return response()->json($instrutores, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param Integer $instrutor
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $instrutores = $this -> instrutor->find($id);

        if($instrutores === null) {
            return response()->json(['error' => 'Não existe dados para esse instrutor'], 404);
        }

        return response()->json($instrutores, 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Instrutor  $instrutor
     * @return \Illuminate\Http\Response
     */
    public function edit(Instrutor $instrutor)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Instrutor  $instrutor
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
       //return 'Cheguei aqui - UPDATE';

       /*
        print_r($request->all()); // Novos dados
        echo '<hr>';
        print_r($instrutor->getAttributes()); // Dados antigos
        */
        $instrutores = $this->instrutor->find($id);

        //    dd($request->nome);
        //    dd($request->file('foto'));

            if($instrutores === null){
                return response()->json(['erro' => 'Impossível realizar a atualização. O instrutor não existe!'], 404);
            }

            if($request->method() === 'PATCH') {
                // return ['teste' => 'PATCH'];

                $dadosDinamico = [];

                foreach ($instrutores->Regras() as $input => $regra) {
                    if(array_key_exists($input, $request->all())) {
                        $dadosDinamico[$input] = $regra;
                    }
                }

                // dd($dadosDinamico);

                $request->validate($dadosDinamico, $this->instrutor->Feedbacks());
            }
            else{
                $request->validate($this->instrutor->Regras(), $this->instrutor->Feedbacks());
            }

            if($request->file('foto') == true) {
                Storage::disk('public')->delete($instrutores->foto);
            }

            $imagem = $request -> file('foto');

            $imagem_url = $imagem -> store('imagem', 'public');

           $instrutores -> update([
                'nome' => $request->nome,
                'foto' => $imagem_url
           ]); // update dos novos dados

           return response()->json($instrutores, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Instrutor  $instrutor
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $instrutores = $this -> instrutor -> find($id);

        if($instrutores === null){
            return response()->json(['erro' => 'Impossível deleter este registro. O instrutor não existe!'], 404);
        }

        // Storage::disk('public')->delete($instrutores->foto);

        Storage::disk('public')->delete($instrutores->foto);

        // return 'Cheguei aqui - DESTROY';
        $instrutores->delete();

        return response()->json(['msg' => 'O registro foi removido com sucesso'], 200);
    }
}
