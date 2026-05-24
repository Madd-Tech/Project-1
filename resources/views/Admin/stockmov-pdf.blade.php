<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Riwayat Pergerakan Stok - {{ config('app.name') }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', Arial, Helvetica, sans-serif;
            font-size: 11px;
            color: #1e293b;
            line-height: 1.5;
            background: #fff;
        }

        /* Header */
        .header {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            color: #fff;
            padding: 24px 32px;
            margin-bottom: 0;
        }

        .header-top {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 12px;
        }

        .header h1 {
            font-size: 20px;
            font-weight: 700;
            letter-spacing: -0.5px;
        }

        .header .store-name {
            font-size: 13px;
            font-weight: 600;
            color: #60a5fa;
            margin-bottom: 2px;
        }

        .header .subtitle {
            font-size: 11px;
            color: #94a3b8;
        }

        .header .export-date {
            font-size: 10px;
            color: #94a3b8;
            text-align: right;
        }

        /* Filter summary bar */
        .filter-bar {
            background: #f1f5f9;
            border: 1px solid #e2e8f0;
            padding: 10px 32px;
            font-size: 10px;
            color: #475569;
            display: flex;
            gap: 24px;
            flex-wrap: wrap;
        }

        .filter-bar strong {
            color: #1e293b;
        }

        /* Table */
        .table-wrapper {
            padding: 0 32px;
            margin-top: 16px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead th {
            background: #f8fafc;
            border-bottom: 2px solid #e2e8f0;
            padding: 10px 12px;
            text-align: left;
            font-size: 9px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #64748b;
        }

        tbody td {
            padding: 9px 12px;
            border-bottom: 1px solid #f1f5f9;
            vertical-align: middle;
        }

        tbody tr:nth-child(even) {
            background: #fafbfc;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        /* Type badges */
        .badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 4px;
            font-size: 9px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        .badge-masuk {
            background: #dcfce7;
            color: #166534;
            border: 1px solid #bbf7d0;
        }

        .badge-keluar {
            background: #fee2e2;
            color: #991b1b;
            border: 1px solid #fecaca;
        }

        .qty-masuk {
            color: #16a34a;
            font-weight: 700;
            font-family: 'DejaVu Sans Mono', monospace;
        }

        .qty-keluar {
            color: #dc2626;
            font-weight: 700;
            font-family: 'DejaVu Sans Mono', monospace;
        }

        .price {
            font-weight: 600;
            color: #1e293b;
        }

        .text-muted {
            color: #94a3b8;
        }

        .reference {
            font-size: 10px;
            color: #64748b;
            font-style: italic;
        }

        /* Summary footer */
        .summary {
            margin: 24px 32px 0;
            display: flex;
            gap: 16px;
        }

        .summary-card {
            flex: 1;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 14px 16px;
            text-align: center;
        }

        .summary-card .label {
            font-size: 9px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #64748b;
            font-weight: 600;
            margin-bottom: 4px;
        }

        .summary-card .value {
            font-size: 18px;
            font-weight: 800;
        }

        .summary-card.total .value { color: #3b82f6; }
        .summary-card.masuk .value { color: #16a34a; }
        .summary-card.keluar .value { color: #dc2626; }

        /* Footer */
        .footer {
            margin-top: 32px;
            padding: 16px 32px;
            border-top: 1px solid #e2e8f0;
            font-size: 9px;
            color: #94a3b8;
            text-align: center;
        }

        /* Empty state */
        .empty-state {
            text-align: center;
            padding: 48px 32px;
            color: #94a3b8;
        }

        .empty-state p {
            font-size: 13px;
        }

        /* Page break helper */
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>

    {{-- Header --}}
    <div class="header">
        <div class="header-top">
            <div>
                <div class="store-name">{{ config('app.name') }}</div>
                <h1>Riwayat Pergerakan Stok</h1>
                <div class="subtitle">Laporan lengkap pergerakan stok inventaris</div>
            </div>
            <div class="export-date">
                <div>Diekspor pada:</div>
                <div style="font-size: 12px; color: #e2e8f0; font-weight: 600;">
                    {{ now()->timezone('Asia/Jakarta')->translatedFormat('d F Y - H:i') }} WIB
                </div>
            </div>
        </div>
    </div>

    {{-- Active filters --}}
    <div class="filter-bar">
        <span>
            <strong>Urutan:</strong>
            @switch($sortBy)
                @case('oldest') Terlama @break
                @case('most_total') Total Terbesar @break
                @default Terbaru
            @endswitch
        </span>

        @if($type)
            <span>
                <strong>Tipe:</strong>
                {{ $type === 'produk masuk' ? 'Produk Masuk' : 'Produk Keluar' }}
            </span>
        @endif

        @if($dateFrom || $dateTo)
            <span>
                <strong>Rentang Tanggal:</strong>
                {{ $dateFrom ?: '...' }} - {{ $dateTo ?: '...' }}
            </span>
        @endif

        <span>
            <strong>Total Data:</strong> {{ $movements->count() }} entri
        </span>
    </div>

    {{-- Data table --}}
    <div class="table-wrapper">
        @if($movements->count() > 0)
            <table>
                <thead>
                    <tr>
                        <th style="width: 5%">#</th>
                        <th style="width: 16%">Tanggal & Waktu</th>
                        <th style="width: 22%">Produk</th>
                        <th style="width: 12%">Tipe</th>
                        <th style="width: 10%" class="text-center">Jumlah</th>
                        <th style="width: 16%" class="text-right">Total Pesanan</th>
                        <th style="width: 19%">Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($movements as $index => $move)
                        <tr>
                            <td class="text-muted">{{ $index + 1 }}</td>
                            <td>
                                <div style="font-weight: 600;">
                                    {{ \Carbon\Carbon::parse($move->created_at)->timezone('Asia/Jakarta')->format('d M Y') }}
                                </div>
                                <div class="text-muted" style="font-size: 9px;">
                                    {{ \Carbon\Carbon::parse($move->created_at)->timezone('Asia/Jakarta')->format('H:i') }} WIB
                                </div>
                            </td>
                            <td style="font-weight: 500;">
                                {{ $move->product->name ?? 'Produk Dihapus' }}
                            </td>
                            <td>
                                <span class="badge {{ $move->type === 'produk masuk' ? 'badge-masuk' : 'badge-keluar' }}">
                                    {{ $move->type }}
                                </span>
                            </td>
                            <td class="text-center {{ $move->type === 'produk masuk' ? 'qty-masuk' : 'qty-keluar' }}">
                                {{ $move->type === 'produk masuk' ? '+' : '-' }}{{ $move->quantity }}
                            </td>
                            <td class="text-right">
                                @if($move->order_total_amount !== null)
                                    <span class="price">Rp {{ number_format($move->order_total_amount, 0, ',', '.') }}</span>
                                @else
                                    <span class="text-muted">&mdash;</span>
                                @endif
                            </td>
                            <td>
                                <span class="reference">{{ $move->reference ?: '-' }}</span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="empty-state">
                <p>Tidak ada data pergerakan stok yang cocok dengan filter yang dipilih.</p>
            </div>
        @endif
    </div>

    {{-- Summary stats --}}
    @if($movements->count() > 0)
        <div class="summary">
            <div class="summary-card total">
                <div class="label">Total Pergerakan</div>
                <div class="value">{{ $stats['total'] }}</div>
            </div>
            <div class="summary-card masuk">
                <div class="label">Produk Masuk</div>
                <div class="value">{{ $stats['masuk'] }}</div>
            </div>
            <div class="summary-card keluar">
                <div class="label">Produk Keluar</div>
                <div class="value">{{ $stats['keluar'] }}</div>
            </div>
        </div>
    @endif

    {{-- Footer --}}
    <div class="footer">
        {{ config('app.name') }} &mdash; Laporan digenerate otomatis. Halaman ini bersifat rahasia.
    </div>

</body>
</html>


