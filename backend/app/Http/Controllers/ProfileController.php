<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function __invoke(Request $request)
    {
        return response()->json([
            'balance' => $request->user()->balance,
            'assets' => $request->user()->assets,
        ]);
    }
}
