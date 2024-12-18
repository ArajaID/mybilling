<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Telegram\Bot\Laravel\Facades\Telegram;

class PengeluaranController extends Controller
{
    public function index() {
        $dataTransaksiPengeluaran = Transaksi::where('jenis_transaksi', 'Pengeluaran')->orderBy('tanggal', 'desc')->paginate(20);
        
        return view('pengeluaran.index', [
            'pengeluaran' => $dataTransaksiPengeluaran
        ]);
    }

    public function create() {
        return view('pengeluaran.create');
    }

    public function store(Request $request) {

        $validateData = $request->validate([
            'kredit'            => 'required',
            'tanggal'           => 'required',
            'kategori'          => 'required',
            'metode_pembayaran' => 'nullable',
            'deskripsi'         => 'nullable',
        ]);

        $validateData['jenis_transaksi'] = 'Pengeluaran';
        $validateData['kredit'] = $request->kredit;

        Transaksi::create($validateData);

        $kredit = 'Rp. ' . number_format($request->kredit, 0, ',', '.');
        $tanggalTransaksi = Carbon::parse($request->tanggal)->format('d-m-Y');
        $tanggalBuat = Carbon::parse($request->created_at)->format('d-m-Y H:i:s');

        $saldo = 0;
        $transaksiAll = Transaksi::all();
        foreach($transaksiAll as $data) {
            $saldo += $data->debit - $data->kredit;
        }
        $formatSaldo = 'Rp. ' . number_format($saldo, 0, ',', '.');
        

$htmlText = "*TRANSAKSI UANG KELUAR* 🔼";
$htmlText .= "
✅ *BERHASIL*";
$htmlText .= "
`===========================`
`Tanggal   :` $tanggalTransaksi
`Kategori  :` $request->kategori
`Deskripsi :` $request->deskripsi
                                    
`Jumlah    :` *$kredit*
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

        toast('Transaksi pengeluaran berhasil ditambah!','success');
        return redirect()->route('pengeluaran.index');
    }

    public function show(string $id)
    {
        return abort(404);
    }

    public function edit(string $id)
    {
        $pengeluaran = Transaksi::findOrFail($id);

        return view('pengeluaran.edit', compact('pengeluaran'));
    }

    public function update(Request $request, string $id)
    {
        $pengeluaran = Transaksi::findOrFail($id);

        $request->validate([
            'kredit'            => 'required',
            'tanggal'           => 'required',
            'kategori'          => 'required',
            'metode_pembayaran' => 'nullable',
            'deskripsi'         => 'nullable',
        ]);

        $pengeluaran->update([
            'jenis_transaksi'   => 'Pengeluaran',
            'kredit'            => $request->kredit,
            'tanggal'           => $request->tanggal,
            'kategori'          => $request->kategori,
            'metode_pembayaran' => $request->metode_pembayaran,
            'deskripsi'         => $request->deskripsi,
        ]);

        toast('Transaksi pengeluaran berhasil edit!','success');
        return redirect()->route('pengeluaran.index');
    }

    public function destroy(string $id)
    {
        return abort(404);
    }
}
