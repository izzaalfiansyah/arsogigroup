<script context="module" lang="ts">
	import type { Pelanggan } from './Pelanggan.svelte';

	export interface Penjualan {
		id?: string;
		pelanggan_id?: number;
		nama_sales?: string;
		nama_delivery?: string;
		status?: string;
		status_awal?: string;
		status_penjualan?: string;
		total_penjualan?: number;
		pelanggan?: Pelanggan;
		print_url?: string;
		subtotal?: any;
		item?: any[];
	}
</script>

<script lang="ts">
	import Modal from '@/components/Modal.svelte';
	import Pagination from '@/components/Pagination.svelte';
	import { auth, en, http, notif } from '@/lib';
	import { onMount } from 'svelte';
	import { link, location } from 'svelte-spa-router';
	import type { User } from './User.svelte';
	import AutoComplete from '@/components/AutoComplete.svelte';
	import DatePicker from '@/components/DatePicker.svelte';
	import Table from '@/components/Table.svelte';

	export let title = 'Penjualan';
	export let status = '3';
	export let prefix = 'PJ';

	let data: Penjualan[] = [];
	let delivery: User[] = [];
	let req: Penjualan = {};
	let totalCount = 0;
	let is = {
		loading: false,
		submit: false,
	};
	let modal = {
		agree: false,
		delete: false,
	};
	let filter = {
		_limit: 10,
		_page: 1,
		q: '',
		status: '3',
		status_awal: status !== '3' ? status : '',
		status_filter: '',
		sales_id: '',
		tanggal: '',
	};

	function get() {
		is.loading = true;

		if (auth.jabatan !== 'Manager') {
			filter.sales_id = auth.id;
		}

		http
			.get('/penjualan', {
				params: filter,
			})
			.then((res) => {
				data = res.data;
				totalCount = parseInt(res.headers['x-total-count']);
				is.loading = false;
			});
	}

	function destroy() {
		is.submit = true;
		http
			.delete('/penjualan/' + req.id)
			.then((res) => {
				notif('data berhasil dihapus');
				modal.delete = false;
				get();
			})
			.finally(() => {
				is.submit = false;
			});
	}

	function update() {
		is.submit = true;
		http
			.put('/penjualan/' + req.id, req)
			.then((res) => {
				notif('status berhasil diedit');
				modal.agree = false;
				get();
			})
			.finally(() => {
				is.submit = false;
			});
	}

	function getDelivery() {
		http
			.get('/user', {
				params: {
					jabatan: 'delivery',
				},
			})
			.then((res) => (delivery = res.data));
	}

	onMount(async () => {
		await get();
		await getDelivery();
	});
</script>

