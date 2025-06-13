<script setup>
import { ref, onMounted, nextTick } from 'vue'

import MatchList from '@/components/MatchList.vue'
import TeamStandings from '@/components/TeamStandings.vue'
import Predictions from '@/components/Predictions.vue'

const currentWeek = ref(1)
const standings = ref([])
const matches = ref([])
const predictions = ref([])
const seasonFinished = ref(false)

let polling = false
let pollTimeout = null
let pollingActive = false // Флаг, чтобы не запускать несколько параллельных pollUntil

async function safeFetch(url, options = {}) {
    try {
        const res = await fetch(url, options)
        if (!res.ok) throw new Error(`HTTP error ${res.status}`)
        return await res.json()
    } catch (e) {
        console.error(`Fetch error for ${url}:`, e)
        return null
    }
}

async function fetchCurrentWeek() {
    if (polling) return null
    polling = true
    const data = await safeFetch('/api/league/week')
    polling = false
    if (data && typeof data.week === 'number') {
        currentWeek.value = data.week
    }
    return data
}

async function fetchStandings() {
    const data = await safeFetch('/api/standings')
    if (data) standings.value = [...data]
}

async function fetchMatches() {
    const data = await safeFetch('/api/matches')
    if (data) {
        data.sort((a, b) => {
            if (a.week !== b.week) return a.week - b.week
            return a.id - b.id
        })
        matches.value = [...data]
    }
}

async function fetchPredictions() {
    if (currentWeek.value <= 4) {
        predictions.value = []
        return
    }
    const data = await safeFetch('/api/predictions')
    if (data) predictions.value = [...data]
}

function checkSeasonFinished() {
    seasonFinished.value = !matches.value.some((m) => m.scoreA === null || m.scoreB === null)
}

async function refreshAll() {
    await fetchCurrentWeek()
    await Promise.all([fetchStandings(), fetchMatches()])
    checkSeasonFinished()
    await fetchPredictions()
    await nextTick()
}

function resetState() {
    standings.value = []
    matches.value = []
    predictions.value = []
    seasonFinished.value = false
    currentWeek.value = 1
}

// Оптимизированный pollUntil с флагом и увеличенным интервалом (1500ms)
async function pollUntil(conditionFn, timeout = 10000, interval = 1500) {
    if (pollingActive) return false
    pollingActive = true
    const start = Date.now()
    return new Promise((resolve) => {
        const check = async () => {
            if (await conditionFn()) {
                pollingActive = false
                resolve(true)
            } else if (Date.now() - start > timeout) {
                pollingActive = false
                resolve(false)
            } else {
                pollTimeout = setTimeout(check, interval)
            }
        }
        check()
    })
}

async function generateSeasonSchedule() {
    console.log('🚀 generateSeasonSchedule()')

    resetState()

    await fetch('/api/simulate/schedule', { method: 'POST' })

    const success = await pollUntil(
        async () => {
            await fetchCurrentWeek()
            return currentWeek.value > prevWeek
        },
        10000,
        1500,
    )

    if (!success) console.warn('Polling for league state timed out.')

    await refreshAll()
}

async function nextWeek() {
    if (seasonFinished.value) {
        console.warn('❌ Season already finished')
        return
    }

    const prevWeek = currentWeek.value

    console.log('➡️ simulate next week')
    await fetch('/api/simulate/next-week', { method: 'POST' })

    const success = await pollUntil(
        async () => {
            await fetchCurrentWeek()
            return currentWeek.value > prevWeek
        },
        10000,
        1500,
    )

    if (!success) console.warn('Polling for week update timed out.')

    await refreshAll()
}

onMounted(() => {
    console.log('🏁 App mounted, calling refreshAll()')
    refreshAll()
})
</script>

<template>
    <main class="league-container">
        <header class="league-header">
            <h1>Insider Champions League</h1>
        </header>

        <div class="league-controls">
            <button class="league-btn" @click="generateSeasonSchedule">Generate</button>
            <button class="league-btn" @click="nextWeek" :disabled="seasonFinished">
                Next Week
            </button>
        </div>

        <TeamStandings :standings="standings" :key="currentWeek" />

        <div class="league-bottom">
            <div class="league-left">
                <MatchList :matches="matches" :currentWeek="currentWeek" />
            </div>
            <div class="league-right">
                <Predictions v-if="currentWeek > 4" :predictions="predictions" :key="currentWeek" />
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
