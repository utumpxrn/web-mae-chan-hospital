<template>
  <div >
    <canvas class="line" ref="myChart" style="background-color: #FFFFFF; border-radius: 10px;">
    </canvas>
  </div>
</template>

<script>
import { defineComponent } from 'vue';
import { Chart, registerables } from 'chart.js';

export default defineComponent({
  mounted() {
    const ctx = this.$refs.myChart.getContext('2d');
    Chart.register(...registerables);

    new Chart(ctx, {
      type: 'line',
      data: {
        labels: ['มกราคม', 'กุมภาพันธ์', 'มีนาคม', 'เมษายน', 'พฤษภาคม', 'มิถุนายน', 'กรกฎาคม', 'สิงหาคม', 'กันยายน', 'ตุลาคม', 'พฤศจิกายน', 'ธันวาคม'],
        datasets: [{

          label: 'การทำงานภายในเดือนนี้',
          data: [65, 59, 80, 81, 56, 55, 40, 45, 55, 60, 70, 75],
          borderWidth: 1,
        }],
      },
      options: {
        scales: {
          y: {
            beginAtZero: true,
          },
        },
      },
    });
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
