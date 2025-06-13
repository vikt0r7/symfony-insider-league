<template>
  <div class="flex gap-4 mb-6">
    <button @click="generateSeason" class="btn">Generate Season Schedule</button>
    <button @click="nextWeek" class="btn">Next Week</button>
  </div>
</template>

<script setup>
const emit = defineEmits(['refresh', 'next-week'])

const post = async url => {
  await fetch(url, { method: 'POST' })
}

const generateSeason = async () => {
  await post('/api/simulate/schedule')
  emit('refresh')
}

const nextWeek = async () => {
  await post('/api/simulate/next-week')
  emit('next-week')
}
</script>

<style scoped>
.btn {
  @apply bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded;
}
</style>
