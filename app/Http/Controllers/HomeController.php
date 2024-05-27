<?php

namespace App\Http\Controllers;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;

class HomeController extends Controller
{

    public function index()
    {
        return view('home.index');
    }

    public function updatePassword(Request $request): RedirectResponse
    {
        $user = Auth::user();

        $validated = $request->validate([
            'current_password' => [
                'required',
                'string', 
                function (string $attribute, mixed $value, Closure $fail) use ($user) {
                if (! Hash::check($value, $user->password)) {
                    $fail("The :attribute is invalid.");
                }
            },
        ],
        'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user->update([
            'password' => Hash::make($validated['password']),
        ]);
        return redirect()->route('home')->withStatus('Votre mot de passe a été modifié avec succès');
    }
}
