<?php  
 namespace App\Models;  
 use Illuminate\Database\Eloquent\Model;  
 class Pembayaran extends Model   
 {  
      // Pembayaran -> table_name = pembayaran  
      // custome table name;  
      // protected $table ='table_name'  
      // define column name
      protected $table ='pembayaran';  
      protected $fillable = array('id_pelanggan','id_kamar','id_pemesanan','tanggal_bayar','lama_inap','total');  

      public $timestamps = true;
 } 