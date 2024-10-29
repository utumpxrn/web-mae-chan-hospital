<template>
  <div class="charts">
    <div class="chart">
      <div>
        <canvas class="line" ref="ChartLi" style="background-color: #FFFFFF; border-radius: 10px;
        width: 600px;">
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
    const monthLable = ['มกราคม', 'กุมภาพันธ์', 'มีนาคม', 'เมษายน', 'พฤษภาคม', 'มิถุนายน', 'กรกฎาคม', 'สิงหาคม', 'กันยายน', 'ตุลาคม', 'พฤศจิกายน', 'ธันวาคม'];
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
            label: 'สถิติการทำงานรายเดือน',
            data,
            borderWidth: 1,
            borderColor: [
              'rgba(153, 102, 255, 1)',
            ],
          }],
        },
        options: {
          scales: {
            x: {
              title: {
                display: true,
                text: 'เดือน',
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
                  family: 'K2D', // Custom font for X-axis ticks (labels)
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
      axios.get('http://localhost/my-draft2/phpchatbot-jew/timestamp.php')
        .then((response) => {
          items.value = response.data;

          // Convert the timestamp to day of the week and count occurrences
          items.value.forEach((item) => {
            const date = new Date(item.stretcher_register_accept_date);
            const monthIndex = date.getMonth(); // 0 for January, 1 for February, etc.
            monthCounts.value[monthIndex] += 1;
          });

          // items.value.forEach((item) => {
          //   const date = new Date(item.stretcher_register_accept_date);
          //   const monthIndex = date.getMonth(); // 0 for January, 1 for February, etc.
          //   monthCounts.value[monthIndex] += 1;
          // });

          // Create the chart after processing data
          createChartLi(monthCounts.value);
          console.log(monthCounts.value);
        })
        .catch((error) => {
          console.error('There was an error fetching the data:', error);
        });
    });

    return {
      items,
      monthCounts,
      myChart,
      ChartLi,
    };
  },
});
</script>
