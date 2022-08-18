<script context="module" lang="ts">
	export interface Pelanggan {
		id?: number;
		nama_toko?: string;
		nama_pemilik?: string;
		alamat?: string;
		keterangan_alamat?: string;
		kelurahan?:
			| string
			| {
					id?: string;
					name?: string;
			  };
		kecamatan?:
			| string
			| {
					id?: string;
					name?: string;
			  };
		kabupaten?:
			| string
			| {
					id?: string;
					name?: string;
			  };
		provinsi?:
			| string
			| {
					id?: string;
					name?: string;
			  };
		hp1?: string;
		hp2?: string;
		sales_id?: number;
		sales?: User;
		foto_toko?: string;
		foto_toko_url?: string;
		latitude?: string;
		longitude?: string;
		diskon_produk?: number;
		total_pinjaman_botol?: number;
		total_pinjaman_krat?: number;
	}
</script>

<script lang="ts">
	import Modal from '@/components/Modal.svelte';
	import Pagination from '@/components/Pagination.svelte';
	import Table from '@/components/Table.svelte';
	import { auth, http, notif } from '@/lib';
	import { onMount } from 'svelte';
	import { link } from 'svelte-spa-router';
	import type { User } from './User.svelte';

	let data: Pelanggan[] = [];
	let req: Pelanggan = {};
	let errors: String[] = [];
	let modal = {
		delete: false,
	};
	let totalCount = 0;
	let is = {
		submit: false,
		loading: false,
	};
	let filter = {
		_limit: 10,
		_page: 1,
		q: '',
		sales_id: '',
	};

	function nullable() {
		req = {};
		errors = [];
	}

	function get() {
		nullable();
		is.loading = true;
		if (auth.jabatan !== 'Manager') {
			filter.sales_id = auth.id;
		}
		http
			.get('/pelanggan', {
				params: filter,
			})
			.then((res) => {
				totalCount = parseInt(res.headers['x-total-count']);
				is.loading = false;
				data = res.data;
			});
	}

	function destroy() {
		is.submit = true;
		http
			.delete('/pelanggan/' + req.id)
			.then((res) => {
				notif('data berhasil dihapus');
				modal.delete = false;
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
		await get();
	});
</script>

<div class="card">
	<div class="title">Data Pelanggan</div>
	<div class="pb-4">
		<a href="/pelanggan/create" use:link class="btn mr-2 mb-2 py-2 inline-block" color="blue">
			<i class="fa fa-plus" />
			Tambah
		</a>
		<select
			bind:value={filter._limit}
			on:change={() => {
				filter._page = 1;
				get();
			}}
			class="input !w-auto mr-2"
		>
			<option value={10}>10</option>
			<option value={20}>20</option>
			<option value={50}>50</option>
			<option value={100}>100</option>
		</select>
		<input
			bind:value={filter.q}
			on:change={() => {
				filter._page = 1;
				get();
			}}
			type="text"
			class="input !lg:w-1/2"
			placeholder="Cari..."
		/>
	</div>
	<Table
		items={data}
		bind:loading={is.loading}
		values={auth.jabatan == 'Manager'
			? [
					{
						header: 'Nama Pemilik',
						key: 'nama_pemilik',
					},
					{
						header: 'Nama Toko',
						key: 'nama_toko',
					},
					{
						header: 'Telepon',
						key: 'telepon',
					},
					{
						header: 'Opsi',
						key: 'opsi',
					},
			  ]
			: [
					{
						header: 'Nama Pemilik',
						key: 'nama_pemilik',
					},
					{
						header: 'Nama Toko',
						key: 'nama_toko',
					},
					{
						header: 'Telepon',
						key: 'telepon',
					},
			  ]}
		let:item
		let:key
	>
		{#if key == 'telepon'}
			HP 1: {item.hp1} <br />
			HP 2: {item.hp2}
		{/if}

		{#if key == 'opsi'}
			<a href="/pelanggan/{item.id}" use:link class="text-blue-500 mr-2"><i class="fa fa-pen" /></a>
			<button
				on:click={() => {
					modal.delete = true;
					req = item;
				}}
				class="text-red-500"><i class="fa fa-trash" /></button
			>
		{/if}
	</Table>
	<div class="flex justify-between items-center mt-3">
		<div class="flex-1">
			Menampilkan {data.length} dari {totalCount} data.
		</div>
		<Pagination
			on:change={get}
			bind:page={filter._page}
			bind:limit={filter._limit}
			bind:totalCount
		/>
	</div>
</div>

<Modal bind:open={modal.delete}>
	<form on:submit|preventDefault={destroy}>
		<div class="title">Hapus Pelanggan</div>

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

		<div>
			Anda yakin menghapus pelanggan <strong>{req.nama_pemilik}</strong> dengan toko
			<strong>{req.nama_toko}</strong>?
		</div>
		<div class="mt-4 text-right">
			<button type="submit" class="btn" color="red">Hapus</button>
		</div>
	</form>
</Modal>
