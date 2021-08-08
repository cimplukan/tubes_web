<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BukuModel;

class BukuController extends Controller
{
    public function __construct()
    {
        $this->BukuModel = new BukuModel();
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
            'id_buku' => 'required|unique:tbl_buku,id_buku|min:4|max:5',
            'judul_buku' => 'required',
            'kategori' => 'required',
            'penulis' => 'required',
            'penerbit' => 'required',
            'foto_buku' => 'required|mimes:jpg,jpeg,bmp,png|max:1024',
        ], [
            'id_buku.required' => 'wajib diisi !!',
            'id_buku.unique' => 'Nip ini sudah sudah ada !!!',
            'id_buku.min' => 'Min 4 karakter',
            'id_buku.max' => 'Max 5 karakter',
            'judul_buku.required' => 'wajib diisi !!',
            'kategori.required' => 'wajib diisi !!',
            'penulis.required' => 'wajib diisi !!',
            'penerbit.required' => 'wajib diisi !!',
            'foto_buku.required' => 'wajib diisi !!'
        ]);

        $file = Request()->foto_buku;
        $fileName = Request()->id_buku.'.'.$file->extension();
        $file->move(public_path('foto_buku'), $fileName);
        
        $data = [
            'id_buku' => Request()->id_buku,
            'judul_buku' => Request()->judul_buku,
            'kategori' => Request()->kategori,
            'penulis' => Request()->penulis,
            'penerbit' => Request()->penerbit,
            'foto_buku' => $fileName,
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
            'id_buku' => 'required|min:4|max:5',
            'judul_buku' => 'required',
            'kategori' => 'required',
            'penulis' => 'required',
            'penerbit' => 'required',
            'foto_buku' => 'mimes:jpg,jpeg,bmp,png|max:1024',
        ], [
            'id_buku.required' => 'wajib diisi !!',
            'id_buku.min' => 'Min 4 karakter',
            'id_buku.max' => 'Max 5 karakter',
            'judul_buku.required' => 'wajib diisi !!',
            'kategori.required' => 'wajib diisi !!',
            'penulis.required' => 'wajib diisi !!',
            'penerbit.required' => 'wajib diisi !!',
        ]);

        if (Request()->foto_buku <> ""){
            $file = Request()->foto_buku;
            $fileName = Request()->id_buku.'.'.$file->extension();
            $file->move(public_path('foto_buku'), $fileName);
        
            $data = [
                'id_buku' => Request()->id_buku,
                'judul_buku' => Request()->judul_buku,
                'kategori' => Request()->kategori,
                'penulis' => Request()->penulis,
                'penerbit' => Request()->penerbit,
                'foto_buku' => $fileName,
            ];

            $this->BukuModel->editData($id_buku, $data);
        }else{
            $data = [
                'id_buku' => Request()->id_buku,
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
