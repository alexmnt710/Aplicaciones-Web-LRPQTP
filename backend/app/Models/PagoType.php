<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PagoType extends Model
{
    use HasFactory;

    protected $primaryKey = 'pagoTypeId';
    protected $fillable = ['pagoTypeName'];

    public function pagos()
    {
        return $this->hasMany(Pago::class, 'pagoType_pagoTypeId', 'pagoTypeId');
    }
}
