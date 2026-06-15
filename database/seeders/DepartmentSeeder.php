<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Department;

class DepartmentSeeder extends Seeder
{
    public function run(): void
    {
        // 'code' (Bölüm Kodu) alanları eklendi
        Department::create([
            'name' => 'Bilgisayar Programcılığı', 
            'code' => 'BİLP',
            'faculty' => 'Eskişehir Meslek Yüksekokulu'
        ]);
        
        Department::create([
            'name' => 'Bilgisayar Mühendisliği', 
            'code' => 'BİLM',
            'faculty' => 'Mühendislik-Mimarlık Fakültesi'
        ]);
        
        Department::create([
            'name' => 'Yazılım Mühendisliği', 
            'code' => 'YAZM',
            'faculty' => 'Mühendislik-Mimarlık Fakültesi'
        ]);
    }
}