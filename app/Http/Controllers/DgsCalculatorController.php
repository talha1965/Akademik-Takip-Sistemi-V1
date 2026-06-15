<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DgsCalculatorController extends Controller
{
   /**
     * DGS Hesaplama sayfasını gösterir.
     */
    public function index()
    {
        // 'academic.' ön ekini kaldırdık, çünkü dosya doğrudan views klasörünün içinde.
        return view('dgs-calculator');
    }
}
