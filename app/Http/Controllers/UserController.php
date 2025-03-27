<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Kreait\Firebase\Contract\Auth;
use Kreait\Firebase\Contract\Firestore;

class UserController extends Controller
{
    protected $auth;
    protected $firestore;

    public function __construct(Auth $auth, Firestore $firestore)
    {
        $this->auth = $auth;
        $this->firestore = $firestore;
    }
}
