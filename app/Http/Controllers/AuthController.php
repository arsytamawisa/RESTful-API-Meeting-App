<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
     public function store(Request $request)
     {
          $this->validate($request, [
               'name'         => 'required',
               'email'        => 'required',
               'password'     => 'required',
          ]);

          $user = new User([
               'name'         => $request->name,
               'email'        => $request->email,
               'password'     => bcrypt($request->password),
          ]);

          if ($user->save())
          {
               $user->signin = [
                    'href'   => 'api/v1/user/signin',
                    'method' => 'post',
                    'params' => 'name, email, password',
               ];

               $response = [
                    'message' => 'Success, user has been created',
                    'user'    => $user,
               ];

               return response()->json($response, 201);
          }

          return response()->json($response, 404);
     }


     public function signin(Request $request)
     {
          //
     }
}
