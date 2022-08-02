<template>
  <base-table
    :header-items="headerItems"
    :records="records"
    table-title="Leader Board"
  >
    <template slot="no-items"> No Leader board Found </template>
  </base-table>
</template>

<script>
export default {
  name: 'LeaderBoard',
  data: () => ({
    headerItems: [
      {
        id: 'game_name',
        name: 'User',
      },
      {
        id: 'date',
        name: 'Date',
      },
      {
        id: 'status',
        name: 'Score',
      },
      {
        id: 'duration',
        name: 'Time',
      },
    ],
    records: [
      {
        game_name: 'Docholiday247',
        date: '15/11/2020',
        status: '08982',
        duration: '34s',
      },
      {
        game_name: 'steveq',
        date: '15/11/2020',
        status: '108982',
        duration: '14s',
      },
      {
        game_name: 'neilmcd',
        date: '15/11/2020',
        status: 'qq08982',
        duration: '140s',
      },
      {
        game_name: 'jamiemcd',
        date: '15/11/2020',
        status: '2308982',
        duration: '304s',
      },
    ],
  }),
}
</script>

<style></style>
