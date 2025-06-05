<script setup>
import {ref, onMounted, watch} from 'vue'
import MatchList from '@/components/MatchList.vue'
import TeamStandings from '@/components/TeamStandings.vue'
import Predictions from '@/components/Predictions.vue'

const currentWeek = ref(1)
const standings = ref([])
const matches = ref([])
const predictions = ref([])
const seasonFinished = ref(false)

async function fetchCurrentWeek() {
    const res = await fetch('/api/league/week')
    const data = await res.json()
    currentWeek.value = data.week
    checkSeasonFinished()
}

async function fetchStandings() {
    const res = await fetch('/api/standings')
    standings.value = await res.json()
}

async function fetchMatches() {
    const res = await fetch('/api/matches')
    matches.value = await res.json()
}

async function fetchPredictions() {
    if (currentWeek.value > 4) {
        const res = await fetch('/api/predictions')
        predictions.value = res.ok ? await res.json() : []
    } else {
        predictions.value = []
    }
}

function checkSeasonFinished() {
    seasonFinished.value = !matches.value.some(m => m.scoreA === null || m.scoreB === null)
}

async function refreshAll() {
    await fetchCurrentWeek()
    await Promise.all([fetchStandings(), fetchMatches()])
    checkSeasonFinished()
    await fetchPredictions()
}

const generateSeasonSchedule = async () => {
    await fetch('/api/simulate/schedule', {method: 'POST'})
    await refreshAll()
}

const nextWeek = async () => {
    if (seasonFinished.value) return
    await fetch('/api/simulate/next-week', {method: 'POST'})
    await refreshAll()
}

onMounted(() => {
    refreshAll()
})

watch(currentWeek, fetchPredictions)
</script>

<template>
    <main class="league-container">
        <header class="league-header">
            <h1>Insider Champions League</h1>
        </header>

        <div class="league-controls">
            <button class="league-btn" @click="generateSeasonSchedule">Generate</button>
            <button class="league-btn" @click="nextWeek" :disabled="seasonFinished">Next Week</button>
        </div>

        <TeamStandings :standings="standings"/>

        <div class="league-bottom">
            <div class="league-left">
                <MatchList :matches="matches" :currentWeek="currentWeek"/>
            </div>
            <div class="league-right">
                <Predictions v-if="currentWeek > 4" :predictions="predictions"/>
            </div>
        </div>

        <div v-if="seasonFinished" class="league-finished">
            🏁 Season Finished — Champion: <strong>{{ standings[0]?.club || 'N/A' }}</strong>
        </div>
    </main>
</template>

<style scoped>
.league-container {
    max-width: 1100px;
    margin: 0 auto;
    padding: 2rem;
    background-color: #f5f5f5;
    font-family: Arial, sans-serif;
}

.league-header {
    background-color: #37003c;
    color: white;
    padding: 1.5rem;
    text-align: center;
    font-size: 2rem;
    border-radius: 6px;
    margin-bottom: 1.5rem;
}

.league-controls {
    display: flex;
    justify-content: center;
    gap: 0.75rem;
    margin-bottom: 1rem;
}

.league-btn {
    background-color: #0050ff;
    color: white;
    font-size: 0.8rem;
    padding: 0.4rem 0.9rem;
    border: none;
    border-radius: 4px;
    font-weight: bold;
    cursor: pointer;
    transition: 0.2s;
}

.league-btn:hover {
    background-color: #003cb3;
}

.league-btn:disabled {
    background-color: #ccc;
    cursor: not-allowed;
}

.league-bottom {
    display: flex;
    gap: 2rem;
    margin-top: 2rem;
}

.league-left {
    flex: 2;
}

.league-right {
    flex: 1;
}

.league-finished {
    margin-top: 2rem;
    background-color: #d4edda;
    color: #155724;
    text-align: center;
    padding: 1rem;
    font-weight: bold;
    border-radius: 6px;
}


</style>
