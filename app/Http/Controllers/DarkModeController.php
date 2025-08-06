<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DarkModeController extends Controller
{
    public function toggle(Request $request)
    {
        $user = auth()->user();
        $user->dark_mode = !$user->dark_mode;
        $user->save();

        // Si es una request AJAX, devolver JSON
        if ($request->expectsJson() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'dark_mode' => $user->dark_mode
            ]);
        }

        // Si es una request normal, redirigir de vuelta
        return redirect()->back()->with('success', 'Modo ' . ($user->dark_mode ? 'oscuro' : 'claro') . ' activado.');
    }
}
