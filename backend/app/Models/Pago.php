<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pago extends Model
{
    use HasFactory;

    protected $primaryKey = 'pagoId';
    protected $fillable = ['pagoMonto', 'clases_users_userId', 'clases_curso_cursoId', 'pagoType_pagoTypeId'];

    public function clase()
    {
        return $this->belongsTo(Clase::class, ['clases_users_userId', 'clases_curso_cursoId'], ['users_userId', 'curso_cursoId']);
    }

    public function pagoType()
    {
        return $this->belongsTo(PagoType::class, 'pagoType_pagoTypeId', 'pagoTypeId');
    }
}
