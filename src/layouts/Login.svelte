<script>
	import { http } from '@/lib';

	let req = {
		username: '',
		password: '',
	};
	let errors = [];
	let is = {
		submit: false,
	};
	let success = '';

	function login() {
		is.submit = true;
		errors = [];
		http
			.post('/login', req)
			.then((res) => {
				success = 'berhasil login';
				localStorage.setItem('id', res.data.id);
				localStorage.setItem('jabatan', res.data.jabatan);
        
				setTimeout(() => {
					window.location.href = window.location.origin + window.location.pathname;
				}, 1000);
			})
			.catch((res) => {
				errors = res.response.data;
			})
			.finally(() => {
				is.submit = false;
			});
	}
</script>

<div class="min-h-screen bg-primary flex items-center justify-center p-4">
	<form
		on:submit|preventDefault={login}
		class="bg-white lg:p-8 p-4 rounded shadow w-[550px] max-w-full"
	>
		<div class="title !mb-8">Login</div>

		{#if success}
			<div class="alert" color="blue">{success}</div>
		{/if}

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

		<span class="mb-1">Username</span>
		<input
			type="text"
			class="input"
			placeholder="Masukkan Username"
			required
			bind:value={req.username}
		/>

		<span class="mb-1">Password</span>
		<input
			type="password"
			class="input"
			placeholder="********"
			required
			bind:value={req.password}
		/>

		<div class="mt-8">
			<button type="submit" class="btn !w-full" color="blue">MASUK</button>
		</div>
	</form>
</div>
