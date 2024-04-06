<script setup>
// import { useMainStore } from '@/stores/main'
import UserAvatar from '@/components/UserAvatar.vue'

// const mainStore = useMainStore()
</script>

<template>
  <UserAvatar :username="'asdsad'" :avatar="'asdasxz'">
    <slot />
  </UserAvatar>
</template>
