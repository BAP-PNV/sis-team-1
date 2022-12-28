<?php

namespace App\Http\Controllers;

use App\Repositories\User\IUserRepository;
use Illuminate\Http\Request;


class UsersController extends Controller
{
   private $userRepository;
  
   public function __construct(IUserRepository $userRepository)
   {
       $this->userRepository = $userRepository;
   }

   public function index()
   {
       $users = $this->userRepository->getUser();
    return $users;
   }
}