<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;
use App\Models\Curso;
use App\Models\Categoria;

class Users extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        Role::create(['name' => 'admin']);
        Role::create(['name' => 'student']);
        Role::create(['name' => 'teacher']);
        DB::table('users')->insert([
            ['userName' => 'admin', 'password' => bcrypt('admin'), 'userNombres' => 'admin', 'userApellidos' => 'admin', 'userCorreo' => '','userWordKey'=>'admin'],
        ]);
        DB::table('pagoType')->insert([
            ['pagoTypeId' => 1, 'pagoTypeName' => 'Tarjeta'],
            ['pagoTypeId' => 2, 'pagoTypeName' => 'Efectivo'],
            ['pagoTypeId' => 3, 'pagoTypeName' => 'Transferencia'],
            ['pagoTypeId' => 4, 'pagoTypeName' => 'Cheque'],
        ]);
        DB::table('nivel')->insert([
            ['nivelId' => 1, 'nivelName' => 'Basico'],
            ['nivelId' => 2, 'nivelName' => 'Intermedio'],
            ['nivelId' => 3, 'nivelName' => 'Avanzado'],
            ['nivelId' => 4, 'nivelName' => 'Profesional'],
        ]);
        Categoria::factory()->count(20)->create(); 
        Curso::factory()->count(20)->create();
    }
}
