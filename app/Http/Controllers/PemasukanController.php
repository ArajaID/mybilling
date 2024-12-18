<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Telegram\Bot\Laravel\Facades\Telegram;

class PemasukanController extends Controller
{
    public function index() {
        $dataTransaksiPemasukan = Transaksi::where('jenis_transaksi', 'Pemasukan')->orderBy('tanggal', 'desc')->paginate(20);
        
        return view('pemasukan.index', [
            'pemasukan' => $dataTransaksiPemasukan
        ]);
    }

    public function create() {
        return view('pemasukan.create');
    }

    public function store(Request $request) {

        $validateData = $request->validate([
            'debit'             => 'required',
            'tanggal'           => 'required',
            'kategori'          => 'required',
            'metode_pembayaran' => 'nullable',
            'deskripsi'         => 'nullable',
        ]);

        $validateData['jenis_transaksi'] = 'Pemasukan';
        $validateData['debit'] = $request->debit;

        Transaksi::create($validateData);

        $debit = 'Rp. ' . number_format($request->debit, 0, ',', '.');
        $tanggalTransaksi = Carbon::parse($request->tanggal)->format('d-m-Y');
        $tanggalBuat = Carbon::parse($request->created_at)->format('d-m-Y H:i:s');

        $saldo = 0;
        $transaksiAll = Transaksi::all();
        foreach($transaksiAll as $data) {
            $saldo += $data->debit - $data->kredit;
        }
        $formatSaldo = 'Rp. ' . number_format($saldo, 0, ',', '.');
        

$htmlText = "*TRANSAKSI UANG MASUK* 🔽";
$htmlText .= "
✅ *BERHASIL*";
$htmlText .= "
`===========================`
`Tanggal   :` $tanggalTransaksi
`Kategori  :` $request->kategori
`Deskripsi :` $request->deskripsi
                                    
`Jumlah    :` *$debit*
`===========================`
`Saldo     :` *$formatSaldo*
`===========================`
`Dibuat    :` $tanggalBuat
`===========================`
";
                        Telegram::sendMessage([
                            'chat_id' => env('TELEGRAM_CHAT_ID'),
                            'text' => $htmlText,
                            'parse_mode' => 'Markdown'
                        ]);

        toast('Transaksi berhasil ditambah!','success');
        return redirect()->route('pemasukan.index');
    }

    public function show(string $id)
    {
        return abort(404);
    }

    public function edit(string $id)
    {
        $pemasukan = Transaksi::findOrFail($id);

        return view('pemasukan.edit', compact('pemasukan'));
    }

    public function update(Request $request, string $id)
    {
        $pemasukan = Transaksi::findOrFail($id);

        $request->validate([
            'debit'             => 'required',
            'tanggal'           => 'required',
            'kategori'          => 'required',
            'metode_pembayaran' => 'nullable',
            'deskripsi'         => 'nullable',
        ]);

        $pemasukan->update([
            'jenis_transaksi'   => 'Pemasukan',
            'debit'             => $request->debit,
            'tanggal'           => $request->tanggal,
            'kategori'          => $request->kategori,
            'metode_pembayaran' => $request->metode_pembayaran,
            'deskripsi'         => $request->deskripsi,
        ]);

        toast('Transaksi pemasukan berhasil edit!','success');
        return redirect()->route('pemasukan.index');
    }

    public function destroy(string $id)
    {
        return abort(404);
    }
}
