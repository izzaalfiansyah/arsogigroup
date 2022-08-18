<script lang="ts">
import Required from '@/components/Required.svelte';

	import { auth, http, notif, readFile } from '@/lib';
	import { onMount } from 'svelte';

	import type { User } from './User.svelte';

	let req: User = {};
	let errors = [];
	let is = {
		submit: false,
	};

	function get() {
		is.submit = true;
		http.get('/user/' + auth.id).then((res) => {
			is.submit = false;
			req = res.data;
			req.password = '';
			req.foto_ktp = '';
		});
	}

	function update() {
		window.scrollTo(0, 0);
		is.submit = true;
		http
			.put('/user/' + auth.id, req)
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
		await get();
	});
</script>

<form on:submit|preventDefault={update} class="flex flex-wrap">
	<div class="w-full">
		<div class="card">
			<div class="title">Profil</div>

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
				<div class="px-2 lg:w-1/2 w-full">
					<span class="mb-1">Username <Required/></span>
					<input
						type="text"
						class="input"
						bind:value={req.username}
						placeholder="Masukkan Username"
						required
					/>
				</div>
				<div class="px-2 lg:w-1/2 w-full">
					<span class="mb-1">Password</span>
					<input
						type="password"
						class="input"
						bind:value={req.password}
						placeholder="********"
					/>
					<div class="-mt-2 text-xs text-gray-500">Kosongkan jika tidak ingin mengganti password</div>
				</div>
			</div>

			<span class="mb-1">Nama <Required/></span>
			<input type="text" class="input" bind:value={req.nama} placeholder="Masukkan Nama" required />
			
			<span class="mb-1">Alamat KTP</span>
			<textarea
				type="text"
				class="input"
				bind:value={req.alamat_ktp}
				placeholder="Masukkan Alamat KTP"
				rows="3"
			></textarea>

			<span class="mb-1">Alamat Domisili</span>
			<textarea
				type="text"
				class="input"
				bind:value={req.alamat_domisili}
				placeholder="Masukkan Alamat Domisili"
				rows="3"
			></textarea>

			<div class="flex -mx-2">
				<div class="px-2 w-1/2">
					<span class="mb-1">HP 1 <Required/></span>
					<input
						type="tel"
						class="input"
						bind:value={req.hp1}
						placeholder="08xxxxxxxxxx"
						required
					/>
				</div>
				<div class="px-2 w-1/2">
					<span class="mb-1">HP 2</span>
					<input type="tel" class="input" bind:value={req.hp2} placeholder="08xxxxxxxxxx" />
				</div>
			</div>

			<span class="mb-1">Jabatan</span>
			<input type="text" disabled class="input" value={req.jabatan}>

			<span class="mb-1">Foto KTP</span>
			<input
				type="file"
				class="input"
				title="Pilih Foto KTP"
				on:change={(e) =>
					readFile(e.currentTarget.files[0], (res) => {
						req.foto_ktp = res;
						req.foto_ktp_url = res;
					})}
				accept="image/*"
			/>
			<div class="-mt-2 text-xs text-gray-500">Kosongkan jika tidak ingin mengganti foto</div>

			<div class="mb-4 rounded bg-gray-50 text-center p-4 mt-1">
				<img src={req.foto_ktp_url} alt="" class="h-[150px] inline-block" />
			</div>

			<div class="mt-4 text-right">
				<button class="btn" color="blue">Simpan</button>
			</div>
		</div>
	</div>
</form>
