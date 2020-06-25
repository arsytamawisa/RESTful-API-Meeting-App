<?php

namespace App\Http\Controllers;

use App\User;
use App\Meeting;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
     public function store(Request $request)
     {
          $this->validate($request, [
               'meeting_id' => 'required',
               'user_id'    => 'required',
          ]);

          $meeting   = Meeting::findOrFail($request->meeting_id);
          $user      = User::findOrFail($request->user_id);
          $message   = [
               'message'       => 'User is already registered for meeting',
               'user'          => $user,
               'meeting'       => $meeting,
               'unregister'    => [
                    'href'     => 'api/v1/meeting/register/' . $meeting->id,
                    'method'   => 'DELETE',
               ]
          ];

          if ( $meeting->users()->where('users.id', $request->user_id)->first() )
          {
               return response()->json($message, 404);
          }

          $user->meetings()->attach($meeting);

          $response = [
               'message' => 'Success, user registered for meeting',
               'meeting' => $meeting,
               'user'    => $user,
               'unregister'    => [
                    'href'     => 'api/v1/meeting/register/' . $meeting->id,
                    'method'   => 'DELETE',
               ]
          ];

          return response()->json($response, 201);

     }


     public function destroy(Request $request, $id)
     {
          $this->validate($request, ['user_id' => 'required']);

          $meeting = Meeting::findOrFail($id);

          // If user_id not registered on meeting
          if ( $meeting->users()->where('users.id', $request->user_id)->first() == null )
          {
               return $response = [
                    'message'      => 'User is not registered for meeting',
               ];
          }

          // Unregister user for meeting
          $meeting->users()->wherePivot('user_id', $request->user_id)->detach();

          $response = [
               'message'      => 'Success, user unregistered for meeting.',
               'meeting'      => $meeting,
               'create'       => [
                    'href'    => 'api/v1/meeting/register',
                    'method'  => 'POST',
                    'params'  => 'user_id, meeting_id',
               ]
          ];

          return response()->json($response, 200);
     }
}
