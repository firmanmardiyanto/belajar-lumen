<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Users;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
  /**
   * Store a new user
   *
   * @param Request $request
   * @return Response
   */

  public function register (Request $request)
  {
    $this->validate($request, [
      'nama' => 'required|string',
      'email' => 'required|email|unique:users',
      'password' => 'required|confirmed',
    ]);

    $input = $request->all();

    $validationRules = [
      'nama' => 'required|string',
      'email' => 'required|email|unique:users',
      'password' => 'required|confirmed',
    ];

    $validator = \Validator::make($input, $validationRules);

    if ($validator->fails()) {
      return response()->json($validator->errors(), 400);
    }

    $users = new Users;
    $users->nama = $request->input('nama');
    $users->email = $request->input('email');
    $plainPassword = $request->input('password');
    $users->password = app('hash')->make($plainPassword);
    $users->save();

    return response()->json($users, 200);
  }

  /**
   * Get a JWT via given credentials.
   *
   * @param Request $request
   * @return Response
   */

  public function login(Request $request)
  {
    $input = $request->all();

    $validationRules = [
      'email' => 'required|string',
      'password' => 'required|string',
    ];

    $validator = \Validator::make($input, $validationRules);

    if ($validator->fails()) {
      return response()->json($validator->errors(), 400);
    }

    // process login
    $credentials = $request->only(['email','password']);

    if (! $token = Auth::attempt($credentials)) {
      return response()->json(['message' => 'Unauthorized'], 401);
    }
      return response()->json([
        'user' => Auth::user()->nama,
        'token' => $token,
        'token_type' => 'bearer',
        'expires_in' => Auth::factory()->getTTL() * 60
      ], 200);
    }

    public function logout(Request $request)
    {
		  $response =  [
        'user' => Auth::user()->nama,
        'status' => 'success',
        'message' => 'logout'
    ];
		  Auth::guard()->logout();
		  return response()->json($response, 200);
		}
 }
 ?>
