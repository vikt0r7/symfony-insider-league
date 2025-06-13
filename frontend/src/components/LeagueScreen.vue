<template>
    <div class="grid grid-cols-3 gap-6">
        <!-- League Table -->
        <div>
            <TeamStandings :standings="standings" small />
        </div>

        <!-- Match Results -->
        <div>
            <h2 class="text-xl font-bold mb-2 text-center">Week {{ currentWeek }} Match Result</h2>
            <ul class="bg-white shadow rounded-lg p-4 space-y-2">
                <li v-for="match in weekMatches" :key="match.id" class="flex justify-between">
                    <span>{{ match.teamA.name }}</span>
                    <span>{{ match.scoreA }} - {{ match.scoreB }}</span>
                    <span>{{ match.teamB.name }}</span>
                </li>
            </ul>
        </div>

        <!-- Predictions -->
        <div>
            <h2 class="text-xl font-bold mb-2 text-center">{{ currentWeek }}ᵗʰ Week Predictions</h2>
            <ul class="bg-white shadow rounded-lg p-4 space-y-2">
                <li v-for="(item, index) in predictions" :key="index" class="flex justify-between">
                    <span>{{ item.name }}</span>
                    <span>{{ item.percent }}%</span>
                </li>
            </ul>
        </div>
    </div>

    <!-- Buttons -->
    <div class="flex justify-center gap-4 mt-6">
        <button @click="nextWeek" class="btn">Next Week</button>
        <button @click="playAll" class="btn">Play All</button>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import TeamStandings from '@/components/TeamStandings.vue'

const currentWeek = ref(1)
const standings = ref([])
const weekMatches = ref([])
const predictions = ref([])

const load = async () => {
    standings.value = await fetch('/api/standings').then((r) => r.json())
    weekMatches.value = (await fetch('/api/matches').then((r) => r.json()))
        .filter((m) => m.week === currentWeek.value)
        .sort((a, b) => a.id - b.id)
    predictions.value = calculatePredictions()
}

const nextWeek = async () => {
    await fetch('/api/simulate/next-week', { method: 'POST' })
    currentWeek.value++
    await load()
}

const playAll = async () => {
    await fetch('/api/simulate-all', { method: 'POST' })
    await load()
}

const calculatePredictions = () => {
    const total = standings.value.reduce((acc, t) => acc + t.points + 1, 0)
    return standings.value
        .map((t) => ({
            name: t.club,
            percent: Math.round(((t.points + 1) / total) * 100),
        }))
        .sort((a, b) => b.percent - a.percent)
}

onMounted(load)
</script>

<style scoped>
.btn {
    @apply bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2 rounded;
}
</style>
