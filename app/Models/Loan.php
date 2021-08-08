<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Loan extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function allData()
    {
        return DB::table('loans')
            ->join('books', 'loans.book_id', '=', 'books.id')
            ->join('members', 'loans.member_id', '=', 'members.id')
            ->select('loans.*', 'books.judul_buku', 'members.nama')
            ->orderByRaw('loans.created_at DESC')
            ->get();
    }

    public function searchData($request)
    {
        return DB::table('loans')->where('peminjam','LIKE', '%'.$request->search.'%')->get();
    }

    public function detailData($id_peminjaman)
    {
        return DB::table('loans')
            ->join('books', 'loans.book_id', '=', 'books.id')
            ->join('members', 'loans.member_id', '=', 'members.id')
            ->select('loans.*', 'books.judul_buku', 'members.nama')
            ->where('loans.id', $id_peminjaman)->first();
    }

    public function addData($data)
    {
        DB::table('loans')->insert($data);
    }

    public function editData($id_peminjaman, $data)
    {
        DB::table('loans')
            ->where('id', $id_peminjaman)
            ->update($data);
    }

    public function deleteData($id_peminjaman)
    {
        DB::table('loans')
            ->where('id', $id_peminjaman)
            ->delete();
    }
}
