<script setup>
defineProps({
  standings: Array
})

const getClubLogo = (club) =>
  `/logos/${club.toLowerCase().replace(/\s+/g, '-')}.png` // пример: "Manchester United" → logos/manchester-united.png
</script>

<template>
  <section class="standings-table">
    <h2 class="table-title">Premier League Table</h2>
    <table>
      <thead>
        <tr>
          <th>#</th>
          <th>Club</th>
          <th>PL</th>
          <th>W</th>
          <th>D</th>
          <th>L</th>
          <th>GD</th>
          <th>PTS</th>
        </tr>
      </thead>
      <tbody>
        <tr
          v-for="(team, idx) in standings"
          :key="idx"
          :class="{
            champion: idx === 0,
            top4: idx > 0 && idx < 2,
            relegation: idx === standings.length - 1
          }"
        >
          <td>{{ idx + 1 }}</td>
          <td class="club-name">
            <img :src="getClubLogo(team.club)" alt="logo" class="club-logo" />
            {{ team.club }}
          </td>
          <td>{{ team.played }}</td>
          <td>{{ team.won }}</td>
          <td>{{ team.drawn }}</td>
          <td>{{ team.lost }}</td>
          <td>{{ team.goalDifference }}</td>
          <td class="points">{{ team.points }}</td>
        </tr>
      </tbody>
    </table>
  </section>
</template>

<style scoped>
.standings-table {
  border: 1px solid #ccc;
  border-radius: 6px;
  overflow: hidden;
  background: white;
}

.table-title {
  background-color: #37003c;
  color: white;
  padding: 1rem;
  font-size: 1.25rem;
  font-weight: bold;
  text-align: center;
}

table {
  width: 100%;
  border-collapse: collapse;
}

thead {
  background-color: #e5e5e5;
}

th, td {
  padding: 0.5rem;
  text-align: center;
  font-size: 0.9rem;
  border-bottom: 1px solid #ddd;
}

.club-name {
  text-align: left;
  font-weight: 600;
  display: flex;
  align-items: center;
  gap: 0.5rem;
}

.club-logo {
  width: 20px;
  height: 20px;
  object-fit: contain;
}

.points {
  font-weight: bold;
}

tbody tr:nth-child(odd) {
  background-color: #f9f9f9;
}

.champion {
  background-color: #d4edda;
}

.top4 {
  background-color: #e0f0ff;
}

.relegation {
  background-color: #ffe0e0;
}
</style>
