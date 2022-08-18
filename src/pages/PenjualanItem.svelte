<script context="module" lang="ts">
	import type { Produk } from './Produk.svelte';

	export interface PenjualanItem {
		id?: number;
		penjualan_id?: string;
		barang_id?: number | any;
		harga_beli?: number;
		harga_jual_awal?: number;
		harga_jual_akhir?: number;
		diskon?: number;
		jumlah?: number;
		sub_total?: number;
		barang?: Produk;
	}
</script>

<script lang="ts">
	import { auth, http, notif } from '@/lib';
	import { createEventDispatcher, onMount } from 'svelte';
	import Modal from '@/components/Modal.svelte';
	import AutoComplete from '@/components/AutoComplete.svelte';
	import Table from '@/components/Table.svelte';

	const dispatch = createEventDispatcher();

	export let penjualan_id;
	export let pelanggan_diskon = 0;
	export let edit = true;

	let data: PenjualanItem[] = [];
	let barang: Produk[] = [];
	let req: PenjualanItem = {
		diskon: 0,
		harga_beli: 0,
		harga_jual_awal: 0,
		harga_jual_akhir: 0,
		sub_total: 0,
	};
	let errors: String[] = [];
	let modal = {
		save: false,
		delete: false,
	};
	let is = {
		edit: false,
		loading: false,
		submit: false,
	};
	let maxStok = 0;

	function nullable() {
		is.edit = false;
		req = {
			penjualan_id: penjualan_id,
			barang_id: '',
			harga_beli: 0,
			harga_jual_awal: 0,
			harga_jual_akhir: 0,
			sub_total: 0,
			diskon: pelanggan_diskon,
		};
	}

	function get(change = false) {
		nullable();
		is.loading = true;

		if (change) {
			dispatch('change');
		}

		http
			.get('/penjualan/item', {
				params: {
					penjualan_id,
				},
			})
			.then((res) => {
				is.loading = false;
				data = res.data;
			});
	}

	function getBarang() {
		http.get('/barang').then((res) => {
			barang = res.data;
		});
	}

	function store() {
		is.submit = true;
		errors = [];
		http
			.post('/penjualan/item', req)
			.then((res) => {
				notif('item berhasil ditambah');
				modal.save = false;
				get(true);
			})
			.catch((res) => {
				errors = res.response.data;
			})
			.finally(() => {
				is.submit = false;
			});
	}

	function getBarangHarga() {
		barang.forEach((item) => {
			if (item.id == req.barang_id) {
				req.harga_beli = item.harga_beli;
				req.harga_jual_awal = item.harga_jual;
				if (!req.jumlah) {
					req.jumlah = 1;
				}
				maxStok = item.stok;
			}
		});
		getHargaSatuanAkhir();
	}

	function getHargaSatuanAkhir() {
		req.harga_jual_akhir = req.harga_jual_awal - req.harga_jual_awal * (req.diskon / 100);
		getSubTotal();
	}

	function getSubTotal() {
		req.sub_total = req.harga_jual_akhir * req.jumlah;
	}

	function update() {
		is.submit = true;
		errors = [];
		http
			.put('/penjualan/item/' + req.id, req)
			.then((res) => {
				notif('item berhasil diedit');
				modal.save = false;
				get(true);
			})
			.catch((res) => {
				errors = res.response.data;
			})
			.finally(() => {
				is.submit = false;
			});
	}

	function destroy() {
		is.submit = true;
		errors = [];
		http
			.delete('/penjualan/item/' + req.id)
			.then((res) => {
				notif('item berhasil dihapus');
				modal.delete = false;
				get(true);
			})
			.catch((res) => {
				errors = res.response.data;
			})
			.finally(() => {
				is.submit = false;
			});
	}

	onMount(async () => {
		await get();
		await getBarang();
	});
</script>

