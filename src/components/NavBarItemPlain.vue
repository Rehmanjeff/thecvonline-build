<script setup>
defineProps({
  display: {
    type: String,
    default: 'flex'
  },
  useMargin: Boolean
})
</script>

<template>
  <div
    :class="[display, useMargin ? 'my-2 mx-3' : 'py-2 px-3']"
    class="items-center text-white cursor-pointer navbar-item-label hover:text-slate-400"
  >
    <slot />
  </div>
</template>
