<?php  
 namespace App\Models;  
 use Illuminate\Database\Eloquent\Model;  
 class Pelanggan extends Model   
 {  
      // Pelanggan -> table_name = pelanggan  
      // custome table name;  
      // protected $table ='table_name'  
      //define column name
      protected $table ='pelanggan';  
      protected $fillable = array('nama','nik','no_hp','tanggal_lahir','jenis_kelamin','alamat','id_pelanggan');  

      public $timestamps = true;
 } 