<template>
  <br>
  <div class="container">
    <div class="charts">
      <div class="chart">
        <LineChart />
        <BarChart />
      </div>
      <TableHome />
    </div>
  </div>
</template>

<script>
import TableHome from '@/components/TableHome.vue';
import BarChart from '@/components/BarChart.vue';
import LineChart from '@/components/LineChart.vue';

export default {
  components: {
    TableHome,
    BarChart,
    LineChart,
  },
};
</script>

<style scoped>
.container {
  width: 100%;
  margin: 0 auto;
  padding: 0 2rem;
}

.charts {
  display: flex;
  flex-direction: column;
  gap: 10px;
}

.chart {
  display: grid;
  gap: 10px;
}

@media (min-width: 768px) {
  .charts {
    flex-direction: row;
  }
}
</style>
