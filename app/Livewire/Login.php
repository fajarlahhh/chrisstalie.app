<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class Login extends Component
{
    public $email, $password, $remember;

    public function login() {
        $this->validate([
            "email" => "required|email|min:3",
            "password" => "required"
        ]);
        
        if (Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
            return $this->redirect('/');
        } else {
            session()->flash('danger', 'Invalid Credential');
        }
    }

    public function render()
    {
        return view('livewire.login');
    }
}
