<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DarkModeController extends Controller
{
    public function toggle(Request $request)
    {
        \Log::info('Dark mode toggle called');
        
        $user = auth()->user();
        \Log::info('Current dark_mode: ' . ($user->dark_mode ? 'true' : 'false'));
        
        $user->dark_mode = !$user->dark_mode;
        $user->save();
        
        \Log::info('New dark_mode: ' . ($user->dark_mode ? 'true' : 'false'));

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
