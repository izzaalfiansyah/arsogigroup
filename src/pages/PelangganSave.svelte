<script lang="ts">
	import Modal from '@/components/Modal.svelte';
	import axios from 'axios';
	import { auth, http, notif, readFile, numeric } from '@/lib';
	import { onMount } from 'svelte';
	import { link, push } from 'svelte-spa-router';
	import type { Pelanggan } from './Pelanggan.svelte';
	import Map from './Map.svelte';
	import Required from '@/components/Required.svelte';
	import type { User } from './User.svelte';
	import AutoComplete from '@/components/AutoComplete.svelte';

	type Items = {
		id: string;
		name: string;
	};

	export let params: {
		id?: string;
	} = {};

	// Khusus Jember
	let req: Pelanggan = {
		provinsi: JSON.stringify({
			id: 35,
			name: 'JAWA TIMUR',
		}).trim(),
		kabupaten: JSON.stringify({
			id: 3509,
			province_id: 35,
			name: 'KABUPATEN JEMBER',
		}).trim(),
	};
	$: provinsi = JSON.parse(req.provinsi as string).name;
	$: kabupaten = JSON.parse(req.kabupaten as string).name;
	// Seluruh Indonesia
	// let req: Pelanggan = {}

	let sales: User[] = [];
	let daerah = {
		provinsi: <Items[]>[],
		kabupaten: <Items[]>[],
		kecamatan: <Items[]>[],
		kelurahan: <Items[]>[],
	};
	let is = {
		submit: false,
		searchLocation: false,
	};
	let modal = {
		map: false,
	};
	let mapValue = null;
	let errors: String[] = [];

	function getProvinsi() {
		axios
			.get('http://www.emsifa.com/api-wilayah-indonesia/api/provinces.json')
			.then((res) => (daerah.provinsi = res.data));
	}

	function getKabupaten() {
		daerah.kabupaten = daerah.kecamatan = daerah.kelurahan = [];
		const provinsi = JSON.parse(req.provinsi as string);
		axios
			.get(`http://www.emsifa.com/api-wilayah-indonesia/api/regencies/${provinsi.id}.json`)
			.then((res) => (daerah.kabupaten = res.data));
	}

	function getKecamatan() {
		daerah.kecamatan = daerah.kelurahan = [];
		const kabupaten = JSON.parse(req.kabupaten as string);
		axios
			.get(`http://www.emsifa.com/api-wilayah-indonesia/api/districts/${kabupaten.id}.json`)
			.then((res) => (daerah.kecamatan = res.data));
	}

	function getKelurahan() {
		daerah.kelurahan = [];
		const kecamatan = JSON.parse(req.kecamatan as string);
		axios
			.get(`http://www.emsifa.com/api-wilayah-indonesia/api/villages/${kecamatan.id}.json`)
			.then((res) => (daerah.kelurahan = res.data));
	}

	function get() {
		errors = [];

		http.get('/pelanggan/' + params.id).then((res) => {
			req = res.data;

			req.provinsi = JSON.stringify(req.provinsi);
			req.kabupaten = JSON.stringify(req.kabupaten);
			req.kecamatan = JSON.stringify(req.kecamatan);
			req.kelurahan = JSON.stringify(req.kelurahan);
			req.foto_toko = '';
			mapValue = [req.latitude, req.longitude];

			getKabupaten();
			getKecamatan();
			getKelurahan();
		});
	}

	function getSales() {
		http
			.get('/user', {
				params: {
					jabatan: 'sales',
				},
			})
			.then((res) => (sales = res.data));
	}

	function getReq() {
		const data = req;
		data.provinsi = JSON.parse(data.provinsi as string);
		data.kabupaten = JSON.parse(data.kabupaten as string);
		data.kecamatan = JSON.parse(data.kecamatan as string);
		data.kelurahan = JSON.parse(data.kelurahan as string);

		return data;
	}

	function store() {
		is.submit = true;
		window.scrollTo(0, 0);
		http
			.post('/pelanggan', getReq())
			.then((res) => {
				notif('data berhasil disimpan');
				push('/pelanggan/' + res.data.id);
			})
			.catch((res) => {
				errors = res.response.data;
			})
			.finally(() => {
				is.submit = false;
			});
	}

	function update() {
		is.submit = true;
		window.scrollTo(0, 0);
		http
			.put('/pelanggan/' + params.id, getReq())
			.then((res) => {
				notif('data berhasil disimpan');
				get();
			})
			.catch((res) => {
				errors = res.response.data;
			})
			.finally(() => {
				is.submit = false;
			});
	}

	onMount(async () => {
		if (params.id) {
			get();
		}

		if (auth.jabatan !== 'Manager') {
			req.sales_id = parseInt(auth.id);
		}

		// Khusus Jember
		await getKecamatan();
		// Seluruh Indonesia
		// await getProvinsi();

		await getSales();
	});
</script>

