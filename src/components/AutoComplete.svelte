<script lang="ts">
	import { createEventDispatcher, onDestroy, onMount } from 'svelte';

	export let items: Array<{
		value: any;
		text: string;
		[key: string]: any;
	}>;
	export let value;

	let show = false;
	let valueText = '';
	let element;
	let filteredItems = items;

	const dispatch = createEventDispatcher();

	$: if (value) {
		items.forEach((item) => {
			if (item.value == value) {
				valueText = item.text;
			}
		});
	} else {
		valueText = '';
	}


	function handleTextInput(e) {
		const text = e.currentTarget.value;
		const data = [];
		items.forEach((item) => {
			if (item.text.toLowerCase().indexOf(text.toLowerCase()) >= 0) {
				data.push(item);
			}
		});
		filteredItems = data;
	}

	function handleTextBlur(e) {
		const text = e.currentTarget.value;
		let data = 0;
		items.forEach((item) => {
			if (item.text == text) {
				data += 1;
			}
		});

		if (!data) {
			value = '';
			valueText = '';
		}
	}

	function handleOutsideClick(e) {
		if (e.path.indexOf(element) < 0) {
			if (show) {
				show = false;
			}
		}
	}

	onMount(() => {
		window.addEventListener('click', handleOutsideClick);
	});

	onDestroy(() => {
		window.removeEventListener('click', handleOutsideClick);
	});
</script>

<div class="relative" bind:this={element}>
	<input
		type="text"
		value={valueText}
		on:click={(e) => e.currentTarget.select()}
		on:focus={(e) => {
			show = true;
			handleTextInput(e);
		}}
		on:input={handleTextInput}
		on:blur={handleTextBlur}
		class="input"
		{...$$restProps}
	/>

	{#if show}
		<div
			class="z-10 absolute bottom-0 left-0 right-0 transform shadow translate-y-full rounded py-1 bg-white overflow-y-auto max-h-[200px]"
		>
			{#each filteredItems as item}
				<div
				class="cursor-pointer transition hover:(bg-blue-500 text-white) p-2"
				on:click={() => {
					value = item.value;
					valueText = item.text;
					show = false;
					dispatch('change');
				}}>
					<slot {item}>
						{item.text}
					</slot>
				</div>
			{/each}
		</div>
	{/if}
</div>
