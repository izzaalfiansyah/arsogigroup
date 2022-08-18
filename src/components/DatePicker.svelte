<script lang="ts">
	import { en } from '@/lib';
	import { createEventDispatcher, onDestroy, onMount } from 'svelte';
	import SveltyPicker from 'svelty-picker';

	export let value;
	export { className as class };

	let className = '';
	let valueText = '';
	let show = false;
	let element;

	function handleOutslideClick(e) {
		if (e.path.indexOf(element) < 0) {
			if (show) {
				show = false;
			}
		}
	}

	const dispatch = createEventDispatcher();

	onMount(() => {
		window.addEventListener('click', handleOutslideClick);
	});

	onDestroy(() => {
		window.removeEventListener('click', handleOutslideClick);
	});
</script>

<div class="relative inline-block" bind:this={element}>
	<input
		type="text"
		readonly
		class="input {className}"
		bind:value={valueText}
		{...$$restProps}
		on:click={() => (show = true)}
	/>
	{#if show}
		<div class="absolute bottom-0 left-0 right-0 transform translate-y-full">
			<SveltyPicker
				format="yyyy-mm-dd"
				bind:value
				pickerOnly={true}
				on:change={(e) => {
					const date = e.detail.split('-');
					const tanggal = date[2];
					const bulan = en.months[parseInt(date[1]) - 1];
					const tahun = date[0];

					valueText = tanggal + ' ' + bulan + ' ' + tahun;
          show = false;
					dispatch('change');
				}}
				todayBtn={false}
				clearBtn={false}
				i18n={en}
			/>
		</div>
	{/if}
</div>
