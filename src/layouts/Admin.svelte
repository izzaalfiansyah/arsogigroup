<script lang="ts">
	import Router, { link, location } from 'svelte-spa-router';
	import Sidebar from '@/components/Sidebar.svelte';
	import { routes } from '@/router';
	import Modal from '@/components/Modal.svelte';
	import { auth } from '@/lib';

	let modal = {
		logout: false,
	};

	function logout() {
		localStorage.removeItem('id');
		localStorage.removeItem('jabatan');

		window.location.href = window.location.origin + window.location.pathname;
	}

	if (!auth.id || !auth.jabatan) {
		logout();
	}
</script>

<div class="min-h-screen bg-gray-50 text-gray-800">
	<div class="min-h-90vh">
		<div class="flex flex-wrap">
			<Sidebar class="w-[300px]" />
			<div class="lg:w-[calc(100%-300px)] w-full">
				<div
					class="bg-primary sticky top-0 shadow z-10 h-16 shadow flex items-center justify-between text-white px-4"
				>
					<div
						class=" bg-white rounded-full p-1.5 px-4 w-[250px] overflow-hidden overflow-ellipsis whitespace-nowrap text-gray-500"
					>
						<a href="/" use:link>
							<i class="fa fa-home text-primary text-xs" />
						</a>
						{$location.replace(/\//gi, ' / ').replace(/-/g, ' ')}
					</div>
					<div>
						<button on:click={() => (modal.logout = true)} class="outline-none">
							<i class="fa fa-sign-out-alt" />
						</button>
					</div>
				</div>
				<div class="bg-primary h-[200px]" />
				<div class="p-4 py-6 -mt-[200px]">
					<Router restoreScrollState={true} {routes} />
				</div>
			</div>
		</div>
	</div>
</div>

<Modal open={modal.logout}>
	<div class="title">Logout</div>
	Anda yakin akan logout? Sesi anda akan terhapus!
	<div class="mt-4 text-right">
		<button on:click={logout} class="btn" color="red">Keluar</button>
	</div>
</Modal>
