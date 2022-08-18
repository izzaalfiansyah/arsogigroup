<script lang="ts">
	import DatePicker from '@/components/DatePicker.svelte';

	import { http } from '@/lib';
	import { onMount } from 'svelte';
	import type { Penjualan } from './Penjualan.svelte';
	import type { Produk } from './Produk.svelte';

	let filter = {
		_tanggal: '',
	};
	let data: Penjualan[] = [];
	let produk: Produk[] = [];
	let url = {
		print: '',
	};

	function get() {
		http
			.get('penjualan/laporan', {
				params: filter,
			})
			.then((res) => {
				data = res.data;
				url.print = res.headers['x-print-url'];
			});
	}

	function getProduk() {
		http.get('/barang').then((res) => {
			produk = res.data;
		});
	}

	$: if (filter) {
		get();
	}

	onMount(() => {
		getProduk();
	});
</script>

<div class="card">
	<div class="title">Laporan</div>

	<div class="flex flex-wrap -mx-3">
		<div class="lg:w-1/2 w-full px-3">
			<DatePicker placeholder="Pilih Tanggal" bind:value={filter._tanggal} />
		</div>
		<div class="lg:w-1/2 w-full px-3 lg:text-right mb-2">
			<a
				target="_blank"
				href={url.print}
				disabled={url.print ? false : true}
				class="btn !inline-block p-2"
				color="red">PRINT</a
			>
		</div>
	</div>

	<div class="overflow-x-auto">
		<table class="w-full whitespace-nowrap">
			<thead>
				<tr>
					<td rowspan="2" class="p-3 px-4 text-center font-semibold text-sm border border-gray-100"
						>NO</td
					>
					<td rowspan="2" class="p-3 px-4 text-center font-semibold text-sm border border-gray-100"
						>ID PELANGGAN</td
					>
					<td rowspan="2" class="p-3 px-4 text-center font-semibold text-sm border border-gray-100"
						>NAMA</td
					>
					<td rowspan="2" class="p-3 px-4 text-center font-semibold text-sm border border-gray-100"
						>ALAMAT</td
					>
					<td rowspan="2" class="p-3 px-4 text-center font-semibold text-sm border border-gray-100"
						>HP</td
					>
					<td colspan="2" class="p-3 px-4 text-center font-semibold text-sm border border-gray-100"
						>PINJAMAN</td
					>
					<td
						colspan={produk.length + 1}
						class="p-3 px-4 text-center font-semibold text-sm border border-gray-100">TAKE ORDER</td
					>
					<td rowspan="2" class="p-3 px-4 text-center font-semibold text-sm border border-gray-100"
						>KETERANGAN</td
					>
				</tr>
				<tr>
					<td class="p-3 px-4 text-center font-semibold text-sm border border-gray-100">BOTOL</td>
					<td class="p-3 px-4 text-center font-semibold text-sm border border-gray-100">KRAT</td>
					{#each produk as item}
						<td class="p-3 px-4 text-center font-semibold text-sm border border-gray-100 uppercase"
							>{item.nama}</td
						>
					{/each}

					<td class="p-3 px-4 text-center font-semibold text-sm border border-gray-100"
						>SUB TOTAL</td
					>
				</tr>
				<tr class="h-3" />
			</thead>
			<tbody>
				{#each data as item, i}
					<tr class="border border-gray-100 transition hover:bg-gray-50">
						<td class="p-4 text-center">{i + 1}</td>
						<td class="p-4 text-center">{item.pelanggan.id}</td>
						<td class="p-4">{item.pelanggan.nama_pemilik} ({item.pelanggan.nama_toko})</td>
						<td class="p-4">{item.pelanggan.alamat}</td>
						<td class="p-4"
							>HP1:{item.pelanggan.hp1}, HP2:{item.pelanggan.hp2 ? item.pelanggan.hp2 : '-'}</td
						>
						<td class="p-4 text-center"
							>{item.pelanggan.total_pinjaman_botol ? item.pelanggan.total_pinjaman_botol : 0}</td
						>
						<td class="p-4 text-center"
							>{item.pelanggan.total_pinjaman_krat ? item.pelanggan.total_pinjaman_krat : 0}</td
						>
						{#each produk as p}
							{@const itemProduk = item.item.filter((i) => i.barang_id == p.id)}
							<td class="p-4 text-center">{itemProduk[0] ? itemProduk[0].jumlah : 0}</td>
						{/each}
						<td class="p-4">Rp.{parseInt(item.subtotal).toLocaleString('id-ID')}</td>
						<td class="p-4">-</td>
					</tr>
					<tr class="h-3" />
				{:else}
					<tr class="border border-gray-100">
						<td colspan="99" class="text-center p-4"
							>{filter._tanggal ? 'Data tidak tersedia' : 'Pilih Tanggal Terlebih dahulu'}.</td
						>
					</tr>
					<tr class="h-3" />
				{/each}
			</tbody>
		</table>
		<tbody />
	</div>
</div>
