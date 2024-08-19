<template>
  <div>
    <canvas class="bar" ref="myChart" style="background-color: #FFFFFF; border-radius: 10px;">
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
      type: 'bar',
      data: {
        labels: ['จันทร์', 'อังคาร', 'พุธ', 'พฤหัสบดี', 'ศุกร์', 'เสาร์', 'อาทิตย์'],
        datasets: [{
          label: 'การทำงานภายในสัปดาห์นี้',
          data: [8, 5, 4, 2, 7, 3, 5],
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
.bar{
  width: 100%;
  height: 500px;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}
</style>
