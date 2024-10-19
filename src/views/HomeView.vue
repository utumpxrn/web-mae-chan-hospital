<template>
  <br><br>
<div class="container">
  <div class="charts">
    <div class="chart">
      <div class="">
        <LineChart/>
      </div>
      <div>
        <BarChart/>
      </div>
    </div>
    <div class="table">
      <TableHome/>
    </div>
  </div>
</div>
</template>

<script>
import TableHome from '@/components/TableHome.vue';
import BarChart from '@/components/BarChart.vue';
import LineChart from '@/components/LineChart.vue';

export default ({
  components: {
    TableHome,
    BarChart,
    LineChart,
  },
});

</script>

<style scoped>
.charts {
  display: flex;
  flex-direction: column;
  gap: 10px;
}

.chart {
  display: grid;
  grid-template-columns: 1fr;
  flex: 1;
  gap: 10px;
}

.table {
  flex: 1;
}

@media (min-width: 768px) {
  .charts {
    flex-direction: row;
  }
}

.container {
  width: 100%;
  margin-right: auto;
  margin-left: auto;
  padding-right: 2rem;
  padding-left: 2rem;
}

</style>
