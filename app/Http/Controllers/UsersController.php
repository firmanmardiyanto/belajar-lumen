<?php

namespace App\Http\Controllers;

use App\Models\Users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;


class UsersController extends Controller
  {
    public function index()
      {
        if (Gate::denies('read-users')) {
          return response()->json([
            'success' => false,
            'status' => 403,
            'message' => 'You Are unauthorized'
          ], 403);
        }

        if (Auth::user()->role === 'admin') {
          $users = Users::OrderBy("id", "DESC")->paginate(2)->toArray();
        } else {
          $users = Users::Where(['id' => Auth::user()->id])->OrderBy("id", "DESC")->paginate(2)->toArray();
        }

        $response = [
          "total_count" => $users["total"],
          "limit" => $users["per_page"],
          "pagination" => [
          "next_page" => $users["next_page_url"],
          "current_page" => $users["current_page"]
        ],
          "data" => $users["data"],
        ];
          return response()->json($response,200);
      }

 	   /**
       * Store a newly created resource in storage.
       *
       * @param \Illuminate\Http\Request $request
       * @return \Illuminate\Http\Respons
       */

      public function store(Request $request)
      {
        if (Gate::denies('create-users')) {
          return response()->json([
            'success' => false,
            'status' => 403,
            'message' => 'You Are unauthorized'
          ], 403);
        }

          $this->validate($request, [
            'nama' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed',
          ]);

          $input = $request->all();

          //validation
          $validationRules = [
            'nama' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed',
          ];

          $validator = \Validator::make($input, $validationRules);

          if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
          }

          //create user
          $users = new Users;
          $users->nama = $request->input('nama');
          $users->email = $request->input('email');
          $plainPassword = $request->input('password');
          $users->password = app('hash')->make($plainPassword);
          $users->save();

          return response()->json($users, 200);
      }


	 /**
	   * Update the specified resource in storage
	   *
	   * @param \Illuminate\Http\Request $request
	   * @param int $id
	   * @return  \Illuminate\Http\Response
	   */

	public function update(Request $request, $id)
  {
      $input = $request->all();
      $users = Users::find($id);

      if(!$users) {
        abort(404);
      }

      if (Gate::denies('update-users')) {
        return response()->json([
          'success' => false,
          'status' => 403,
          'message' => 'You Are unauthorized'
        ], 403);
      }

      $validationRules = [
        'nama' => 'required|string',
        'email' => 'required|email',
        'password' => 'required|confirmed',
      ];

      $validator = \Validator::make($input, $validationRules);

      if ($validator->fails()) {
        return response()->json($validator->errors(), 400);
      }

      $users->fill($input);
      $plainPassword = $request->input('password');
      $users->password = app('hash')->make($plainPassword);
      $users->save();

      return response()->json($users, 200);
}

	/**
	* Remove the specified resource from storage
	*
	* @param int $id
	* @return  \Illuminate\Http\Response
	*/

	public function delete($id)
	{
	  $users= Users::find($id);

	  if(!$users) {
	     abort(404);
	   }

    if (Gate::denies('delete-users', $users)) {
      return response()->json([
        'success' => false,
        'status' => 403,
        'message' => 'You Are unauthorized'
      ], 403);
    }

      $users->delete();
	    $message =['message' => 'deleted succesfully', 'users_id' => $id];

	    return response()->json($message,200);
	}
}
