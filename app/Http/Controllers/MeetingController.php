<?php

namespace App\Http\Controllers;

use App\Meeting;
use Illuminate\Http\Request;

class MeetingController extends Controller
{
     public function index()
     {
          $meetings = Meeting::all();

          foreach ($meetings as $meeting) {
               $meeting->view = [
                    'href'    => 'api/v1/meeting/' . $meeting->id,
                    'method'  => 'GET',
               ];
          }

          $response = [
               'message' => 'List of all Meetings',
               'data'    => $meetings,
          ];

          return response()->json($response, 200);
     }


     public function store(Request $request)
     {
          $this->validate($request, [
               'title'     => 'required',
               'desc'      => 'required',
               'user_id'   => 'required'
          ]);

          $meeting = new Meeting([
               'title' => $request->title,
               'desc'  => $request->desc,
          ]);

          if ($meeting->save())
          {
               $meeting->users()->attach($request->user_id);

               $meeting->view = [
                    'href'    => 'api/v1/meeting/' . $meeting->id,
                    'method'  => 'GET',
               ];

               $message = [
                    'message' => 'Success, meeting has been created.',
                    'data'    => $meeting,
               ];

               return response()->json($message, 201);
          }
          // else
          return response()->json($response, 404);
     }


     public function show($id)
     {
          $meeting = Meeting::with('users')->where('id', $id)->firstOrFail();

          $meeting->view = [
               'href'    => 'api/v1/meeting',
               'method'  => 'GET',
          ];

          $response = [
               'message' => 'Show meeting information',
               'data'    => $meeting,
          ];

          return response()->json($response, 202);
     }


     public function update(Request $request, $id)
     {
          $this->validate($request, [
               'title'     => 'required',
               'desc'      => 'required',
               'user_id'   => 'required'
          ]);

          $meeting = Meeting::with('users')->where('id', $id)->findOrFail($id);

          if ( !$meeting->users()->where('users.id', $request->user_id)->first() )
          {
               return response()->json(['message' => 'Updating failed, user not match.'], 401);
          };

          // else
          $meeting->title = $request->title;
          $meeting->desc  = $request->desc;

          $meeting->view = [
               'href'    => 'api/v1/meeting' . $meeting->id,
               'method'  => 'GET',
          ];

          $response = [
               'message' => 'Success, meeting has been updated.',
               'data'    => $meeting,
          ];

          return response()->json($response, 201);

     }


     public function destroy(Request $request, $id)
     {
          $meeting = Meeting::with('users')->where('id', $id)->findOrFail($id);

          // Validating user_id
          $this->validate($request, ['user_id' => 'required']);
          if ( !$meeting->users()->where('users.id', $request->user_id)->first() )
          {
               return response()->json(['message' => 'Deleting failed, user not match.'], 401);
          }

          if ($meeting->delete())
          {
               $response = [
                    'message'      => 'Success, meeting has been deleted.',
                    'create'       => [
                         'href'    => 'api/v1/meeting',
                         'method'  => 'POST',
                         'params'  => 'title, desc',
                    ]
               ];
          }

          return response()->json($response, 200);
     }
}
