<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Member extends Model
{
    use HasFactory;
    public function allData(){
        return DB::table('members')->get();
    }

    public function searchData($request){
        return DB::table('members')->where('nama','LIKE', '%'.$request->search.'%')->get();
    }

    public function detailData($id_anggota){
        return DB::table('members')->where('id', $id_anggota)->first();
    }

    public function addData($data){
        DB::table('members')->insert($data);
    }

    public function editData($id_anggota, $data){
        DB::table('members')
            ->where('id', $id_anggota)
            ->update($data);
    }

    public function deleteData($id_anggota){
        DB::table('members')
            ->where('id', $id_anggota)
            ->delete();
    }    
}
