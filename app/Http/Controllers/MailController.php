<?php

namespace App\Http\Controllers;

use App\Mail\Registered;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class MailController extends Controller
{
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function index()
    {
        return view('index');
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

//        Log::debug('Wrong number');

        $user=new User();
        $user->username=$req->username;
        $user->email=$req->email;
        $user->password=$req->password;
//        dd($user->email);
        Mail::to($user->email)->send(new Registered($user));
        return redirect()->back()
            ->with(['success' => 'Thank you for contact us. Check your email.']);
    }
}
