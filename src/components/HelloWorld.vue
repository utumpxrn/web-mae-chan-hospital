<template>
  <br><br>
  <div class="charts">
    <div class="chart">
      <div>
        <canvas class="line" ref="ChartLi" style="background-color: #FFFFFF; border-radius: 10px;">
        </canvas>
      </div>
      <div>
        <canvas class="bar" ref="myChart" style="background-color: #FFFFFF; border-radius: 10px;">
        </canvas>
      </div>
    </div>
    <div class="table">
      <TableHome/>
    </div>
  </div>
</template>

<script>
import Table from './TableHome.vue';

export default ({
  components: {
    Table,
  },
});

</script>

<style scoped>
.date-label {
  display: inline-block;
  margin: 10px 0;
}

.date-input {
  border-radius: 10px;
  padding: 5px;
}

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

.bar {
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

@media (min-width: 768px) {
  .charts {
    flex-direction: row;
  }
}
</style>
