<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Department;

class DepartmentSeeder extends Seeder
{
    public function run(): void
    {
        $departments = ['IT', 'RRHH', 'Contabilidad', 'Ventas', 'Dirección'];

        foreach ($departments as $name) {
            Department::create(['name' => $name]);
        }
    }
}


