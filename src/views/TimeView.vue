<template>
  <br>
<div class="container">
  <h2 style="text-align: center;">บันทึกเวลาการทำงาน</h2>
  <label for="date" style="margin-left: 10px">
    <input type="date" v-model="selectedDate"/>
  </label>
  <br><br>
    <table>
      <thead>
        <tr>
          <th>ผู้รับ</th>
          <th>เริ่มงาน</th>
          <th>จบงาน</th>
          <th>วันที่</th>
        </tr>
      </thead>
      <tbody class="scrollable-tbody">
        <tr v-if="filteredItems.length === 0">
          <td colspan="4" class="mx-auto text-center">ไม่มีข้อมูล</td>
        </tr>
        <tr v-for="(item, index) in filteredItems" :key="index">
          <td>{{ item.ผู้รับ }}</td>
          <td>{{ item.stretcher_register_send_time }}</td>
          <td>{{ item.stretcher_register_return_time }}</td>
          <td>{{ formatDate(item.stretcher_register_accept_date) }}</td>
        </tr>
      </tbody>
    </table>
</div>
</template>

<script>
import axios from 'axios';
import { ref, onMounted, computed } from 'vue';

export default {
  name: 'DataFetcher',
  setup() {
    const items = ref([]);
    const selectedDate = ref('');

    // Get today's date in YYYY-MM-DD format
    const getTodayDate = () => {
      const today = new Date();
      const year = today.getFullYear();
      const month = String(today.getMonth() + 1).padStart(2, '0');
      const day = String(today.getDate()).padStart(2, '0');
      return `${year}-${month}-${day}`;
    };

    // Set selectedDate to today's date when the component is mounted
    onMounted(() => {
      selectedDate.value = getTodayDate();
      axios.get('http://localhost/my-draft2/phpchatbot-jew/timestamp.php')
        .then((response) => {
          items.value = response.data;
        })
        .catch((error) => {
          console.error('There was an error fetching the data:', error);
        });
    });

    const formatDate = (dateString) => {
      const date = new Date(dateString);
      const year = date.getFullYear();
      const month = String(date.getMonth() + 1).padStart(2, '0');
      const day = String(date.getDate()).padStart(2, '0');
      return `${year}-${month}-${day}`;
    };

    const filteredItems = computed(() => {
      const sortedItems = [...items.value].sort((a, b) => {
        const dateA = new Date(a.stretcher_register_accept_date);
        const dateB = new Date(b.stretcher_register_accept_date);
        return dateB - dateA; // Sort by latest date first
      });

      if (!selectedDate.value) {
        return sortedItems;
      }

      return sortedItems.filter((item) => formatDate(item.stretcher_register_accept_date)
        === selectedDate.value);
    });

    return {
      items,
      formatDate,
      selectedDate,
      filteredItems,
      getTodayDate,
    };
  },
};

</script>

<style scoped>
.container{
  width: 100%;
  margin-right: auto;
  margin-left: auto;
  padding-right: 4rem;
  padding-left: 4rem;
}
table {
  width: 100%;
  border-collapse: collapse;
}
th, td {
  border: 1px solid #dddddd;
  text-align: left;
  padding: 8px;
  background-color: #FFFFFF;
  text-align: center;
}
th {
  background-color: #f2f2f2;
  text-align: center;
}
h2{
  font-size: 32px;
  color: #FFFFFF;
}

.scrollable-tbody {
  display: block;
  max-height: 400px; /* Adjust this value as needed */
  overflow-y: auto;
}

.scrollable-tbody tr {
  display: table;
  width: 100%;
  table-layout: fixed;
}

thead, tbody tr {
  display: table;
  width: 100%;
  table-layout: fixed;
}
</style>
