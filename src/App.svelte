<script lang="ts">
	import Admin from './layouts/Admin.svelte';
	import Login from './layouts/Login.svelte';
	import { auth } from './lib';

	const isLogin = auth.id ? true : false;
</script>

{#if isLogin}
	<Admin />
{:else}
	<Login />
{/if}
