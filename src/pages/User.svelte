<script context="module" lang="ts">
	export interface User {
		id?: number;
		username?: string;
		password?: string;
		nama?: string;
		alamat_ktp?: string;
		alamat_domisili?: string;
		hp1?: string;
		hp2?: string;
		jabatan?: string;
		foto_ktp?: string;
		foto_ktp_url?: string;
	}
</script>

<script lang="ts">
	import Modal from '@/components/Modal.svelte';
	import Pagination from '@/components/Pagination.svelte';
	import Required from '@/components/Required.svelte';
	import Table from '@/components/Table.svelte';

	import { http, notif, readFile, numeric } from '@/lib';
	import { onMount } from 'svelte';

	let data: User[] = [];
	let jabatan: Array<{
		nama: string;
	}> = [];
	let errors: string[] = [];
	let req: User = {};
	let totalCount: any = 0;
	let is = {
		loading: false,
		submit: false,
		edit: false,
	};
	let modal = {
		save: false,
		delete: false,
	};
	let filter = {
		q: '',
		_limit: 10,
		_page: 1,
	};

	function nullable() {
		errors = [];
		req = {
			jabatan: '',
		};
	}

	function get() {
		is.loading = true;
		http
			.get('/user', {
				params: filter,
			})
			.then((res) => {
				totalCount = parseInt(res.headers['x-total-count']);
				is.loading = false;
				data = res.data;
			});
	}

	function store() {
		document.querySelectorAll('.modal').forEach((item) => item.scrollTo(0, 0));
		is.submit = true;
		http
			.post('/user', req)
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
		document.querySelectorAll('.modal').forEach((item) => item.scrollTo(0, 0));
		is.submit = true;
		http
			.put('/user/' + req.id, req)
			.then((res) => {
				notif('data berhasil diedit');
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
		document.querySelectorAll('.modal').forEach((item) => item.scrollTo(0, 0));
		is.submit = true;
		http
			.delete('/user/' + req.id)
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

	function getComponent() {
		http.get('/jabatan').then((res) => (jabatan = res.data));
	}

	onMount(async () => {
		getComponent();
		await get();
	});
</script>

<div class="card">
	<div class="title">Data User</div>
	<div class="pb-4">
		<button
			class="btn mr-2 mb-2"
			color="blue"
			on:click={() => {
				nullable();
				is.edit = false;
				modal.save = true;
			}}
		>
			<i class="fa fa-plus" />
			Tambah
		</button>
		<input
			type="text"
			bind:value={filter.q}
			on:change={() => {
				filter._page = 1;
				get();
			}}
			class="input !lg:w-1/2"
			placeholder="Cari..."
		/>
	</div>
	<Table
		items={data}
		values={[
			{
				header: 'Username',
				key: 'username',
			},
			{
				header: 'Nama',
				key: 'nama',
			},
			{
				header: 'Jabatan',
				key: 'jabatan',
			},
			{
				header: 'Telepon',
				key: 'telepon',
			},
			{
				header: 'Opsi',
				key: 'opsi',
			},
		]}
		bind:loading={is.loading}
		let:item
		let:key
	>
		{#if key == 'telepon'}
			HP 1 : {item.hp1} <br />
			HP 2 : {item.hp2}
		{/if}

		{#if key == 'opsi'}
			<button
				on:click={() => {
					is.edit = true;
					modal.save = true;
					req = item;
					req.password = '';
					req.foto_ktp = '';
				}}
				class="text-blue-500 mr-2"><i class="fa fa-pen" /></button
			>
			<button
				on:click={() => {
					modal.delete = true;
					req = item;
				}}
				class="text-red-500"><i class="fa fa-trash" /></button
			>
		{/if}
	</Table>
	<div class="flex justify-between mt-3 items-center">
		<div class="flex-1">
			Menampilkan {data.length} dari {totalCount} data.
		</div>
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
		<div class="title">{is.edit ? 'Edit' : 'Tambah'} User</div>

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

		<div class="flex -mx-2">
			<div class="px-2 w-1/2">
				<span class="mb-1">Username <Required /></span>
				<input
					type="text"
					class="input"
					bind:value={req.username}
					placeholder="Masukkan Username"
					required
				/>
			</div>
			<div class="px-2 w-1/2">
				<span class="mb-1">
					Password
					{#if !is.edit}
						<Required />
					{/if}
				</span>
				<input
					type="password"
					class="input"
					bind:value={req.password}
					placeholder="********"
					required={!is.edit}
				/>
				{#if is.edit}
					<div class="-mt-2 text-xs text-gray-500">Optional (boleh tidak diisi)</div>
				{/if}
			</div>
		</div>

		<span class="mb-1">Nama <Required /></span>
		<input type="text" class="input" bind:value={req.nama} placeholder="Masukkan Nama" required />

		<span class="mb-1">Alamat KTP</span>
		<input
			type="text"
			class="input"
			bind:value={req.alamat_ktp}
			placeholder="Masukkan Alamat KTP"
		/>

		<span class="mb-1">Alamat Domisili</span>
		<input
			type="text"
			class="input"
			bind:value={req.alamat_domisili}
			placeholder="Masukkan Alamat Domisili"
		/>

		<div class="flex -mx-2">
			<div class="px-2 w-1/2">
				<span class="mb-1">HP 1 <Required /></span>
				<input
					type="tel"
					class="input"
					bind:value={req.hp1}
					use:numeric
					placeholder="08xxxxxxxxxx"
					required
				/>
			</div>
			<div class="px-2 w-1/2">
				<span class="mb-1">HP 2</span>
				<input
					type="tel"
					class="input"
					bind:value={req.hp2}
					use:numeric
					placeholder="08xxxxxxxxxx"
				/>
			</div>
		</div>
		<span class="mb-1">Jabatan <Required /></span>
		<select bind:value={req.jabatan} class="input" required>
			<option value="" disabled>Pilih Jabatan</option>
			{#each jabatan as item}
				<option value={item.nama}>{item.nama}</option>
			{/each}
		</select>
		<span class="mb-1">Foto KTP <Required /></span>
		<input
			type="file"
			class="input"
			title="Pilih Foto KTP"
			required={!is.edit}
			on:change={(e) =>
				readFile(e.currentTarget.files[0], (res) => {
					req.foto_ktp = res;
					req.foto_ktp_url = res;
				})}
			accept="image/*"
		/>
		{#if is.edit}
			<div class="-mt-2 text-xs text-gray-500">Kosongkan jika tidak ingin mengganti foto</div>
		{/if}

		<div class="mb-4 rounded bg-gray-50 text-center p-4">
			<img src={req.foto_ktp_url} alt="" class="h-[150px] inline-block" />
		</div>

		<div class="text-right mt-5">
			<button type="submit" class="btn" color="blue">Simpan</button>
		</div>
	</form>
</Modal>

<Modal bind:open={modal.delete}>
	<form on:submit={destroy}>
		<div class="title">Hapus User</div>

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

		<div>Anda yakin akan menghapus user <strong>{req.username}</strong>?</div>

		<div class="text-right mt-5">
			<button type="submit" class="btn" color="red">Hapus</button>
		</div>
	</form>
</Modal>
