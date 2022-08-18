<script lang="ts">
	import AutoComplete from '@/components/AutoComplete.svelte';

	import { auth, http, notif } from '@/lib';
	import { onMount } from 'svelte';
	import { push, pop, location } from 'svelte-spa-router';
	import type { Pelanggan } from './Pelanggan.svelte';
	import type { Penjualan } from './Penjualan.svelte';
	import PenjualanItem from './PenjualanItem.svelte';
	import type { User } from './User.svelte';

	export let title = 'Penjualan';
	export let status = '3';
	export let prefix = 'PJ';

	export let params: {
		id?: string;
	} = {};

	let req: Penjualan = {
		status: status,
		status_awal: (params.id && status == '3') ? '' : status,
		total_penjualan: 0,
		pelanggan: {
			diskon_produk: 0,
		},
	};
	let pelanggan: Pelanggan[] = [];
	let delivery: User[] = [];
	let is = {
		submit: false,
	};
	let errors: String[] = [];

	function get() {
		is.submit = true;
		http.get('/penjualan/' + params.id).then((res) => {
			req = res.data;
			is.submit = false;
		});
	}

	function getPelanggan() {
		const params = {
			sales_id: '',
		};

		if (auth.jabatan !== 'Manager') {
			params.sales_id = auth.id;
		}

		http
			.get('/pelanggan', {
				params: params,
			})
			.then((res) => (pelanggan = res.data));
	}

	function getSales() {
		pelanggan.forEach((item) => {
			if (item.id == req.pelanggan_id) {
				req.nama_sales = item.sales.nama;
				req.pelanggan = item;
			}
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

	function store() {
		is.submit = true;
		window.scrollTo(0, 0);
		errors = [];
		http
			.post('/penjualan', req)
			.then((res) => {
				notif('data berhasil disimpan');
				push($location.replace('/create', '') + '/' + res.data.id);
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
		errors = [];
		http
			.put('/penjualan/' + params.id, req)
			.then((res) => {
				notif('data berhasil diedit');
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

		await getPelanggan();
		await getDelivery();
	});
</script>

<form on:submit|preventDefault={params.id ? update : store} class="card">
	<div class="title">
		<div class="flex items-center">
			<div class="flex-1">
				Detail
				{title}
			</div>
			{#if status == '3' && params.id}
				<div class="flex-1 text-right">
					<a
						href={req.print_url}
						target="_blank"
						class="py-2 inline-block btn text-base"
						color="red"
					>
						<i class="fa fa-print" /> Print
					</a>
				</div>
			{/if}
		</div>
	</div>

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

	{#if params.id}
		<span class="mb-1">ID</span>
		<input type="text" class="input" disabled value={prefix + params.id} />
	{/if}

	<span class="mb-1">Pelanggan</span>
	<AutoComplete
		placeholder="Pilih Pelanggan"
		bind:value={req.pelanggan_id}
		required
		on:change={getSales}
		items={pelanggan.map((item) => ({
			value: item.id,
			text: `${item.nama_pemilik} (${item.nama_toko})`,
			foto: item.foto_toko_url,
		}))}
		let:item
	>
		<div class="flex items-center h-[50px]">
			<img src={item.foto} alt="" class="rounded object-cover h-full w-[70px] lg:w-[100px]" />
			<div class="pl-4 overflow-hidden overflow-ellipsis flex-1 whitespace-nowrap">
				{item.text}
			</div>
		</div>
	</AutoComplete>

	<span class="mb-1">Sales</span>
	<input type="text" disabled value={req.nama_sales} class="input" />

	{#if status == '3'}
		<span class="mb-1">Delivery</span>
		<AutoComplete
			placeholder="Pilih Nama Delivery"
			bind:value={req.nama_delivery}
			required
			items={delivery.map((item) => ({
				value: item.nama,
				text: item.nama,
			}))}
		/>
	{/if}

	<span class="mb-1">Status</span>
	<select bind:value={req.status_penjualan} class="input" required>
		<option value="">Pilih Status</option>
		<option value="TTP">TTP</option>
		<option value="COD">COD</option>
	</select>

	{#if params.id}
		<span class="mb-1">Total</span>
		<input
			type="text"
			class="input"
			disabled
			value={'Rp.' + req.total_penjualan.toLocaleString('id-ID')}
		/>
	{/if}

	<div class="mt-4 text-right">
		<button class="btn" type="button" on:click={() => pop()}>Kembali</button>
	</div>
</form>

{#if params.id}
	<PenjualanItem
		on:change={get}
		penjualan_id={params.id}
    edit={false}
		bind:pelanggan_diskon={req.pelanggan.diskon_produk}
	/>
{/if}
