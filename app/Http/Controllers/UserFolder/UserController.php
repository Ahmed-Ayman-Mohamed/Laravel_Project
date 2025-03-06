<?php

namespace App\Http\Controllers\UserFolder;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function showUsers()
    {
        $users = [
            'firstName' => 'ahmed',
            'middleName' => 'mohamed',
            'lastName' => 'radwan',
        ];
        return '<pre>' . json_encode($users) . '</pre>';
    }


    public function show()
    {
        return view('user');
    }
}
