<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Curso extends Model
{
    use HasFactory;
    protected $primaryKey = 'cursoId';
    protected $fillable = [
        'cursoName', 
        'cursoDescripcion', 
        'cursoNivelId', 
        'cursoValor', 
        'cursoRequisito', 
        'cursoContenido', 
        'cursoCategoriaId'
    ];

    public function clases()
    {
        return $this->hasMany(Clase::class, 'curso_cursoId', 'cursoId');
    }

    public function nivel()
    {
        return $this->belongsTo(Nivel::class, 'cursoNivelId', 'nivelId');
    }

    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'cursoCategoriaId', 'categoriaId');
    }
}
