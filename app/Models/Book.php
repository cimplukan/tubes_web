<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Book extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function allData(){
        return DB::table('books')->get();
    }

    public function searchData($request){
        return DB::table('books')->where('judul_buku','LIKE', '%'.$request->search.'%')->get();
    }

    public function detailData($id_buku){
        return DB::table('books')->where('id', $id_buku)->first();
    }

    public function addData($data){
        DB::table('books')->insert($data);
    }

    public function editData($id_buku, $data){
        DB::table('books')
            ->where('id', $id_buku)
            ->update($data);
    }

    public function deleteData($id_buku){
        DB::table('books')
            ->where('id', $id_buku)
            ->delete();
    }
}
