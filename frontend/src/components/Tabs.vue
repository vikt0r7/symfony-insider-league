<template>
    <div class="max-w-5xl mx-auto py-8">
        <div class="flex gap-4 mb-6">
            <button
                v-for="tab in tabs"
                :key="tab"
                @click="activeTab = tab"
                :class="[
                    'px-4 py-2 font-semibold rounded',
                    activeTab === tab ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700',
                ]"
            >
                {{ tab }}
            </button>
        </div>

        <div v-if="activeTab === 'Standings'">
            <TeamStandings />
        </div>

        <div v-else-if="activeTab === 'Matches'">
            <MatchList :matches="matches" @edit="editMatch" @refresh="loadMatches" />
        </div>

        <div v-else-if="activeTab === 'Control'">
            <ControlPanel @refresh="loadMatches" />
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import TeamStandings from './TeamStandings.vue'
import MatchList from './MatchList.vue'
import ControlPanel from './ControlPanel.vue'

const tabs = ['Standings', 'Matches', 'Control']
const activeTab = ref('Standings')

const matches = ref([])

const loadMatches = async () => {
    const res = await fetch('/api/matches')
    const data = await res.json()
    data.sort((a, b) => {
        if (a.week !== b.week) return a.week - b.week
        return a.id - b.id
    })
    matches.value = data
}

const editMatch = (id) => {
    console.log('Edit match', id)
}

onMounted(loadMatches)
</script>
