<script lang="ts">
	import { createEventDispatcher } from 'svelte';

	export let page;
	export let totalCount;
  export let limit;

  $: totalPage = Math.ceil(totalCount / limit);

	const dispatch = createEventDispatcher();
</script>

<button
	disabled={page <= 1}
	class="text-gray-500 disabled:text-gray-300 mr-1"
	on:click={() => {
		page -= 1;
		dispatch('change');
	}}
>
	<i class="fa fa-chevron-left" />
</button>
{page} - {totalPage}
<button
	disabled={page >= totalPage}
	class="text-gray-500 disabled:text-gray-300 ml-1"
	on:click={() => {
		page += 1;
		dispatch('change');
	}}
>
	<i class="fa fa-chevron-right" />
</button>
