<script setup>
import { computed } from 'vue'

const props = defineProps({
  username: {
    type: String,
    required: true
  },
  avatar: {
    type: String,
    default: null
  },
  api: {
    type: String,
    default: 'avataaars'
  }
})

const avatar = computed(
  () =>
    props.avatar ??
    `https://api.dicebear.com/7.x/${props.api}/svg?seed=${props.username.replace(
      /[^a-z0-9]+/gi,
      '-'
    )}.svg`
)

const username = computed(() => props.username)
</script>

<template>
  <div>
    <img
      :src="avatar"
      :alt="username"
      class="block w-full h-auto max-w-full rounded-full bg-slate-800"
    />
    <slot />
  </div>
</template>
