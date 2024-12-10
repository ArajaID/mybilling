<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Buku Bank</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .table th,
        .table td {
            border: 1px solid #000;
            padding: 8px;
        }

        .table th {
            background-color: #f2f2f2;
        }

        .text-right {
            text-align: right
        }

        .text-center {
            text-align: center;
        }

        .tbody {
            font-size: 13px
        }
    </style>
</head>

<body>
    <img src="data:image/png;base64,<?= base64_encode(file_get_contents(base_path('public/img/logo-aionios.png'))) ?>"
        width="150">
    <hr>
    <h3 class="text-center">Buku Bank</h3>
    <p class="text-center">Periode
        {{ \Carbon\Carbon::parse($tanggalAwal)->isoFormat('D MMM Y') . ' s/d ' . \Carbon\Carbon::parse($tanggalAkhir)->isoFormat('D MMM Y') }}
    </p>

    <table class="table">
        <thead>
            <tr>
                <th scope="col">Tanggal</th>
                <th scope="col">Kategori</th>
                <th scope="col">Deskripsi</th>
                <th scope="col" class="text-right">Debit</th>
                <th scope="col" class="text-right">Kredit</th>
            </tr>
        </thead>
        <tbody class="tbody">
            @foreach ($dataTransaksi as $data)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($data->tanggal)->isoFormat('D MMM Y') }}</td>
                    <td>{{ $data->kategori }}</td>
                    <td>{{ $data->sumber . ' ' . $data->deskripsi }}</td>
                    <td class="text-right">{{ $data->debit != 0 ? number_format($data->debit) : '' }}</td>
                    <td class="text-right">{{ $data->kredit != 0 ? number_format($data->kredit) : '' }}</td>
                </tr>
            @endforeach
            <tr>
                <th scope="col" colspan="3" class="text-right">Total</th>
                <th scope="col" class="text-right">{{ number_format($totalDebit) }}</th>
                <th scope="col" class="text-right">{{ number_format($totalKredit) }}</th>
            </tr>
        </tbody>
    </table>
</body>

</html>
