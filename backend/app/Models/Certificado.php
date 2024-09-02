<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Certificado extends Model
{
    use HasFactory;
    protected $primaryKey = 'certificadoId';
    protected $fillable = ['ruta', 'clases_users_userId', 'clases_curso_cursoId'];

    public function clase()
    {
        return $this->belongsTo(Clase::class, ['clases_users_userId', 'clases_curso_cursoId'], ['users_userId', 'curso_cursoId']);
    }
}
