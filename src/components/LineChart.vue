<template>
  <div class="charts">
    <div class="chart">
      <div >
        <canvas class="line" ref="ChartLi" style="background-color: #FFFFFF; border-radius: 10px;">
        </canvas>
      </div>
      <div>
        <canvas class="bar" ref="myChart" style="background-color: #FFFFFF; border-radius: 10px;">
        </canvas>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';
import { ref, onMounted, defineComponent } from 'vue';
import { Chart, registerables } from 'chart.js';

export default defineComponent({
  name: 'DataFetcher',
  setup() {
    const items = ref([]);
    const dayLabels = ['อาทิตย์', 'จันทร์', 'อังคาร', 'พุธ', 'พฤหัสบดี', 'ศุกร์', 'เสาร์'];
    const monthLable = ['มกราคม', 'กุมภาพันธ์', 'มีนาคม', 'เมษายน', 'พฤษภาคม', 'มิถุนายน', 'กรกฎาคม', 'สิงหาคม', 'กันยายน', 'ตุลาคม', 'พฤศจิกายน', 'ธันวาคม'];
    const dayCounts = ref(new Array(7).fill(0));
    const monthCounts = ref(new Array(12).fill(0));
    const myChart = ref(null);
    const ChartLi = ref(null);

    const createChartLi = (data) => {
      const ctx = ChartLi.value.getContext('2d');
      Chart.register(...registerables);

      new Chart(ctx, {
        type: 'line',
        data: {
          labels: monthLable,
          datasets: [{
            label: 'การทำงานภายในเดือนนี้',
            data,
            borderWidth: 1,
          }],
        },
        options: {
          scales: {
            y: {
              beginAtZero: true,
              max: 50,
            },
          },
        },
      });
    };

    const createChart = (data) => {
      const ctx = myChart.value.getContext('2d');
      Chart.register(...registerables);

      new Chart(ctx, {
        type: 'bar',
        data: {
          labels: dayLabels,
          datasets: [{
            label: 'การทำงานภายในสัปดาห์นี้',
            data,
            borderWidth: 1,
          }],
        },
        options: {
          scales: {
            y: {
              beginAtZero: true,
              max: 50,
            },
          },
        },
      });
    };

    onMounted(() => {
      axios.get('http://localhost:3000/api/timestamp')
        .then((response) => {
          items.value = response.data;

          // Convert the timestamp to day of the week and count occurrences
          items.value.forEach((item) => {
            const date = new Date(item.stretcher_register_accept_date);
            const dayIndex = date.getDay(); // 0 for Sunday, 1 for Monday, etc.
            dayCounts.value[dayIndex] += 1;
            const monthIndex = date.getMonth(); // 0 for January, 1 for February, etc.
            monthCounts.value[monthIndex] += 1;
          });

          // items.value.forEach((item) => {
          //   const date = new Date(item.stretcher_register_accept_date);
          //   const monthIndex = date.getMonth(); // 0 for January, 1 for February, etc.
          //   monthCounts.value[monthIndex] += 1;
          // });

          // Create the chart after processing data
          createChart(dayCounts.value);
          createChartLi(monthCounts.value);
          console.log(dayCounts.value);
        })
        .catch((error) => {
          console.error('There was an error fetching the data:', error);
        });
    });

    return {
      items,
      dayCounts,
      monthCounts,
      myChart,
      ChartLi,
    };
  },
});
</script>
<style scoped>
.line{
  width: 100%;
  height: 100%;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}
</style>
