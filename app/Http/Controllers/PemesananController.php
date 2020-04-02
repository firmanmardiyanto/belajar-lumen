<?php

namespace App\Http\Controllers;

use App\Models\Pemesanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class PemesananController extends Controller
{
  public function index()
  {
    if (Gate::denies('read-pemesanan')) {
      return response()->json([
        'success' => false,
        'status' => 403,
        'message' => 'You Are unauthorized'
      ], 403);
    }

    if (Auth::user()->role === 'admin') {
      $pemesanan = Pemesanan::OrderBy("id", "DESC")->paginate(2)->toArray();
    } else {
      $pemesanan = Pemesanan::Where(['id_pelanggan' => Auth::user()->id])->OrderBy("id", "DESC")->paginate(2)->toArray();
    }

    $response = [
      "total_count" => $pemesanan["total"],
      "limit" => $pemesanan["per_page"],
      "pagination" => [
      "next_page" => $pemesanan["next_page_url"],
      "current_page" => $pemesanan["current_page"]
    ],
      "data" => $pemesanan["data"],
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
      if (Gate::denies('create-pemesanan')) {
        return response()->json([
          'success' => false,
          'status' => 403,
          'message' => 'You Are unauthorized'
        ], 403);
      }

      if (Auth::user()->role === 'admin') {
        $input = $request->all();
        $validationRules = [
          'id_pelanggan' => 'required|numeric',
          'id_kamar' => 'required|numeric',
          'tanggal_pesan' => 'required|date',
          'tgl_check_in' => 'required|date',
          'tgl_check_out' => 'required|date',
          'lama_inap' => 'required|numeric',
          'total' => 'required|numeric'
        ];

        $validator = \Validator::make($input, $validationRules);

        if ($validator->fails()) {
          return response()->json($validator->errors(), 400);
        }
          $pemesanan = Pemesanan::create($input);
        } else {

        $input = $request->all();
        $validationRules = [
          'id_kamar' => 'required|numeric',
          'tanggal_pesan' => 'required|date',
          'tgl_check_in' => 'required|date',
          'tgl_check_out' => 'required|date',
          'lama_inap' => 'required|numeric',
          'total' => 'required|numeric'
        ];

        $validator = \Validator::make($input, $validationRules);

        if ($validator->fails()) {
          return response()->json($validator->errors(), 400);
        }
          $pemesanan = new Pemesanan;
          $pemesanan->id_pelanggan = Auth::user()->id;
          $pemesanan->id_kamar = $request->input('id_kamar');
          $pemesanan->tanggal_pesan = $request->input('tanggal_pesan');
          $pemesanan->tgl_check_in = $request->input('tgl_check_in');
          $pemesanan->tgl_check_out = $request->input('tgl_check_out');
          $pemesanan->lama_inap = $request->input('lama_inap');
          $pemesanan->total = $request->input('total');
          $pemesanan->save();
        }
          return response()->json($pemesanan, 200);
    }

   /**
    *Display the specified resource
    *
    *@param int $id
    *@return \Illuminate\Http\Response
    */

    public function show($id)
    {
      $pemesanan = Pemesanan::find($id);
        if(!$pemesanan) {
          abort(404);
        }

        if (Gate::denies('show_pemesanan', $pemesanan)) {
          return response()->json([
            'success' => false,
            'status' => 403,
            'message' => 'You Are unauthorized'
        ], 403);
      }
        return response()->json($pemesanan,200);
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
      $pemesanan = Pemesanan::find($id);

      if(!$pemesanan) {
        abort(404);
      }

      if (Gate::denies('update-pemesanan', $pemesanan)) {
        return response()->json([
          'success' => false,
          'status' => 403,
          'message' => 'You Are unauthorized'
        ], 403);
      }

      $validationRules = [
        'id_kamar' => 'required|numeric',
        'tanggal_pesan' => 'required|date_format:Y-m-d H:i:s',
        'tgl_check_in' => 'required|date',
        'tgl_check_out' => 'required|date',
        'lama_inap' => 'required|numeric',
        'total' => 'required|numeric'
      ];

      $validator = \Validator::make($input, $validationRules);

      if($validator->fails()) {
        return response()->json($validator->errors(), 400);
      }

        $pemesanan->fill($input);
        $pemesanan->save();

        return response()->json($pemesanan,200);
    }

   /**
   * Remove the specified resource from storage
   *
   * @param int $id
   * @return  \Illuminate\Http\Response
   */

   public function delete($id)
   {
      $pemesanan= Pemesanan::find($id);

      if(!$pemesanan) {
        abort(404);
      }

      if (Gate::denies('delete-pemesanan', $pemesanan)) {
        return response()->json([
          'success' => false,
          'status' => 403,
          'message' => 'You Are unauthorized'
        ], 403);
      }
        $pemesanan->delete();
        $message =['message' => 'deleted succesfully', 'pemesanan_id' => $id];

        return response()->json($message,200);
   }
}
