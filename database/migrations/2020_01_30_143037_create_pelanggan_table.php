<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePelangganTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pelanggan', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('nama',100);
            $table->string('nik',100);
            $table->string('no_hp',100);
            $table->date('tanggal_lahir',100);
            $table->enum('jenis_kelamin', array('laki-laki','perempuan'))->default('laki-laki');
            $table->text('alamat', 65535);
            $table->integer('id_pelanggan')->index('id_pelanggan_foreign');

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
        Schema::dropIfExists('pelanggan');
    }
}
