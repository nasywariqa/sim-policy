<?php

namespace App\Http\Controllers;

use App\Support\Access;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class SessionController extends Controller
{
    public function login(Request $request)
    {
        $data = $request->validate(['email'=>'required|email', 'password'=>'required|string']);
        if (! Auth::attempt([...$data, 'is_active' => true])) {
            throw ValidationException::withMessages(['email'=>'Email atau password tidak sesuai.']);
        }
        $request->session()->regenerate();
        return response()->json(['message'=>'Berhasil masuk.']);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return response()->json(['message'=>'Berhasil keluar.']);
    }

    public function me(Request $request)
    {
        $user = $request->user();
        $period = Access::period();
        $membership = Access::membership($user);
        return response()->json(['data'=>[
            'id'=>$user->id, 'name'=>$user->name, 'email'=>$user->email,
            'is_super_admin'=>$user->is_super_admin,
            'period'=>$period,
            'membership'=>$membership ? [
                'id'=>$membership->id, 'role'=>$membership->role->slug,
                'role_name'=>$membership->role->name,
                'department'=>$membership->department?->name,
                'is_chairperson'=>$membership->is_chairperson,
            ] : null,
            'permissions'=>Access::permissions($user),
        ]]);
    }
}
