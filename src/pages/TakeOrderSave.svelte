<script lang="ts">
	import PenjualanSave from './PenjualanSave.svelte';

	export let params: {
		id?: string;
	} = {};
</script>

<PenjualanSave title="Take Order" status="2" prefix="TO" bind:params />
