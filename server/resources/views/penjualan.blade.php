<table border=1 width=100% cellpadding=3 cellspacing=0>
    <tr>
        <th rowspan="2">NO</th>
        <th rowspan="2">ID PEL</th>
        <th rowspan="2">NAMA</th>
        <th rowspan="2">ALAMAT</th>
        <th rowspan="2">HP</th>
        <th colspan="2">PINJAMAN</th>
        <th colspan="{{ count($produk) + 1 }}">TAKE ORDER</th>
        <th rowspan="2">KETERANGAN</th>
    </tr>
    <tr>
        <th>BOTOL</th>
        <th>KRAT</th>
        @foreach ($produk as $item)
            <th>{{ strtoupper($item->nama) }}</th>
        @endforeach
        <th>SUBTOTAL</th>
    </tr>
    @foreach ($data as $item)
        <tr>
            <td align="center">{{ $loop->iteration }}</td>
            <td align="center">{{ $item->pelanggan->id }}</td>
            <td>{{ $item->pelanggan->nama_pemilik }} ({{ $item->pelanggan->nama_toko }})</td>
            <td>{{ $item->pelanggan->alamat }}</td>
            <td>HP1:{{ $item->pelanggan->hp1 }}, HP2:{{ $item->pelanggan->hp2 }}</td>
            <td align="center">{{ $item->pelanggan->total_pinjaman_botol ?: 0 }}</td>
            <td align="center">{{ $item->pelanggan->total_pinjaman_krat ?: 0 }}</td>
            @foreach ($produk as $p)
                @php
                    $itemProduk = array_filter(
                        $item->item->toArray(),
                        function ($i) use ($p) {
                            return $i->barang_id == $p->id;
                        },
                        0,
                    );
                    $itemProduk = array_values($itemProduk);
                @endphp

                <td align="center">{{ $itemProduk ? @$itemProduk[0]->jumlah : 0 }}</td>
            @endforeach
            <td>Rp.{{ number_format($item->subtotal, 0, ',', '.') }}</td>
            <td>-</td>
        </tr>
    @endforeach
</table>

<script>
    window.print();
    setTimeout(window.close, 3000);
</script>
