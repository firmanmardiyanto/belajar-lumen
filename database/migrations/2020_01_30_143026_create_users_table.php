<?php
 use Illuminate\Support\Facades\Schema;
 use Illuminate\Database\Schema\Blueprint;
 use Illuminate\Database\Migrations\Migration;
 class CreateUsersTable extends Migration
 {
   /**
    * Run the migrations.
    *
    * @return void
    */
   public function up()
   {
     Schema::create('users', function (Blueprint $table) {
       $table->bigIncrements('id');
       $table->string('nama', 100);
       $table->string('email', 100)->unique('email_unique');
       $table->string('password', 100);
       $table->enum('role', array('user','admin'))->default('user');
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
     Schema::dropIfExists('users');
   }
 }