<form on:submit|preventDefault={params.id ? update : store} class="card">
	<div class="title">{params.id ? 'Edit' : 'Tambah'} Pelanggan</div>

	{#if errors.length}
		<div class="alert" color="red">
			{#each errors as item}
				<li>{item}</li>
			{/each}
		</div>
	{/if}

	{#if is.submit}
		<div class="alert" color="blue">Memuat...</div>
	{/if}

	<div class="flex flex-wrap -mx-2">
		<div class="lg:w-1/2 w-full px-2">
			<span class="mb-1">Nama Toko <Required /></span>
			<input
				type="text"
				class="input"
				bind:value={req.nama_toko}
				placeholder="Masukkan Nama Toko"
				required
			/>
			<span class="mb-1">Nama Pemilik <Required /></span>
			<input
				type="text"
				class="input"
				bind:value={req.nama_pemilik}
				placeholder="Masukkan Nama Pemilik"
				required
			/>
			<div class="flex -mx-2">
				<div class="w-1/2 px-2">
					<span class="mb-1">HP 1 <Required /></span>
					<input
						type="text"
						class="input"
						bind:value={req.hp1}
						use:numeric
						placeholder="08xxxxxxxxxx"
						required
					/>
				</div>
				<div class="w-1/2 px-2">
					<span class="mb-1">HP 2</span>
					<input
						type="text"
						class="input"
						bind:value={req.hp2}
						use:numeric
						placeholder="08xxxxxxxxxx"
					/>
				</div>
			</div>
			<span class="mb-1">Foto Toko</span>
			<input
				type="file"
				class="input"
				title="Pilih Foto Toko"
				on:change={(e) =>
					readFile(e.currentTarget.files[0], (res) => {
						req.foto_toko = res;
						req.foto_toko_url = res;
					})}
			/>
			<div class="mb-3 bg-gray-50 p-4 text-center">
				<img
					src={req.foto_toko_url}
					alt=""
					class="h-[250px] max-w-full object-cover inline-block"
				/>
			</div>
			<span class="mb-1">Lokasi Toko</span>
			<input
				type="text"
				class="input"
				value="{req.latitude}-{req.longitude}"
				on:click={() => (modal.map = true)}
				placeholder="Pilih Lokasi Toko"
				readonly
			/>
			<span class="mb-1">Sales <Required /></span>
			<AutoComplete
				items={sales.map((item) => ({
					value: item.id,
					text: item.nama,
				}))}
				bind:value={req.sales_id}
				placeholder="Pilih Sales"
				disabled={auth.jabatan == 'Manager' ? false : true}
				required
			/>
		</div>
		<div class="lg:w-1/2 w-full px-2">
			<!-- Khusus Jember -->
			<div class="mb-1">Provinsi</div>
			<input type="text" class="input" disabled value={provinsi} />
			<div class="mb-1">Kabupaten</div>
			<input type="text" class="input" disabled value={kabupaten} />

			<!-- Seluruh Indonesia -->
			<!-- <span class="mb-1">Provinsi <Required /></span>
			<select required bind:value={req.provinsi} on:change={getKabupaten} class="input">
				<option value="">Pilih Provinsi</option>
				{#each daerah.provinsi as item}
					<option value={JSON.stringify(item)}>{item.name}</option>
				{/each}
			</select>
			{#if daerah.kabupaten.length}
				<span class="mb-1">Kabupaten <Required /></span>
				<select required bind:value={req.kabupaten} on:change={getKecamatan} class="input">
					<option value="">Pilih Kabupaten</option>
					{#each daerah.kabupaten as item}
						<option value={JSON.stringify(item)}>{item.name}</option>
					{/each}
				</select>
			{/if} -->

			{#if daerah.kecamatan.length}
				<span class="mb-1">Kecamatan <Required /></span>
				<AutoComplete
					items={daerah.kecamatan.map((item) => ({
						value: JSON.stringify(item),
						text: item.name,
					}))}
					bind:value={req.kecamatan}
					on:change={getKelurahan}
					required
					placeholder="Pilih Kecamatan"
				/>
			{/if}

			{#if daerah.kelurahan.length && req.kecamatan}
				<span class="mb-1">Kelurahan <Required /></span>
				<AutoComplete
					items={daerah.kelurahan.map((item) => ({
						value: JSON.stringify(item),
						text: item.name,
					}))}
					bind:value={req.kelurahan}
					required
					placeholder="Pilih Kecamatan"
				/>
			{/if}

			<span class="mb-1">Alamat <Required /></span>
			<input
				type="text"
				class="input"
				bind:value={req.alamat}
				placeholder="Masukkan Alamat"
				required
			/>

			<span class="mb-1">Keterangan Alamat</span>
			<textarea
				rows="3"
				bind:value={req.keterangan_alamat}
				class="input"
				placeholder="Keterangan Alamat"
			/>

			<span class="mb-1">Diskon Produk</span>
			<input
				type="number"
				class="input"
				bind:value={req.diskon_produk}
				placeholder="Masukkan Jumlah Diskon Produk"
				min="0"
				max="100"
			/>

			<span class="mb-1">Total Pinjaman Botol</span>
			<input
				type="number"
				class="input"
				bind:value={req.total_pinjaman_botol}
				placeholder="Masukkan Total Pinjaman Botol"
				min="0"
			/>

			<span class="mb-1">Total Pinjaman Krat</span>
			<input
				type="number"
				class="input"
				bind:value={req.total_pinjaman_krat}
				placeholder="Masukkan Total Pinjaman Krat"
				min="0"
			/>
		</div>

		<div class="mt-5 text-right">
			<button type="submit" class="btn" color="blue">Simpan</button>
			<a href="/pelanggan" use:link class="btn py-3">Kembali</a>
		</div>
	</div>
</form>

<Modal bind:open={modal.map}>
	<div class="title">Pilih Lokasi</div>
	{#if is.searchLocation}
		<div class="alert" color="blue">Mencari lokasi terkini...</div>
	{/if}
	<Map
		on:change={(e) => {
			req.latitude = e.detail.latitude;
			req.longitude = e.detail.longitude;
		}}
		bind:search={is.searchLocation}
		bind:value={mapValue}
		bind:open={modal.map}
	/>
	<div class="mt-5 text-right">
		<button type="button" class="btn mr-1" color="blue" on:click={() => (is.searchLocation = true)}
			>Temukan Saya</button
		>
		<button
			type="button"
			class="btn"
			on:click={() => {
				modal.map = false;
			}}>Tutup</button
		>
	</div>
</Modal>
