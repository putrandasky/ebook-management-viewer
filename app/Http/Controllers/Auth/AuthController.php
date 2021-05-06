<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function __invoke()
    {
        return new UserResource(Auth::user());
    }
}
