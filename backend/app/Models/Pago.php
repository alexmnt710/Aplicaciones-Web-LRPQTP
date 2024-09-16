<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pago extends Model
{
    use HasFactory;
    //Llave primaria de la tabla

    protected $primaryKey = 'pagoId';
     /**
     * PagoMonto = Valor de la tabla 
     * clase_users_userId = campo de la relacion con usuarios
     * clase_curso_cursoId= campo de la relacion de curso
     * pagoType_PagoTypeId = campo de la relacion con tipo de curso
     */

    protected $fillable = ['pagoMonto', 'clases_users_userId', 'clases_curso_cursoId', 'pagoType_pagoTypeId'];

    //funcion de la relacion uno a muchos
    public function clase()
    {
        return $this->belongsTo(Clase::class, ['clases_users_userId', 'clases_curso_cursoId'], ['users_userId', 'curso_cursoId']);
    }
    //funcion de relacion uno a muchos

    public function pagoType()
    {
        return $this->belongsTo(PagoType::class, 'pagoType_pagoTypeId', 'pagoTypeId');
    }
}
