<?php

namespace App\Http\Controllers;

use App\Models\Perangkat;
use Illuminate\Http\Request;

class PerangkatController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // tampilkan data seluruh perangkat dan is_active = 1
        $perangkats = Perangkat::where('is_active', 1)->paginate(10);
        return view('perangkat.index', compact('perangkats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // tampilkan form untuk menambahkan perangkat baru
        return view('perangkat.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // validasi data yang diinputkan
        $request->validate([
            'nama_perangkat' => 'required',
        ]);

        // simpan data perangkat baru
        Perangkat::create($request->all());

        toast('Perangkat berhasil ditambah!','success');
        return redirect()->route('perangkat.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // tampilkan form untuk mengedit data perangkat
        $perangkat = Perangkat::find($id);
        return view('perangkat.edit', compact('perangkat'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // validasi data yang diinputkan dan sn unique
        $request->validate([
            'nama_perangkat' => 'required',
            'sn' => 'unique:tb_perangkat,sn,'.$id,
        ]);

        // update data perangkat dan is_active
        Perangkat::find($id)->update($request->all());

        toast('Perangkat berhasil diubah!','success');
        return redirect()->route('perangkat.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
