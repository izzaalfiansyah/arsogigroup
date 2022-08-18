<script lang="ts">
	import PenjualanSave from './PenjualanSave.svelte';

	export let params: {
		id?: string;
	} = {};
</script>

<PenjualanSave title="Purchase Order" status="1" prefix="PO" bind:params />
