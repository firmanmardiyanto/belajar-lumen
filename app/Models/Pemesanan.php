<?php  
 namespace App\Models;  
 use Illuminate\Database\Eloquent\Model;  
 class Pemesanan extends Model   
 {  
      // Pemesanan -> table_name = pemesanan  
      // custome table name;  
      // protected $table ='table_name'  
      //define column name
      protected $table ='pemesanan';  
      protected $fillable = array('id_pelanggan','id_kamar','tanggal_pesan','tgl_check_in','tgl_check_out','lama_inap','total');  

      public $timestamps = true;
 } 