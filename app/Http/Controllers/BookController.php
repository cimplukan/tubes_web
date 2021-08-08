<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function __construct()
    {
        $this->BukuModel = new Book();
        $this->middleware('auth');
    }

    public function index(Request $request){     
        if ($request->has('search')) {
            $data=[
                'buku'=> $this->BukuModel->searchData($request),
            ];
        }else{   
        $data=[
            'buku'=> $this->BukuModel->allData(),
        ];}
        return view('v_buku', $data);
    }

    public function detail($id_buku){
        if (!$this->BukuModel->detailData($id_buku)){
            abort(404);
        }
        $data=[
            'buku'=> $this->BukuModel->detailData($id_buku),
        ];
        return view('v_detailbuku', $data);
    }

    public function add(){
        return view('v_addbuku');
    }

    public function insert(){
        Request()->validate([
            'kode_buku' => 'required|unique:books,kode_buku|min:4|max:4',
            'judul_buku' => 'required',
            'kategori' => 'required',
            'penulis' => 'required',
            'penerbit' => 'required',
            'foto_buku' => 'required|mimes:jpg,jpeg,bmp,png|max:1024',
        ], [
            'kode_buku.required' => 'wajib diisi !!',
            'kode_buku.unique' => 'Kode buku ini sudah sudah ada !!!',
            'kode_buku.min' => 'Min 4 karakter',
            'kode_buku.max' => 'Max 4 karakter',
            'judul_buku.required' => 'wajib diisi !!',
            'kategori.required' => 'wajib diisi !!',
            'penulis.required' => 'wajib diisi !!',
            'penerbit.required' => 'wajib diisi !!',
            'foto_buku.required' => 'wajib diisi !!'
        ]);

        $file = Request()->foto_buku;
        $fileName = Request()->kode_buku.'.'.$file->extension();
        $file->move(public_path('foto_buku'), $fileName);
        
        $data = [
            'kode_buku' => Request()->kode_buku,
            'judul_buku' => Request()->judul_buku,
            'kategori' => Request()->kategori,
            'penulis' => Request()->penulis,
            'penerbit' => Request()->penerbit,
            'foto_buku' => $fileName,
            'created_at' => date('Y-m-d H:m:s'),
            'updated_at' => date('Y-m-d H:m:s')
        ];

        $this->BukuModel->AddData($data);
        return redirect()->route('buku')->with('pesan','Data Berhasil Ditambahkan');
    }

    public function edit($id_buku){
        if (!$this->BukuModel->detailData($id_buku)){
            abort(404);
        }
        $data=[
            'buku'=> $this->BukuModel->detailData($id_buku),
        ];
        return view('v_editbuku', $data);
    }

    public function update($id_buku){
        Request()->validate([
            'kode_buku' => 'required|min:4|max:4',
            'judul_buku' => 'required',
            'kategori' => 'required',
            'penulis' => 'required',
            'penerbit' => 'required',
            'foto_buku' => 'mimes:jpg,jpeg,bmp,png|max:1024',
        ], [
            'kode_buku.required' => 'wajib diisi !!',
            'kode_buku.min' => 'Min 4 karakter',
            'kode_buku.max' => 'Max 4 karakter',
            'judul_buku.required' => 'wajib diisi !!',
            'kategori.required' => 'wajib diisi !!',
            'penulis.required' => 'wajib diisi !!',
            'penerbit.required' => 'wajib diisi !!',
        ]);

        if (Request()->foto_buku <> ""){
            $file = Request()->foto_buku;
            $fileName = Request()->kode_buku.'.'.$file->extension();
            $file->move(public_path('foto_buku'), $fileName);
        
            $data = [
                'kode_buku' => Request()->kode_buku,
                'judul_buku' => Request()->judul_buku,
                'kategori' => Request()->kategori,
                'penulis' => Request()->penulis,
                'penerbit' => Request()->penerbit,
                'foto_buku' => $fileName,
                'updated_at' => date('Y-m-d H:m:s')
            ];

            $this->BukuModel->editData($id_buku, $data);
        }else{
            $data = [
                'kode_buku' => Request()->kode_buku,
                'judul_buku' => Request()->judul_buku,
                'kategori' => Request()->kategori,
                'penulis' => Request()->penulis,
                'penerbit' => Request()->penerbit,
            ];

            $this->BukuModel->editData($id_buku, $data);
        }
        
        return redirect()->route('buku')->with('pesan','Data Berhasil Diupdate');
    }

    public function delete($id_buku){
        $buku = $this->BukuModel->detailData($id_buku);
        if ($buku->foto_buku <> ""){
            unlink(public_path('foto_buku').'/'.$buku->foto_buku);
        }
        
        $this->BukuModel->deleteData($id_buku);
        return redirect()->route('buku')->with('pesan','Data Berhasil Dihapus');
    }
}
