<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HistoryController extends Controller
{
    public function __construct()
    {
        
    }

    public function index(Request $request)
    {
        return $request->all();
    }

    public function show(Request $request, $historyId)
    {
        
    }
}
