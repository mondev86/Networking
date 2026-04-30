<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Seeder;

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
