<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak KRS - {{ $krs->kode_krs }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            padding: 0;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            border: 1px solid #ddd;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .header h3 {
            margin: 5px 0;
            font-size: 16px;
            color: #555;
        }
        .info-section {
            margin-bottom: 20px;
        }
        .info-section table {
            width: 100%;
            border-collapse: collapse;
        }
        .info-section td {
            padding: 5px;
        }
        .info-section td:first-child {
            width: 120px;
            font-weight: bold;
        }
        .table-mk {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .table-mk th, .table-mk td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        .table-mk th {
            background-color: #f2f2f2;
        }
        .total-sks {
            margin-top: 20px;
            text-align: right;
            font-weight: bold;
        }
        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 12px;
            color: #777;
        }
        .status {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 3px;
            font-size: 12px;
            font-weight: bold;
        }
        .status-approved {
            background-color: #d4edda;
            color: #155724;
        }
        .status-pending {
            background-color: #fff3cd;
            color: #856404;
        }
        .status-rejected {
            background-color: #f8d7da;
            color: #721c24;
        }
        @media print {
            body {
                margin: 0;
                padding: 0;
            }
            .no-print {
                display: none;
            }
            .container {
                border: none;
                padding: 0;
            }
        }
        .btn-print {
            display: block;
            width: 150px;
            margin: 20px auto 0;
            padding: 10px;
            background-color: #007bff;
            color: white;
            text-align: center;
            text-decoration: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .btn-print:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>UNIVERSITAS SIAKAD</h1>
            <h3>KARTU RENCANA STUDI (KRS)</h3>
            <p>Semester {{ $krs->semester }} Tahun Akademik {{ $krs->tahun }}</p>
        </div>
        
        <div class="info-section">
            <table>
                <tr><td>Kode KRS</td><td>: {{ $krs->kode_krs }}</td></tr>
                <tr><td>Tanggal KRS</td><td>: {{ \Carbon\Carbon::parse($krs->tgl_krs)->format('d/m/Y') }}</td></tr>
                <tr><td>Status</td>
                    <td>: 
                        <span class="status 
                            @if($krs->status == 'approved') status-approved
                            @elseif($krs->status == 'rejected') status-rejected
                            @else status-pending @endif">
                            {{ ucfirst($krs->status) }}
                        </span>
                    </td>
                </tr>
            </table>
        </div>
        
        <div class="info-section">
            <h4>Data Mahasiswa</h4>
            <table>
                <tr><td>NIM</td><td>: {{ $krs->nim }}</td></tr>
                <tr><td>Nama Mahasiswa</td><td>: {{ $krs->nama_mahasiswa }}</td></tr>
            </table>
        </div>
        
        <div class="info-section">
            <h4>Data Mata Kuliah</h4>
            <table>
                <tr><td>Mata Kuliah</td><td>: {{ $krs->nama_mk }}</td></tr>
                <tr><td>SKS</td><td>: {{ $krs->sks }}</td></tr>
                <tr><td>Dosen Pengampu</td><td>: {{ $krs->nama_dosen }}</td></tr>
                <tr><td>Jadwal</td><td>: {{ $krs->hari }}, {{ substr($krs->jam_mulai,0,5) }} - {{ substr($krs->jam_selesai,0,5) }}</td></tr>
                <tr><td>Ruangan</td><td>: {{ $krs->ruangan }}</td></tr>
            </table>
        </div>
        
        <div class="info-section">
            <h4>Daftar Mata Kuliah Yang Diambil</h4>
            <table class="table-mk">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kode MK</th>
                        <th>Nama Mata Kuliah</th>
                        <th>SKS</th>
                        <th>Dosen</th>
                        <th>Jadwal</th>
                        <th>Ruangan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($allKRS as $index => $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $item->kode_mk ?? '-' }}</td>
                        <td>{{ $item->nama_mk }}</td>
                        <td>{{ $item->sks }}</td>
                        <td>{{ $item->nama_dosen ?? '-' }}</td>
                        <td>{{ $item->hari }} {{ substr($item->jam_mulai,0,5) }}</td>
                        <td>{{ $item->ruangan }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3" style="text-align: right; font-weight: bold;">Total SKS</td>
                        <td colspan="4" style="font-weight: bold;">{{ $totalSKS }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
        
        <div class="footer">
            <p>Dicetak pada: {{ \Carbon\Carbon::now()->format('d/m/Y H:i:s') }}</p>
            <p>Dokumen ini adalah bukti resmi Kartu Rencana Studi (KRS)</p>
        </div>
        
        <button class="btn-print no-print" onclick="window.print()">
            <i class="fas fa-print"></i> Cetak / Simpan PDF
        </button>
    </div>
    
    <script>
        // Auto print when page loads (opsional)
        // window.print();
    </script>
</body>
</html>