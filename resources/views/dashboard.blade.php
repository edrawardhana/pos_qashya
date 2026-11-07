@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
@php
    $rp = fn ($amount) => 'Rp '.number_format((float) $amount, 0, ',', '.');
    $today = now()->locale('id');
    $firstName = explode(' ', trim(auth()->user()->name))[0];
    $trend = collect($salesTrend ?? [])->values();
    $max = max((float) $trend->max(fn ($day) => data_get($day, 'total', 0)), 1);
    $trendCount = max($trend->count(), 1);
    $hasTrend = $trend->sum(fn ($day) => data_get($day, 'total', 0)) > 0;
    $lowStock = collect($lowStockProducts ?? []);
    $recent = collect($recentTransactions ?? []);
@endphp

<style>
    .qd { --qd-line:#e5e7eb; --qd-ink:#0f172a; --qd-mute:#64748b; --qd-soft:#f1f5f9; max-width:1100px; margin:0 auto; color:var(--qd-ink); }
    .qd * { box-sizing:border-box; }
    .qd-mono { font-family:ui-monospace,SFMono-Regular,Menlo,Consolas,monospace; }
    .qd-date { font-size:12px; color:var(--qd-mute); margin:0 0 4px; }
    .qd-title { font-size:32px; font-weight:700; line-height:1.15; margin:0 0 4px; }
    .qd-sub { color:var(--qd-mute); font-size:14px; margin:0 0 24px; }
    .qd-card { background:#fff; border:1px solid var(--qd-line); border-radius:8px; }
    .qd-card-head { display:flex; justify-content:space-between; align-items:flex-start; gap:12px; padding:14px 16px; border-bottom:1px solid var(--qd-line); }
    .qd-card-head h2 { font-size:13px; font-weight:600; margin:0; }
    .qd-card-head p { font-size:12px; color:var(--qd-mute); margin:2px 0 0; }
    .qd-card-head a { font-size:12px; color:var(--qd-ink); text-decoration:none; white-space:nowrap; }
    .qd-card-head a:hover { text-decoration:underline; }
    .qd-stats { display:grid; grid-template-columns:repeat(4,1fr); gap:16px; margin-bottom:20px; }
    .qd-stat { padding:14px 16px; }
    .qd-stat-top { display:flex; justify-content:space-between; align-items:center; font-size:12px; color:var(--qd-mute); margin-bottom:10px; }
    .qd-stat-icon { width:26px; height:26px; border:1px solid var(--qd-line); border-radius:6px; display:grid; place-items:center; }
    .qd-stat-icon svg { width:14px; height:14px; }
    .qd-stat strong { display:block; font-size:24px; font-weight:700; line-height:1.2; }
    .qd-stat small { display:block; margin-top:4px; font-size:12px; color:var(--qd-mute); }
    .qd-stat small a { color:inherit; }
    .qd-grid { display:grid; grid-template-columns:minmax(0,2fr) minmax(0,1fr); gap:16px; margin-bottom:16px; align-items:start; }
    .qd-col { display:grid; gap:16px; }
    .qd-chart { padding:12px 16px 16px; }
    .qd-chart svg { width:100%; height:auto; display:block; }
    .qd-empty { padding:28px 16px; text-align:center; font-size:13px; color:var(--qd-mute); }
    .qd-list { list-style:none; margin:0; padding:0; }
    .qd-list li { display:flex; justify-content:space-between; align-items:center; gap:12px; padding:11px 16px; border-bottom:1px solid var(--qd-line); }
    .qd-list li:last-child { border-bottom:0; }
    .qd-item { display:flex; align-items:center; gap:10px; min-width:0; }
    .qd-item-icon { flex:none; width:28px; height:28px; border:1px solid var(--qd-line); border-radius:6px; display:grid; place-items:center; color:var(--qd-mute); }
    .qd-item-icon svg { width:14px; height:14px; }
    .qd-item b { display:block; font-size:13px; font-weight:600; }
    .qd-item span, .qd-right span { display:block; font-size:11px; color:var(--qd-mute); }
    .qd-right { text-align:right; flex:none; }
    .qd-right b { display:block; font-size:13px; font-weight:600; }
    .qd-badge { font-size:11px; padding:2px 8px; border-radius:4px; background:var(--qd-soft); border:1px solid var(--qd-line); }
    .qd-badge-out { background:var(--qd-ink); color:#fff; border-color:var(--qd-ink); }
    .qd-quick { padding:16px; }
    .qd-quick a { display:block; padding:12px; border:1px solid var(--qd-line); border-radius:6px; font-size:12px; text-decoration:none; color:var(--qd-ink); }
    .qd-quick a:hover, .qd-quick a:focus-visible { background:var(--qd-soft); outline:none; }
    .qd-quick svg { width:15px; height:15px; display:block; margin-bottom:10px; }
    @media (max-width:1000px) { .qd-stats { grid-template-columns:repeat(2,1fr); } .qd-grid { grid-template-columns:1fr; } }
    @media (max-width:520px) { .qd-stats { grid-template-columns:1fr; } .qd-title { font-size:26px; } }
</style>

<div class="qd">
    <p class="qd-date qd-mono">{{ $today->translatedFormat('l, d F Y') }}</p>
    <h1 class="qd-title">Halo, {{ $firstName }}</h1>
    <p class="qd-sub">{{ $isAdmin ? 'Ringkasan penjualan dan kondisi toko hari ini.' : 'Pantau transaksi dan stok toko.' }}</p>

    @if ($isAdmin)
        <div class="qd-stats">
            <div class="qd-card qd-stat">
                <div class="qd-stat-top"><span class="qd-mono">Penjualan hari ini</span><span class="qd-stat-icon">Rp</span></div>
                <strong>{{ $rp($salesToday) }}</strong>
                <small>Total penjualan seluruh kasir</small>
            </div>
            <div class="qd-card qd-stat">
                <div class="qd-stat-top"><span class="qd-mono">Transaksi hari ini</span><span class="qd-stat-icon">#</span></div>
                <strong>{{ $transactionsToday }}</strong>
                <small>Seluruh kasir</small>
            </div>
            <div class="qd-card qd-stat">
                <div class="qd-stat-top"><span class="qd-mono">Item terjual</span><span class="qd-stat-icon">PCS</span></div>
                <strong>{{ $itemsSoldToday }}</strong>
                <small>Hari ini</small>
            </div>
            <div class="qd-card qd-stat">
                <div class="qd-stat-top"><span class="qd-mono">Perlu restok</span><span class="qd-stat-icon">!</span></div>
                <strong>{{ $lowStockCount }}</strong>
                <small><a href="{{ route('stock.index') }}">Lihat manajemen stok</a></small>
            </div>
        </div>

        <div class="qd-grid">
            <section class="qd-card">
                <div class="qd-card-head">
                    <div><h2>Tren penjualan 7 hari</h2><p>Total penjualan per hari</p></div>
                    <a href="{{ route('reports.index') }}">Laporan lengkap →</a>
                </div>
                @if ($hasTrend)
                    <div class="qd-chart">
                        <svg viewBox="0 0 600 230" role="img" aria-label="Grafik penjualan 7 hari terakhir">
                            @foreach ([0, 1, 2, 3] as $gridLine)
                                <line x1="10" x2="590" y1="{{ 20 + $gridLine * 52 }}" y2="{{ 20 + $gridLine * 52 }}" stroke="#e5e7eb" stroke-width="1"/>
                            @endforeach
                            <line x1="10" x2="590" y1="176" y2="176" stroke="#cbd5e1" stroke-width="1"/>
                            @foreach ($trend as $i => $day)
                                @php
                                    $slot = 580 / $trendCount;
                                    $barWidth = $slot * 0.5;
                                    $x = 10 + $i * $slot + ($slot - $barWidth) / 2;
                                    $height = 156 * (float) data_get($day, 'total', 0) / $max;
                                @endphp
                                <rect x="{{ $x }}" y="{{ 176 - $height }}" width="{{ $barWidth }}" height="{{ $height }}" rx="3" fill="#0f172a">
                                    <title>{{ data_get($day, 'label') }}: {{ $rp(data_get($day, 'total', 0)) }}</title>
                                </rect>
                                <text x="{{ $x + $barWidth / 2 }}" y="200" text-anchor="middle" font-size="11" fill="#64748b">{{ data_get($day, 'label') }}</text>
                            @endforeach
                        </svg>
                    </div>
                @else
                    <div class="qd-empty">Belum ada penjualan dalam 7 hari terakhir.</div>
                @endif
            </section>

            <section class="qd-card">
                <div class="qd-card-head">
                    <div><h2>Peringatan stok</h2><p>Produk pada atau di bawah stok minimum</p></div>
                </div>
                <ul class="qd-list">
                    @forelse ($lowStock as $product)
                        <li>
                            <div class="qd-item">
                                <span class="qd-item-icon">!</span>
                                <div><b>{{ $product->name }}</b><span class="qd-mono">Sisa {{ $product->stock }} · min. {{ $product->min_stock }}</span></div>
                            </div>
                            <span class="qd-badge {{ $product->stock === 0 ? 'qd-badge-out' : '' }}">{{ $product->stock === 0 ? 'Habis' : 'Menipis' }}</span>
                        </li>
                    @empty
                        <li><div class="qd-empty" style="width:100%">Semua stok masih aman.</div></li>
                    @endforelse
                </ul>
            </section>
        </div>
    @else
        <div class="qd-grid">
            <section class="qd-card">
                <div class="qd-card-head">
                    <h2>Transaksi terbaru</h2>
                    <a href="{{ route('history.index') }}">Semua riwayat →</a>
                </div>
                <ul class="qd-list">
                    @forelse ($recent as $transaction)
                        <li>
                            <div class="qd-item"><div>
                                <b class="qd-mono">{{ $transaction->code }}</b>
                                <span>{{ $transaction->created_at->locale('id')->translatedFormat('d M Y, H:i') }} · {{ $transaction->items_count }} item</span>
                            </div></div>
                            <div class="qd-right">
                                <b class="qd-mono">{{ $rp($transaction->total) }}</b>
                                <span class="qd-mono">{{ ucfirst($transaction->payment_method) }}</span>
                            </div>
                        </li>
                    @empty
                        <li><div class="qd-empty" style="width:100%">Belum ada transaksi.</div></li>
                    @endforelse
                </ul>
            </section>

            <div class="qd-col">
                <section class="qd-card">
                    <div class="qd-card-head">
                        <div><h2>Peringatan stok</h2><p>Produk pada atau di bawah stok minimum</p></div>
                        <a href="{{ route('products.index') }}">Lihat produk →</a>
                    </div>
                    <ul class="qd-list">
                        @forelse ($lowStock as $product)
                            <li>
                                <div class="qd-item">
                                    <span class="qd-item-icon">!</span>
                                    <div><b>{{ $product->name }}</b><span class="qd-mono">Sisa {{ $product->stock }} · min. {{ $product->min_stock }}</span></div>
                                </div>
                                <span class="qd-badge {{ $product->stock === 0 ? 'qd-badge-out' : '' }}">{{ $product->stock === 0 ? 'Habis' : 'Menipis' }}</span>
                            </li>
                        @empty
                            <li><div class="qd-empty" style="width:100%">Semua stok masih aman.</div></li>
                        @endforelse
                    </ul>
                </section>

                <section class="qd-card">
                    <div class="qd-card-head"><h2>Akses cepat</h2></div>
                    <div class="qd-quick">
                        <a href="{{ route('cashier.index') }}">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 12h16M12 4v16"/></svg>
                            Mulai Transaksi
                        </a>
                    </div>
                </section>
            </div>
        </div>
    @endif
</div>
@endsection
