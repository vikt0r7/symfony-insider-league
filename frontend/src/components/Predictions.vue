<script setup>
const props = defineProps({
  predictions: Array,
})
</script>

<template>
  <section class="mt-8" v-if="predictions.length">
    <h2 class="text-xl font-bold mb-4">🏆 Championship Predictions</h2>
    <table class="min-w-full text-sm text-gray-800">
      <thead>
        <tr>
          <th>Club</th>
          <th>Points</th>
          <th>GD</th>
          <th>Win %</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="team in predictions" :key="team.team" class="hover:bg-gray-50">
          <td>{{ team.team }}</td>
          <td>–</td>
          <td>–</td>
          <td class="font-bold text-blue-700">{{ team.chance.toFixed(1) }}%</td>
        </tr>
      </tbody>
    </table>
  </section>
</template>
