<script setup>
defineProps({
  zIndex: {
    type: String,
    default: 'z-50'
  },
  type: {
    type: String,
    default: 'flex'
  }
})

const emit = defineEmits(['overlay-click'])

const overlayClick = (event) => {
  emit('overlay-click', event)
}
</script>

<template>
  <div
    :class="[type, zIndex]"
    class="fixed inset-0 flex-col items-center justify-center overflow-hidden"
  >
    <transition
      enter-active-class="transition duration-150 ease-in"
      enter-from-class="opacity-0"
      enter-to-class="opacity-100"
      leave-active-class="transition duration-150 ease-in"
      leave-from-class="opacity-100"
      leave-to-class="opacity-0"
    >
      <div
        class="absolute inset-0 overlay bg-gradient-to-tr opacity-90 from-gray-700 via-gray-900 to-gray-700"
        @click="overlayClick"
      />
    </transition>
    <transition
      enter-active-class="transition duration-100 ease-out"
      enter-from-class="transform scale-95 opacity-0"
      enter-to-class="transform scale-100 opacity-100"
      leave-active-class="animate-fade-out"
    >
      <slot />
    </transition>
  </div>
</template>
