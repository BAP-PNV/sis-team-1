<?php

namespace App\Http\Controllers;

use App\Mail\Register;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class RegisterController extends Controller
{
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function index()
    {
        return view('contactForm');
    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function store(Request $req)
    {
        $req->validate([
            'username' => 'required',
            'email' => 'required|email',
            'password' => 'required',
        ],[
            'username.required' => 'Name blank',
        ]);

        Log::debug('Wrong number');

        $user=new User();
        $user->username=$req->username;
        $user->email=$req->email;
        $user->password=$req->password;
        $user->save();
        Mail::to($user->email)->send(new Register($user));
        return redirect()->back()
            ->with(['success' => 'Thank you for contact us. Check your email.']);
    }
}

