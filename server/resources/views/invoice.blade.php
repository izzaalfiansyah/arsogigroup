@php
function tanggal($date)
{
    $tanggal = date('d', strtotime($date));
    $tanggal .= ' ';
    $tanggal .= [1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'][(int) date('m', strtotime($date))];
    $tanggal .= ' ';
    $tanggal .= date('Y', strtotime($date));

    return $tanggal;
}

function formatRupiah($number)
{
    return 'Rp.' . number_format($number, 0, ',', '.');
}
@endphp

<style>
    @page {
        size: 8.5in 5.5in;
        padding: 0;
        margin: 0;
    }
</style>

<div style="font-size: 10px; height: 50vh; position: relative; height: calc(28cm/2); padding: 20px;">
    <div>
        <div style="margin-bottom: 15px;">
            <div style="font-weight: bold; font-size: 15px;">
                <div>CV. PUTRA BAWEAN</div>
                <div>ARSOGI GROUP <span style="font-weight: normal; font-style: italic;">Temulawak dan Coffe Beer</span></div>
            </div>
            Jl. Kepodang No. 4 bintoro-Patrang-Jember Hp. 081336007799 / 081224411311
        </div>
    
        <div>
            <div style="font-style: italic; text-align: center; font-size: 12px;">
                <u>
                    INVOICE PENJUALAN
                </u>
            </div>
        
            <div style="display: flex; flex-direction: row;">
                <div style="padding: 10px 0px; width: 50%;">
                    <table style="font-size: 10px;" cellpadding="0">
                        <tr>
                            <td style="vertical-align: top">ID TAKE ORDER</td>
                            <td style="vertical-align: top; width: 8px;">:</td>
                            <td style="vertical-align: top">TO{{ $penjualan->id }}</td>
                        </tr>
                        <tr>
                            <td style="vertical-align: top">TGL TAKE ORDER</td>
                            <td style="vertical-align: top; width: 8px;">:</td>
                            <td style="vertical-align: top">{{ tanggal($penjualan->created_at) }}</td>
                        </tr>
                        <tr>
                            <td style="vertical-align: top">NAMA PELANGGAN</td>
                            <td style="vertical-align: top; width: 8px;">:</td>
                            <td style="vertical-align: top">{{ $penjualan->pelanggan->id }}/{{ $penjualan->pelanggan->nama_pemilik }}</td>
                        </tr>
                        <tr>
                            <td style="vertical-align: top">NAMA TOKO</td>
                            <td style="vertical-align: top; width: 8px;">:</td>
                            <td style="vertical-align: top">{{ $penjualan->pelanggan->nama_toko }}</td>
                        </tr>
                        <tr>
                            <td style="vertical-align: top">ALAMAT</td>
                            <td style="vertical-align: top; width: 8px;">:</td>
                            <td class="align-top w-12">{{ $penjualan->pelanggan->alamat }} -
                                {{ $penjualan->pelanggan->kelurahan->name }} - {{ $penjualan->pelanggan->kecamatan->name }}
                            </td>
                        </tr>
                        <tr>
                            <td style="vertical-align: top">NO HP.</td>
                            <td style="vertical-align: top; width: 8px;">:</td>
                            <td style="vertical-align: top">
                                {{ $penjualan->pelanggan->hp1 . ($penjualan->pelanggan->hp2 ? '/' . $penjualan->pelanggan->hp2 : '') }}
                            </td>
                        </tr>
                    </table>
                </div>
                <div style="padding: 10px 0px; width: 50%;">
                    <table style="font-size: 10px;" cellpadding="0">
                        <tr>
                            <td style="vertical-align: top">ID PENJUALAN</td>
                            <td style="vertical-align: top; width: 8px;">:</td>
                            <td style="vertical-align: top">PJ{{ $penjualan->id }}</td>
                        </tr>
                        <tr>
                            <td style="vertical-align: top">TGL PENJUALAN</td>
                            <td style="vertical-align: top; width: 8px;">:</td>
                            <td style="vertical-align: top">{{ tanggal($penjualan->updated_at) }}</td>
                        </tr>
                        <tr>
                            <td style="vertical-align: top">NAMA SALES</td>
                            <td style="vertical-align: top; width: 8px;">:</td>
                            <td style="vertical-align: top">{{ $penjualan->nama_sales }}</td>
                        </tr>
                        <tr>
                            <td style="vertical-align: top">NAMA DELIVERY</td>
                            <td style="vertical-align: top; width: 8px;">:</td>
                            <td style="vertical-align: top">{{ $penjualan->nama_delivery }}</td>
                        </tr>
                        <tr>
                            <td style="vertical-align: top">STATUS PENJUALAN</td>
                            <td style="vertical-align: top; width: 8px;">:</td>
                            <td style="vertical-align: top">{{ $penjualan->status_penjualan == 'TTP' ? 'TTP/14 Hari' : $penjualan->status_penjualan }}</td>
                        </tr>
                        <tr>
                            <td style="vertical-align: top">TOTAL PINJAMAN BOTOL</td>
                            <td style="vertical-align: top; width: 8px;">:</td>
                            <td style="vertical-align: top">{{ $penjualan->pelanggan->total_pinjaman_botol }}</td>
                        </tr>
                        <tr>
                            <td style="vertical-align: top">TOTAL PINJAMAN KRAT</td>
                            <td style="vertical-align: top; width: 8px;">:</td>
                            <td style="vertical-align: top">{{ $penjualan->pelanggan->total_pinjaman_krat }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        <table style="width: 100%; border-collapse: collapse; font-size: 10px;" border="1" cellpadding="2">
            <tr>
                <td>NO</td>
                <td>NAMA PRODUK</td>
                <td>HARGA JUAL AWAL</td>
                <td>DISKON</td>
                <td>JUMLAH</td>
                <td>HARGA JUAL AKHIR</td>
                <td>SUBTOTAL</td>
            </tr>
            @foreach ($items as $item)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $item->barang->nama }}</td>
                    <td>{{ formatRupiah($item->harga_jual_awal) }}</td>
                    <td>{{ $item->diskon }}</td>
                    <td>{{ $item->jumlah }}</td>
                    <td>{{ formatRupiah($item->harga_jual_akhir) }}</td>
                    <td>{{ formatRupiah($item->sub_total) }}</td>
                </tr>
            @endforeach
            <tr>
                <td colspan="5"></td>
                <td>TOTAL</td>
                <td>{{ formatRupiah($penjualan->total_penjualan) }}</td>
            </tr>
        </table>
    </div>
    <div style="margin-top: 20px; display: flex; align-items: stretch; justify-content: space-between;">
        <div style="height: 70px; width: 30%; text-align: center; display: flex; justify-content: space-between; flex-direction: column;">
            <div>Dicek dan diterima<br />dalam keadaan baik oleh</div>
            <div>{{ $penjualan->pelanggan->nama_pemilik }}</div>
        </div>
        <div style="height: 70px; width: 30%; text-align: center; display: flex; justify-content: space-between; flex-direction: column;">
            <div><br />Hormat Kami</div>
            <div>Iin Mutmainah</div>
        </div>
    </div>
    <div style="position: absolute; bottom: 0; left: 0; padding: 20px;">
        {{ $penjualan->pelanggan->keterangan_alamat }}
    </div>
</div>

<script>
    window.print();
    setTimeout(() => {
        window.close();
    }, 3000);
</script>