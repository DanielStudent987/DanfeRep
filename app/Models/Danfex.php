<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Danfex extends Model
{
    use HasFactory;

    protected $table = 'danfes'; 

    protected $fillable = [
        'chave',
        'content_xml',
        'inserido_por',
    ];

    //Relacionamento entre tabelas do bd
    public function user() {
        return $this->belongsTo(User::class, 'inserido_por');
    }
}