<div class="card">
	<div class="title">Detail Item</div>

	{#if edit}
		<div class="pb-4">
			<button
				type="button"
				class="btn"
				color="blue"
				on:click={() => {
					nullable();
					modal.save = true;
				}}
			>
				<i class="fa fa-plus" />
				Tambah
			</button>
		</div>
	{/if}

	<Table
		items={data}
		values={edit
			? [
					{
						header: 'Produk',
						key: 'barang',
						render: (res) => res.nama,
					},
					{
						header: 'Harga Jual Awal',
						key: 'harga_jual_awal',
						render: (res) => 'Rp.' + res.toLocaleString('id-ID'),
					},
					{
						header: 'Diskon',
						key: 'diskon',
						render: (res) => res + '%',
					},
					{
						header: 'Harga Jual Akhir',
						key: 'harga_jual_akhir',
						render: (res) => 'Rp.' + res.toLocaleString('id-ID'),
					},
					{
						header: 'Jumlah',
						key: 'jumlah',
					},
					{
						header: 'Sub Total',
						key: 'sub_total',
						render: (res) => 'Rp.' + res.toLocaleString('id-ID'),
					},
					{
						header: 'Opsi',
						key: 'opsi',
					},
			  ]
			: [
					{
						header: 'Produk',
						key: 'barang',
						render: (res) => res.nama,
					},
					{
						header: 'Harga Jual Awal',
						key: 'harga_jual_awal',
						render: (res) => 'Rp.' + res.toLocaleString('id-ID'),
					},
					{
						header: 'Diskon',
						key: 'diskon',
						render: (res) => res + '%',
					},
					{
						header: 'Harga Jual Akhir',
						key: 'harga_jual_akhir',
						render: (res) => 'Rp.' + res.toLocaleString('id-ID'),
					},
					{
						header: 'Jumlah',
						key: 'jumlah',
					},
					{
						header: 'Sub Total',
						render: (res) => 'Rp.' + res.toLocaleString('id-ID'),
						key: 'sub_total',
					},
			  ]}
		bind:loading={is.loading}
		let:item
		let:key
	>
		{#if key == 'opsi'}
			<td>
				<button
					on:click={() => {
						req = item;
						modal.save = true;
						is.edit = true;
						getBarangHarga();
					}}
					type="button"
					class="text-blue-500 mr-2"><i class="fa fa-pen" /></button
				>
				<button
					on:click={() => {
						req = item;
						modal.delete = true;
					}}
					type="button"
					class="text-red-500"><i class="fa fa-trash" /></button
				>
			</td>
		{/if}
	</Table>
</div>

<Modal bind:open={modal.save}>
	<form on:submit|preventDefault={is.edit ? update : store}>
		<div class="title">{is.edit ? 'Edit' : 'Tambah'} Item</div>

		{#if is.submit}
			<div class="alert" color="blue">Memuat...</div>
		{/if}

		{#if errors.length}
			<div class="alert" color="red">
				{#each errors as item}
					<li>{item}</li>
				{/each}
			</div>
		{/if}

		<span class="mb-1">Produk</span>
		<AutoComplete
			items={barang.map((item) => ({
				value: item.id,
				text: item.nama,
				foto: item.foto_url,
			}))}
			bind:value={req.barang_id}
			placeholder="Pilih Produk"
			required
			let:item
			on:change={getBarangHarga}
		>
			<div class="flex items-center h-[50px]">
				<img src={item.foto} alt="" class="w-[50px] h-full object-cover rounded" />
				<div class="pl-4 flex-1 overflow-hidden overflow-ellipsis whitespace-nowrap">
					{item.text}
				</div>
			</div>
		</AutoComplete>

		<span class="mb-1">Harga Jual</span>
		<input
			type="text"
			class="input"
			placeholder="Harga Jual"
			disabled
			value={'Rp.' + req.harga_jual_awal.toLocaleString('id-ID')}
		/>

		<!-- <div class="flex -mx-2">
			<div class="w-1/2 px-2">
				<span class="mb-1">Harga Beli</span>
				<input
					type="text"
					class="input"
					placeholder="Harga Beli"
					disabled
					value={'Rp.' + req.harga_beli.toLocaleString('id-ID')}
				/>
			</div>
			<div class="w-1/2 px-2">
				<span class="mb-1">Harga Jual</span>
				<input
					type="text"
					class="input"
					placeholder="Harga Jual"
					disabled
					value={'Rp.' + req.harga_jual_awal.toLocaleString('id-ID')}
				/>
			</div>
		</div> -->

		<span class="mb-1">Diskon</span>
		<input
			type="number"
			class="input"
			placeholder="Masukkan Diskon"
			min="0"
			max="100"
			bind:value={req.diskon}
			on:input={getHargaSatuanAkhir}
			disabled={auth.jabatan == 'Manager' ? false : true}
		/>

		<span class="mb-1">Harga Satuan Akhir</span>
		<input
			type="text"
			class="input"
			disabled
			placeholder="Harga Satuan Akhir"
			value={'Rp.' + req.harga_jual_akhir.toLocaleString('id-ID')}
		/>

		<span class="mb-1">Jumlah</span>
		<input
			type="number"
			class="input"
			placeholder="Masukkan Jumlah"
			min="1"
			required
			max={maxStok}
			bind:value={req.jumlah}
			on:keyup={getSubTotal}
		/>

		<span class="mb-1">Sub Total</span>
		<input
			type="text"
			class="input"
			disabled
			placeholder="Sub Total"
			value={'Rp.' + req.sub_total.toLocaleString('id-ID')}
		/>

		<div class="mt-4 text-right">
			<button type="submit" class="btn" color="blue">Simpan</button>
		</div>
	</form>
</Modal>

<Modal bind:open={modal.delete}>
	<form on:submit|preventDefault={destroy}>
		<div class="title">Hapus Item</div>

		{#if is.submit}
			<div class="alert" color="blue">Memuat...</div>
		{/if}

		<div>Anda yakin menghapus item terpilih?</div>

		<div class="mt-4 text-right">
			<button type="submit" class="btn" color="red">Hapus</button>
		</div>
	</form>
</Modal>
