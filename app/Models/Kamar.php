<?php  
 namespace App\Models;  
 use Illuminate\Database\Eloquent\Model;  
 class Kamar extends Model   
 {  
      // Kamar -> table_name = kamar  
      // custome table name;  
      // protected $table ='table_name'  
      //define column name
      protected $table ='kamar';  
      protected $fillable = array('jenis','info_kamar','harga');  

      public $timestamps = true;
 } 
