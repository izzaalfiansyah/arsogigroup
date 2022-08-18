<script lang="ts">
	export let values: {
		header: String;
		key: any;
		render?: Function;
	}[];
	export let items: any[];
	export let loading = false;
</script>

<div class="overflow-x-auto">
	<table class="table">
		<thead class="lg:table-header-group hidden">
			<tr>
				{#each values as item}
					<td>{item.header}</td>
				{/each}
			</tr>
			<tr />
		</thead>
		<tbody class="lg:table-row-group flex flex-col">
			{#if loading}
				<tr class="h-12 skeleton">
					<td colspan={values.length}></td>
				</tr>
			{:else}
				{#each items as item}
					<tr class="hover:bg-gray-50 transition">
						{#each values as value}
							<td
								class="lg:table-cell flex -mx-2.5 justify-between items-center lg:w-auto max-w-screen"
							>
								<div class="lg:hidden block px-2.5 font-semibold whitespace-normal lg:whitespace-nowrap">
									{value.header}
								</div>
								<div
									class="block px-2.5 lg:text-left text-right whitespace-normal lg:whitespace-nowrap"
								>
									{#if item[value.key]}
										{#if value.render}
											{value.render(item[value.key])}
										{:else}
											{item[value.key]}
										{/if}
									{/if}
									<slot {item} key={value.key}>{item[value.key]}</slot>
								</div>
							</td>
						{/each}
					</tr>
					<tr />
				{:else}
					<tr>
						<td colspan={values.length}>
							<div class="text-center">data tidak tersedia.</div>
						</td>
					</tr>
					<tr></tr>
				{/each}
			{/if}
		</tbody>
	</table>
</div>
