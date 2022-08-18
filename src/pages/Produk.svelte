<script context="module" lang="ts">
	export interface Produk {
		id?: number;
		nama?: string;
		kategori?: string;
		stok?: number;
		harga_jual?: number;
		harga_jual_detail?: string;
		harga_beli?: number;
		harga_beli_detail?: string;
		foto?: string;
		foto_url?: string;
	}
</script>

<script lang="ts">
	import Modal from '@/components/Modal.svelte';
	import Pagination from '@/components/Pagination.svelte';
	import Required from '@/components/Required.svelte';

	import { http, notif, readFile } from '@/lib';
	import { onMount } from 'svelte';

	let data: Produk[] = [];
	let kategori: Array<{
		nama: string;
	}> = [];
	let req: Produk = {};
	let is = {
		edit: false,
		loading: false,
		submit: false,
	};
	let errors: String[] = [];
	let totalCount = 0;
	let modal = {
		save: false,
		delete: false,
	};
	let filter = {
		_limit: 10,
		_page: 1,
		q: '',
	};

	function nullable() {
		is.edit = false;
		req = {
			kategori: '',
		};
	}

	function get() {
		is.loading = true;
		http
			.get('/barang', {
				params: filter,
			})
			.then((res) => {
				is.loading = false;
				totalCount = parseInt(res.headers['x-total-count']);
				data = res.data;
			});
	}

	function store() {
		document.querySelectorAll('.modal').forEach((el) => el.scrollTo(0, 0));
		is.submit = true;
		errors = [];
		http
			.post('/barang', req)
			.then((res) => {
				notif('data berhasil disimpan');
				modal.save = false;
				get();
			})
			.catch((res) => {
				errors = res.response.data;
			})
			.finally(() => {
				is.submit = false;
			});
	}

	function update() {
		document.querySelectorAll('.modal').forEach((el) => el.scrollTo(0, 0));
		is.submit = true;
		errors = [];
		http
			.put('/barang/' + req.id, req)
			.then((res) => {
				notif('data berhasil disimpan');
				modal.save = false;
				get();
			})
			.catch((res) => {
				errors = res.response.data;
			})
			.finally(() => {
				is.submit = false;
			});
	}

	function destroy() {
		document.querySelectorAll('.modal').forEach((el) => el.scrollTo(0, 0));
		is.submit = true;
		errors = [];
		http
			.delete('/barang/' + req.id)
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

	function getKategori() {
		http.get('/kategori').then((res) => {
			kategori = res.data;
		});
	}

	onMount(async () => {
		await get();
		getKategori();
	});
</script>

<div class="card">
	<div class="title">Data Produk</div>
	<div class="pb-4">
		<button
			class="btn mr-2 mb-2"
			color="blue"
			on:click={() => {
				nullable();
				modal.save = true;
			}}
		>
			<i class="fa fa-plus" />
			Tambah
		</button>
		<select
			class="input mr-2 !w-auto"
			bind:value={filter._limit}
			on:change={() => {
				filter._page = 1;
				get();
			}}
		>
			<option value={10}>10</option>
			<option value={20}>20</option>
			<option value={50}>50</option>
			<option value={100}>100</option>
		</select>
		<input
			type="text"
			class="input !lg:w-1/2"
			placeholder="Cari..."
			bind:value={filter.q}
			on:change={() => {
				filter._page = 1;
				get();
			}}
		/>
	</div>
	<div class="flex flex-wrap -mx-3">
		{#if is.loading}
			{#each Array(4) as item}
				<div class="lg:w-1/5 w-1/2 p-3">
					<div class="bg-white rounded shadow relative">
						<div class="absolute top-0 left-0 right-0 flex items-center justify-center">
							<div
								class="rounded-b shadow text-xs bg-white transform -translate-y-1 font-semibold px-3 py-0.5 w-16 h-5"
							/>
						</div>
						<div class="lg:h-[200px] h-[150px] skeleton rounded-t" />
						<div class="p-4 text-center">
							<div class="rounded-full h-3 skeleton" />
							<div class="rounded-full h-2 mt-3 skeleton inline-block w-[50%]" />
							<div class="mt-5">
								<div class="rounded-full h-2 skeleton inline-block w-[20%]" />
							</div>
						</div>
					</div>
				</div>
			{/each}
		{:else}
			{#each data as item}
				<div class="lg:w-1/5 w-1/2 p-3">
					<div class="bg-white rounded shadow relative">
						<div class="absolute top-0 left-0 right-0 flex items-center justify-center">
							<div
								class="rounded-b shadow text-xs bg-white transform -translate-y-1 font-semibold px-3 py-0.5"
							>
								{item.kategori}
							</div>
						</div>
						<img
							src={item.foto_url}
							alt=""
							class="lg:h-[200px] h-[150px] rounded-t object-cover w-full"
						/>
						<div class="p-4 text-center">
							<div
								class="text-base font-semibold w-full overflow-ellipsis overflow-hidden whitespace-nowrap"
							>
								{item.nama}
							</div>
							<div class="text-sm">{item.harga_jual_detail}</div>

							<div class="mt-4">
								<button
									type="button"
									class="text-blue-500 mr-2"
									on:click={() => {
										is.edit = true;
										modal.save = true;
										req = item;
										req.foto = '';
									}}
								>
									<i class="fa fa-pen" />
								</button>
								<button
									class="text-red-500"
									type="button"
									on:click={() => {
										modal.delete = true;
										req = item;
									}}
								>
									<i class="fa fa-trash" />
								</button>
							</div>
						</div>
					</div>
				</div>
			{:else}
				<div class="w-full p-2">
					<div class="card">
						<div class="text-center">data tidak tersedia</div>
					</div>
				</div>
			{/each}
		{/if}
	</div>
	<div class="mt-5 flex justify-between items-center">
		<div class="flex-1">Menampilkan {data.length} dari {totalCount} data.</div>
		<Pagination
			on:change={get}
			bind:limit={filter._limit}
			bind:page={filter._page}
			bind:totalCount
		/>
	</div>
</div>

<Modal bind:open={modal.save}>
	<form on:submit|preventDefault={is.edit ? update : store}>
		<div class="title">{is.edit ? 'Edit' : 'Tambah'} Produk</div>

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

		<span class="mb-1">Nama Barang <Required /></span>
		<input
			type="text"
			class="input"
			required
			placeholder="Masukkan Nama Barang"
			bind:value={req.nama}
		/>

		<span class="mb-1">Kategori <Required /></span>
		<select bind:value={req.kategori} required class="input">
			<option value="" disabled>Pilih Kategori</option>
			{#each kategori as item}
				<option value={item.nama}>{item.nama}</option>
			{/each}
		</select>

		<span class="mb-1">Stok <Required /></span>
		<input
			type="number"
			min="0"
			class="input"
			required
			placeholder="Masukkan Stok"
			bind:value={req.stok}
		/>

		<div class="flex -mx-2">
			<div class="p-2 w-1/2">
				<span class="mb-1">Harga Beli <Required /></span>
				<input
					type="number"
					class="input"
					required
					placeholder="Harga Beli"
					min="0"
					bind:value={req.harga_beli}
				/>
			</div>
			<div class="p-2 w-1/2">
				<span class="mb-1">Harga Jual <Required /></span>
				<input
					type="number"
					class="input"
					required
					placeholder="Harga Jual"
					min="0"
					bind:value={req.harga_jual}
				/>
			</div>
		</div>

		<span class="mb-1">Foto Pratinjau</span>
		<input
			type="file"
			class="input"
			title="Pilih Foto Pratinjau"
			on:change={(e) =>
				readFile(e.currentTarget.files[0], (res) => {
					req.foto = res;
					req.foto_url = res;
				})}
			accept="image/*"
		/>

		<div class="mb-3 bg-gray-50 rounded p-4 text-center">
			<img src={req.foto_url} alt="" class="h-[130px] inline max-w-full object-cover" />
		</div>

		<div class="mt-5 text-right">
			<button type="submit" class="btn" color="blue">Simpan</button>
		</div>
	</form>
</Modal>

<Modal bind:open={modal.delete}>
	<form on:submit|preventDefault={destroy}>
		<div class="title">Hapus Produk</div>

		{#if is.submit}
			<div class="alert" color="blue">Memuat...</div>
		{/if}

		<div>Anda yakin menghapus produk <strong>{req.nama}</strong>?</div>

		<div class="mt-4 text-right">
			<button type="submit" class="btn" color="red">Hapus</button>
		</div>
	</form>
</Modal>
