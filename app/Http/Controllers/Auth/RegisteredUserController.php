<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        if($request->hasFile('fileImage')) {
            $file_post = $request->file('fileImage');
            $file_extension = $file_post->extension();
            $file_name = $file_post->getClientOriginalName();

             $user->addMediaFromRequest('fileImage')->toMediaCollection('images');
//            $user->addMedia(storage_path('app/public/images/tmp' . $request->file . '/' .$filename ))
//            ->toMediaCollection('images');

            // $file_post->storeAs('file', $file_name .'.'.$file_extension);
            // $file_post->storeAs('file', $file_name .'.'.$file_extension, 'public');//if you want to make file public
            // $user->update([
                // 'file' => $file_post->storeAs('file', $user->id .'.'.$file_extension)
                // 'file' => $file_name
            // ]);
        }

        event(new Registered($user));

        Auth::login($user);

        return redirect(RouteServiceProvider::HOME);
    }
}
