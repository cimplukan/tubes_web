<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Loan;
use App\Models\Member;
use Illuminate\Http\Request;

class LoanController extends Controller
{
    public function __construct()
    {
        $this->PeminjamanModel = new Loan();
        $this->middleware('auth');
    }

    public function index(Request $request){    
        // dump($this->PeminjamanModel->allData());die;
        if ($request->has('search')) {
            $data=[
                'peminjaman'=> $this->PeminjamanModel->searchData($request),
            ];
        } else {   
            $data=[
                'peminjaman'=> $this->PeminjamanModel->allData(),
            ];
        }
        return view('loan.data', $data);
    }

    public function detail($id_peminjaman)
    {
        if (!$this->PeminjamanModel->detailData($id_peminjaman)){
            abort(404);
        }

        $data=[
            'loan' => $this->PeminjamanModel->detailData($id_peminjaman),
        ];
        return view('loan.detail', $data);
    }

    public function add()
    {
        $book = new Book();
        $member = new Member();

        $data = [
            'books'     => $book->allData(),
            'members'   => $member->allData()
        ];
        return view('loan.add', $data);
    }

    public function insert()
    {
        Request()->validate([
            'judul_buku' => 'required',
            'peminjam' => 'required'
        ], [
            'judul_buku.required' => 'wajib diisi !!',
            'peminjam.required' => 'wajib diisi !!'
        ]);

        $data = [
            'book_id'           => Request()->judul_buku,
            'member_id'         => Request()->peminjam,
            'tanggal_pinjam'    => date('Y-m-d H:m:s'),
            'tanggal_kembali'   => date('Y-m-d H:m:s', time() + (60*60*24*7)),
            'status'            => 'belum dikembalikan',
            'created_at' => date('Y-m-d H:m:s'),
            'updated_at' => date('Y-m-d H:m:s')
        ];

        $this->PeminjamanModel->AddData($data);
        return redirect()->route('peminjaman')->with('pesan','Data Berhasil Ditambahkan');
    }

    public function edit($id_peminjaman)
    {
        if (!$this->PeminjamanModel->detailData($id_peminjaman)){
            abort(404);
        }

        $book = new Book();
        $member = new Member();

        $data=[
            'books'     => $book->allData(),
            'members'   => $member->allData(),
            'loan'     => $this->PeminjamanModel->detailData($id_peminjaman),
        ];
        return view('loan.edit', $data);
    }

    public function update($id_peminjaman)
    {
        Request()->validate([
            'judul_buku' => 'required',
            'peminjam' => 'required'
        ], [
            'judul_buku.required' => 'wajib diisi !!',
            'peminjam.required' => 'wajib diisi !!'
        ]);

        $data = [
            'book_id'           => Request()->judul_buku,
            'member_id'         => Request()->peminjam,
            'updated_at' => date('Y-m-d H:m:s')
        ];
        $this->PeminjamanModel->editData($id_peminjaman, $data);
    
        return redirect()->route('peminjaman')->with('pesan','Data Berhasil Diupdate');
    }

    public function delete($id_peminjaman)
    {
        $this->PeminjamanModel->deleteData($id_peminjaman);
        return redirect()->route('peminjaman')->with('pesan','Data Berhasil Dihapus');
    }
}

