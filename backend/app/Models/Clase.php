<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Clase extends Model
{
    use HasFactory;
    protected $primaryKey = ['users_userId', 'curso_cursoId']; // Si utilizas llaves compuestas, ajusta en el uso
    public $incrementing = false; // Indica que la clave primaria no es un incremento automático
    protected $fillable = ['relNota', 'relAprobado', 'relPrueba', 'relVerificacion', 'relCode'];

    public function user()
    {
        return $this->belongsTo(User::class, 'users_userId', 'userId');
    }

    public function curso()
    {
        return $this->belongsTo(Curso::class, 'curso_cursoId', 'cursoId');
    }

    public function certificado()
    {
        return $this->hasOne(Certificado::class, 'clases_users_userId', 'users_userId')
                    ->where('clases_curso_cursoId', $this->curso_cursoId);
    }

    public function pagos()
    {
        return $this->hasMany(Pago::class, 'clases_users_userId', 'users_userId')
                    ->where('clases_curso_cursoId', $this->curso_cursoId);
    }
}
