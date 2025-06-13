<template>
    <div>
        <h2>Edit Match</h2>
        <div v-if="match">
            <form @submit.prevent="saveMatch">
                <label>Team A Score:
                    <input v-model.number="match.scoreA" type="number" min="0" required/>
                </label>
                <label>Team B Score:
                    <input v-model.number="match.scoreB" type="number" min="0" required/>
                </label>
                <button type="submit">Save</button>
            </form>
        </div>
        <div v-else>
            <p>Select a match to edit</p>
        </div>
    </div>
</template>

<script setup>
import {ref, watch} from 'vue'
import {defineProps, defineEmits} from 'vue'

const props = defineProps({
    matchId: Number,
    matches: Array
})

const emit = defineEmits(['updated'])
const match = ref(null)

watch(() => props.matchId, (id) => {
    match.value = props.matches.find(m => m.id === id) || null
}, {immediate: true})

async function saveMatch() {
    if (!match.value) return

    const response = await fetch(`/api/matches/${match.value.id}`, {
        method: 'PUT',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({
            scoreA: match.value.scoreA,
            scoreB: match.value.scoreB
        })
    })

    if (response.ok) {
        emit('updated')
    } else {
        alert('Failed to save')
    }
}
</script>

<style scoped>
form {
    display: flex;
    flex-direction: column;
    gap: 1rem;
    max-width: 300px;
}

input {
    width: 100%;
    padding: 0.4rem;
}
</style>
