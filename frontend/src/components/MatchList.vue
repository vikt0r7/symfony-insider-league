<template>
    <div>
        <h2 class="text-xl font-bold mb-4">Match List — Season</h2>
        <ul>
            <li
                v-for="match in matches"
                :key="match.id"
                :class="match.scoreA !== null && match.scoreB !== null ? 'text-green-700' : 'text-blue-700'"
                class="mb-1"
            >
                Week {{ match.week }} — {{ match.teamA.name }} vs {{ match.teamB.name }} —
                {{ match.scoreA !== null ? match.scoreA : '-' }} : {{ match.scoreB !== null ? match.scoreB : '-' }}
            </li>
        </ul>
    </div>
</template>

<script setup>
const props = defineProps({
    matches: Array,
    currentWeek: Number
})
</script>

<style scoped>
.text-green-700 {
    color: #047857;
}
.text-blue-700 {
    color: #1d4ed8;
}
</style>
