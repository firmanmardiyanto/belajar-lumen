<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePemesananTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pemesanan', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->integer('id_pelanggan')->index('id_pelanggan_foreign');
            $table->integer('id_kamar')->index('id_kamar_foreign');
            $table->datetime('tanggal_pesan');
            $table->date('tgl_check_in', 100);
            $table->date('tgl_check_out', 100);
            $table->integer('lama_inap');
            $table->integer('total');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pemesanan');
    }
}
