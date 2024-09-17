<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Clase extends Model
{
    use HasFactory;
    protected $primaryKey = ['users_userId', 'curso_cursoId']; //llave primaria de la tabla clase

    public $incrementing = false;
    /**
     * relNota= campo de la nota adquirida en la tabla
     * relAprobado = booleano que dice si aprobo o no el curso
     * relPrueba = Campo que guarda la estructura de la prueba
     * relVerificacion = en caso de que el curso cueste dinero este campo verifica si ha pagado o no
     * relCode = en caso de que el curso cueste dinero se generara un codigo para pagar
     */
    protected $fillable = ['relNota', 'relAprobado', 'relPrueba', 'relVerificacion', 'relCode'];
    //funcion de relacion mucho a uno

    public function user()
    {
        return $this->belongsTo(User::class, 'users_userId', 'userId');
    }
    //funcion de relacion muchos a uno
    public function curso()
    {
        return $this->belongsTo(Curso::class, 'curso_cursoId', 'cursoId');
    }
    //funcion de relacion uno a uno
    public function certificado()
    {
        return $this->hasOne(Certificado::class, 'clases_users_userId', 'users_userId')
                    ->where('clases_curso_cursoId', $this->curso_cursoId);
    }
    //funcion uno a muchos
    public function pagos()
    {
        return $this->hasMany(Pago::class, 'clases_users_userId', 'users_userId')
                    ->where('clases_curso_cursoId', $this->curso_cursoId);
    }
}
