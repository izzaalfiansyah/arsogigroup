<script lang="ts">
	import { auth } from '@/lib';
	import { location, link } from 'svelte-spa-router';

	let className = 'w-[320px]';

	export { className as class };
	export let open = false;
	export let toggleSidebar = () => {
		open = !open;
	};

	const paths = [
		{
			title: 'Dashboard',
			icon: 'home',
			path: '/',
			jabatan: ['Manager', 'Sales', 'Delivery', 'Sales Supervisor'],
		},
		{
			title: 'Data User',
			icon: 'users',
			path: '/user',
			jabatan: ['Manager'],
		},
		{
			title: 'Data Produk',
			icon: 'database',
			path: '/produk',
			jabatan: ['Manager'],
		},
		{
			title: 'Data Pelanggan',
			icon: 'user-tie',
			path: '/pelanggan',
			jabatan: ['Manager', 'Sales', 'Sales Supervisor'],
		},
		{
			title: 'Purchase Order',
			icon: 'scale-balanced',
			path: '/purchase-order',
			jabatan: ['Manager', 'Delivery'],
		},
		{
			title: 'Take Order',
			icon: 'credit-card',
			path: '/take-order',
			jabatan: ['Manager', 'Sales', 'Sales Supervisor'],
		},
		{
			title: 'Penjualan',
			icon: 'chart-pie',
			path: '/penjualan',
			jabatan: ['Manager'],
		},
		{
			title: 'Laporan',
			icon: 'building',
			path: '/laporan',
			jabatan: ['Manager', 'Sales', 'Delivery', 'Sales Supervisor'],
		},
		{
			title: 'Profil',
			icon: 'user',
			path: '/profil',
			jabatan: ['Manager', 'Sales', 'Delivery', 'Sales Supervisor'],
		},
	];
</script>

<div
	class="{className} p-4 h-screen bg-white shadow-lg fixed top-0 left-0 bottom-0 z-30 transition transform lg:translate-x-0 {open
		? 'translate-x-0'
		: '-translate-x-full'} overflow-y-auto"
>
	<div class="py-8 text-primary text-3xl border-b-3 border-gray-800 text-center font-bold">
		Arsogi
	</div>
	<div class="py-10">
		{#each paths as item}
			{#if item.jabatan.indexOf(auth.jabatan) >= 0}
				<a
					href={item.path}
					class="outline-none block w-full rounded p-4 py-2 hover:(bg-gray-200) transition font-semibold mb-1 {item.path ==
					$location
						? 'bg-primary !text-white shadow'
						: ''}"
					use:link
					on:click={toggleSidebar}
				>
					<span class="inline-block w-6 mr-2 text-center">
						<i class="fa fa-{item.icon}" />
					</span>
					{item.title}
				</a>
			{/if}
		{/each}
	</div>
</div>
<div
	on:click={toggleSidebar}
	class="fixed top-0 left-0 z-20 right-0 bottom-0 bg-black bg-opacity-30 lg:hidden"
	class:hidden={!open}
/>
<div class="{className} lg:block hidden" />

<button
	class="lg:hidden rounded-r-full outline-none shadow bg-primary text-white fixed top-22 h-14 left-0 z-30 px-4"
	on:click={toggleSidebar}
>
	<i class="fa fa-bars" />
</button>
