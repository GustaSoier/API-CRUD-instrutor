<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Instrutor extends Model
{
    use HasFactory;

    protected $fillable = ['nome', 'foto'];

    public function Regras() {
        return [
            'nome' => 'required|unique:instrutors,nome,'.$this->id.'min:3',
            'foto' => 'required'
        ];
    }

    public function Feedbacks() {
            return [
                'required' => 'O campo :attribute é obrigatório',
                'nome.unique' => 'O nome do instrutor já existe!',
                'nome.min' => 'O nome do instrutor deve ser maior que 3 caracteres!'
            ];
    }
}
