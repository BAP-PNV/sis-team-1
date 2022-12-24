<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\User\UserRepositoryInterface;

class UsersController extends Controller
{
   private $userRepository;
  
   public function __construct(UserRepositoryInterface $userRepository)
   {
       $this->userRepository = $userRepository;
   }

   public function index()
   {
       $users = $this->userRepository->getUser();
    return $users;
   }
}