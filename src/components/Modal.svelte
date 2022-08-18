<script lang="ts">
  export let open = false;

  let content;

  function handleClick(e) {
    if (e.path.indexOf(content) < 0) {
      open = false;
    }
  }
</script>

<div class="fixed top-0 left-0 bottom-0 right-0 z-30 bg-black bg-opacity-25 flex justify-center lg:items-center items-end lg:p-4 p-0 pt-[70px]" class:hidden={!open} on:click={handleClick}>
  <div class="card !m-0 max-w-full w-[550px] max-h-full modal overflow-y-auto" bind:this={content}>
    <slot></slot>
  </div>
</div>