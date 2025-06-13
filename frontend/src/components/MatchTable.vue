<template>
    <div>
        <h2>Upcoming Matches</h2>
        <table>
            <thead>
            <tr>
                <th>Date</th>
                <th>Team A</th>
                <th>Team B</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="match in matches" :key="match.id">
                <td>{{ match.date }}</td>
                <td>{{ match.teamA.name }}</td>
                <td>{{ match.teamB.name }}</td>
            </tr>
            </tbody>
        </table>
    </div>
</template>

<script setup>
import {ref, onMounted} from 'vue'

const matches = ref([])

onMounted(async () => {
    const res = await fetch('/api/matches')
    matches.value = await res.json()
})
</script>

<style scoped>
table {
    width: 100%;
    border-collapse: collapse;
}

th, td {
    border: 1px solid #ccc;
    padding: 8px;
    text-align: center;
}

th {
    background-color: #f4f4f4;
}
</style>
