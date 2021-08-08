<?php

namespace App\Http\Controllers;

use App\Models\Member;
use Illuminate\Http\Request;

class MemberController extends Controller
{
    public function __construct()
    {
        $this->AnggotaModel = new Member();
        $this->middleware('auth');
    }

    public function index(Request $request){    

        if ($request->has('search')) {
            $data=[
                'anggota' => $this->AnggotaModel->searchData($request),
            ];
        } else{   
            $data=[
                'anggota' => $this->AnggotaModel->allData(),
            ];
        }
        return view('member.data', $data);
    }

    public function detail($id_anggota)
    {
        if (!$this->AnggotaModel->detailData($id_anggota)){
            abort(404);
        }
        $data = [
            'anggota' => $this->AnggotaModel->detailData($id_anggota),
        ];
        return view('member.detail', $data);
    }

    public function add()
    {
        return view('member.add');
    }

    public function insert(Request $request)
    {
        $request->validate([
            'nama'      => 'required',
            'kelas'     => 'required',
            'jurusan'   => 'required'
        ], [
            'nama.required'     => 'wajib diisi !!',
            'kelas.required'    => 'wajib diisi !!',
            'jurusan.required'  => 'wajib diisi !!',
        ]);
        
        $data = [
            'nama'    => $request->nama,
            'kelas'   => $request->kelas,
            'jurusan' => $request->jurusan,
            'created_at' => date('Y-m-d H:m:s'),
            'updated_at' => date('Y-m-d H:m:s')
        ];

        $this->AnggotaModel->AddData($data);
        return redirect()->route('anggota')->with('pesan','Data Berhasil Ditambahkan');
    }

    public function edit($id_anggota)
    {
        if (!$this->AnggotaModel->detailData($id_anggota)){
            abort(404);
        }
        $data=[
            'anggota'=> $this->AnggotaModel->detailData($id_anggota),
        ];
        return view('member.edit', $data);
    }

    public function update($id_anggota)
    {
        Request()->validate([
            'nama'      => 'required',
            'kelas'     => 'required',
            'jurusan'   => 'required'
        ], [
            'nama.required'     => 'wajib diisi !!',
            'kelas.required'    => 'wajib diisi !!',
            'jurusan.required'  => 'wajib diisi !!',
        ]);
        
        $data = [
            'nama'    => Request()->nama,
            'kelas'   => Request()->kelas,
            'jurusan' => Request()->jurusan,
            'updated_at' => date('Y-m-d H:m:s')
        ];

        $this->AnggotaModel->editData($id_anggota, $data);
        
        return redirect()->route('anggota')->with('pesan','Data Berhasil Diupdate');
    }

    public function delete($id_anggota)
    {        
        $this->AnggotaModel->deleteData($id_anggota);
        return redirect()->route('anggota')->with('pesan','Data Berhasil Dihapus');
    }
}
