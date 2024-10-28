<template>
  <div>
    <canvas class="bar" ref="myChart" style="background-color: #FFFFFF; border-radius: 10px;
    width: 300px;">
    </canvas>
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
    const dayCounts = ref(new Array(7).fill(0));
    const myChart = ref(null);
    const ChartLi = ref(null);

    const createChart = (data) => {
      const ctx = myChart.value.getContext('2d');
      Chart.register(...registerables);

      new Chart(ctx, {
        type: 'bar',
        data: {
          labels: dayLabels,
          datasets: [{
            label: 'สถิติการทำงานรายสัปดาห์',
            data,
            borderWidth: 1,
            backgroundColor: [
              'rgba(255, 99, 132, 0.2)',
              'rgba(255, 206, 86, 0.2)',
              'rgba(255, 192, 203, 0.2)',
              'rgba(75, 192, 192, 0.2)',
              'rgba(255, 159, 64, 0.2)',
              'rgba(54, 162, 235, 0.2)',
              'rgba(153, 102, 255, 0.2)',
            ],
            borderColor: [
              'rgba(255, 99, 132, 1)',
              'rgba(255, 206, 86, 1)',
              'rgba(255, 192, 203, 1)',
              'rgba(75, 192, 192, 1)',
              'rgba(255, 159, 64, 1)',
              'rgba(54, 162, 235, 1)',
              'rgba(153, 102, 255, 1)',
            ],
          }],

        },
        options: {
          scales: {
            x: {
              title: {
                display: true,
                text: 'วัน',
                font: {
                  family: 'K2D', // Custom font for Y-axis
                  size: 14,
                  weight: 'bold',
                },
              },
              ticks: {
                font: {
                  family: 'K2D', // Custom font for X-axis ticks (labels)
                  size: 12,
                  style: 'italic',
                  weight: 'bold',
                },
              },
            },
            y: {
              beginAtZero: true,
              min: 0,
              max: 50,
              title: {
                display: true,
                text: 'จำนวนรอบ',
                font: {
                  family: 'K2D', // Custom font for Y-axis
                  size: 14,
                  weight: 'bold',
                },
              },
              ticks: {
                font: {
                  family: 'K2D',
                  size: 12,
                  style: 'italic',
                  weight: 'bold',
                },
              },
            },
          },
          plugins: {
            legend: {
              labels: {
                font: {
                  family: 'K2D', // Custom font for Y-axis
                  size: 14,
                  weight: 'bold',
                },
              },
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
            const dayIndex = date.getDay();
            dayCounts.value[dayIndex] += 1;
          });

          // items.value.forEach((item) => {
          //   const date = new Date(item.stretcher_register_accept_date);
          //   const monthIndex = date.getMonth(); // 0 for January, 1 for February, etc.
          //   monthCounts.value[monthIndex] += 1;
          // });

          // Create the chart after processing data
          createChart(dayCounts.value);
          console.log(dayCounts.value);
        })
        .catch((error) => {
          console.error('There was an error fetching the data:', error);
        });
    });
    return {
      items,
      dayCounts,
      myChart,
      ChartLi,
    };
  },
});
</script>