<div class="card">
	<div class="title">{title}</div>

	<div class="pb-4">
		<a href="{$location}/create" use:link class="btn mr-2 mb-2 py-2 inline-block" color="blue">
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
			<option value={1}>1</option>
			<option value={10}>10</option>
			<option value={20}>20</option>
			<option value={50}>50</option>
			<option value={100}>100</option>
		</select>
		<DatePicker
			class="!w-auto mr-2"
			placeholder="Pilih Tanggal"
			bind:value={filter.tanggal}
			on:change={() => {
				filter._page = 1;
				get();
			}}
		/>
		{#if status !== '3'}
			<select
				class="input !w-auto"
				bind:value={filter.status_filter}
				on:change={() => {
					filter._page = 1;
					get();
				}}
			>
				<option value="">Semua Status</option>
				<option value="-1">Menunggu</option>
				<option value="1">Disetujui</option>
			</select>
		{/if}
		<input
			type="text"
			class="input {status !== '3' ? '' : '!lg:w-1/2'}"
			placeholder="Cari..."
			bind:value={filter.q}
			on:change={() => {
				filter._page = 1;
				get();
			}}
		/>
	</div>

	<Table
		items={data}
		bind:loading={is.loading}
		values={status == '3'
			? [
					{
						header: 'ID',
						key: 'id',
						render: (res) => prefix + res,
					},
					{
						header: 'Pembeli',
						key: 'pelanggan',
						render: (res) => res.nama_pemilik + '(' + res.nama_toko + ')',
					},
					{
						header: 'Sales',
						key: 'nama_sales',
					},
					{
						header: 'Delivery',
						key: 'nama_delivery',
					},
					{
						header: 'Status',
						key: 'status_penjualan',
					},
					{
						header: 'Total',
						key: 'total_penjualan',
						render: (res) => 'Rp.' + res.toLocaleString('id-ID'),
					},
					{
						header: 'Opsi',
						key: 'opsi',
					},
			  ]
			: [
					{
						header: 'ID',
						key: 'id',
						render: (res) => prefix + res,
					},
					{
						header: 'Pembeli',
						key: 'pelanggan',
						render: (res) => res.nama_pemilik + '(' + res.nama_toko + ')',
					},
					{
						header: 'Sales',
						key: 'nama_sales',
					},
					{
						header: 'Status',
						key: 'status_penjualan',
					},
					{
						header: 'Total',
						key: 'total_penjualan',
						render: (res) => 'Rp.' + res.toLocaleString('id-ID'),
					},
					{
						header: 'Persetujuan',
						key: 'persetujuan',
					},
					{
						header: 'Opsi',
						key: 'opsi',
					},
			  ]}
		let:item
		let:key
	>
		{#if key == 'persetujuan'}
			{#if item.status == '3'}
				<div
					class="inline-block bg-green-500 rounded px-3 py-1 text-white text-xs uppercase font-semibold shadow transition outline-none focus:bg-red-600"
				>
					Disetujui
				</div>
			{:else if auth.jabatan == 'Manager'}
				<button
					on:click={() => {
						modal.agree = true;
						req.id = item.id;
						req.pelanggan_id = item.pelanggan_id;
						req.nama_delivery = item.nama_delivery;
						req.nama_sales = item.nama_sales;
						req.status_penjualan = item.status_penjualan;
						req.status = '3';
					}}
					class="bg-blue-500 rounded px-3 py-1 text-white text-xs uppercase font-semibold shadow hover:bg-blue-400 transition outline-none focus:bg-blue-600"
					>Menunggu</button
				>
			{:else}
				<div
					class="inline-block bg-yellow-500 rounded px-3 py-1 text-white text-xs uppercase font-semibold shadow transition outline-none focus:bg-red-600"
				>
					Menunggu
				</div>
			{/if}
		{/if}

		{#if key == 'opsi'}
			{#if item.status == '3'}
				{#if status == '3'}
					<a
						href={item.print_url}
						target="_blank"
						class="inline-block bg-red-500 rounded px-3 py-1 text-white text-xs uppercase mr-1 font-semibold shadow hover:bg-red-400 transition outline-none focus:bg-red-600"
						>Print</a
					>
					<a
						use:link
						href="/penjualan/{item.id}/detail"
						class="inline-block bg-green-500 rounded px-3 py-1 text-white text-xs uppercase font-semibold shadow hover:bg-green-400 transition outline-none focus:bg-green-600"
						><i class="fa fa-search" /></a
					>
				{:else}
					-
				{/if}
			{:else}
				<a href="{$location}/{item.id}" class="text-blue-500 mr-2" use:link
					><i class="fa fa-pen" /></a
				>
				<button class="text-red-500" type="button"
					><i
						class="fa fa-trash"
						on:click={() => {
							req = item;
							modal.delete = true;
						}}
					/></button
				>
			{/if}
		{/if}
	</Table>

	<div class="flex justify-between items-center mt-3">
		<div class="flex-1">
			Menampilkan {data.length} dari {totalCount} data.
		</div>
		<div>
			<Pagination
				bind:page={filter._page}
				bind:totalCount
				bind:limit={filter._limit}
				on:change={get}
			/>
		</div>
	</div>
</div>

<Modal bind:open={modal.delete}>
	<form on:submit|preventDefault={destroy}>
		<div class="title">Hapus Pesanan</div>

		{#if is.submit}
			<div class="alert" color="blue">Memuat...</div>
		{/if}

		<div>
			Anda yakin akan menghapus data {title.toLowerCase()} dengan ID
			<strong>{prefix}{req.id}</strong>?
		</div>

		<div class="mt-4 text-right">
			<button type="submit" class="btn" color="red">Hapus</button>
		</div>
	</form>
</Modal>

<Modal bind:open={modal.agree}>
	<form on:submit|preventDefault={update}>
		<div class="title">Setujui Transaksi</div>

		{#if is.submit}
			<div class="alert" color="blue">Memuat...</div>
		{/if}

		<div class="mb-4">
			<span class="mb-1">Delivery:</span>
			<AutoComplete
				placeholder="Pilih Delivery"
				required
				items={delivery.map((item) => ({
					value: item.nama,
					text: item.nama,
				}))}
				bind:value={req.nama_delivery}
			/>
		</div>

		<div>
			Status dari transaksi dengan ID <strong>{prefix}{req.id}</strong> akan diubah ke penjualan
		</div>

		<div class="mt-4 text-right">
			<button type="submit" class="btn" color="blue">Konfirmasi</button>
		</div>
	</form>
</Modal>
