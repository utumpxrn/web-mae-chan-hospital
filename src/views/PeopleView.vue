<template>
  <div class="container">
    <br>
    <label for="selectedOption" class="relative w-full ml-90 h-10 rounded-md">
      <select id="name-select" v-model="selectedOption"
        class="flex ml-auto items-end w-1/5 h-full rounded-md appearance-none bg-white border
        border-gray-300
        text-gray-900 px-3 py-2 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-600">
        <option value="ทั้งหมด">ทั้งหมด</option>
        <option v-for="(name, index) in uniqueNames" :key="index" :value="name">
          {{ name }}
        </option>
      </select>
    </label>
    <br>
    <label for="date" style="margin-left: 10px">
      <input type="date" v-model="selectedDate" class="border border-black rounded-md px-2"/>
    </label>
    <br><br>
    <div class="grid">
      <div class="box">จำนวนงานที่รับแล้ว<br><br>{{ filteredItems.length }}</div>
      <div class="box">จำนวนงานที่สำเร็จ<br><br>{{ completedItems.length }}</div>
    </div>
    <br>
    <table>
      <thead>
        <tr class="title">
          <th>วันที่</th>
          <th>จาก</th>
          <th>ถึง</th>
          <th>สถานะ</th>
        </tr>
      </thead>
      <tbody class="scrollable-tbody">
        <tr v-if="filteredItems.length === 0">
          <td colspan="4" class="mx-auto text-center">ไม่มีข้อมูล</td>
        </tr>
        <tr v-for="(item, index) in filteredItems" :key="index">
          <td>{{ formatDate(item.stretcher_register_accept_date) }}</td>
          <td>{{ item.from_depcode }}</td>
          <td>{{ item.send_depcode }}</td>
          <td>{{ item.stretcher_work_status_id }}</td>
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
    const selectedOption = ref('ทั้งหมด');
    const selectedDate = ref('');

    // Get today's date in YYYY-MM-DD format
    const getTodayDate = () => {
      const today = new Date();
      const year = today.getFullYear();
      const month = String(today.getMonth() + 1).padStart(2, '0'); // Zero-based month
      const day = String(today.getDate()).padStart(2, '0');
      return `${year}-${month}-${day}`;
    };

    onMounted(() => {
      // Automatically set selectedDate to today's date
      selectedDate.value = getTodayDate();

      // Fetch data
      axios.get('http://localhost:3000/API/people')
        .then((response) => {
          items.value = response.data;
        })
        .catch((error) => {
          console.error('There was an error fetching the data:', error);
        });
    });

    // Format date as YYYY-MM-DD
    const formatDate = (dateString) => {
      const date = new Date(dateString);
      const year = date.getFullYear();
      const month = String(date.getMonth() + 1).padStart(2, '0');
      const day = String(date.getDate()).padStart(2, '0');
      return `${year}-${month}-${day}`;
    };

    // Sort and filter items by name and date
    const filteredItems = computed(() => {
      const sortedItems = [...items.value].sort((a, b) => {
        const dateA = new Date(a.stretcher_register_accept_date);
        const dateB = new Date(b.stretcher_register_accept_date);
        return dateB - dateA; // Sort by latest date first
      });

      const matchName = selectedOption.value === 'ทั้งหมด';
      // const matchDate = selectedDate.value === getTodayDate();

      return sortedItems.filter((item) => {
        const isNameMatch = matchName || item.ผู้รับ === selectedOption.value;
        const isDateMatch = !selectedDate.value
         || formatDate(item.stretcher_register_accept_date) === selectedDate.value;
        return isNameMatch && isDateMatch;
      });
    });

    // Unique names for the dropdown
    const uniqueNames = computed(() => {
      const namesSet = new Set(items.value.map((item) => item.ผู้รับ));
      return Array.from(namesSet);
    });

    // Filter completed items
    const completedItems = computed(() => filteredItems
      .value.filter((item) => item.stretcher_work_status_id === 3));

    return {
      items,
      selectedOption,
      selectedDate,
      filteredItems,
      uniqueNames,
      completedItems,
      formatDate,
    };
  },
};
</script>

<style scoped>
.container {
  width: 100%;
  margin-right: auto;
  margin-left: auto;
  padding-right: 4rem;
  padding-left: 4rem;
}
table {
  width: 100%;
  border-collapse: collapse;
  background: #FFFFFF;
}
th, td {
  border: 1px solid #dddddd;
  padding: 8px;
  text-align: center;
}

.title {
  background-color: white;
}

.grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  grid-gap: 1rem;
}

.box {
  background-color: #ffffff;
  padding: 1rem;
  border-radius: 0.5rem;
  width: auto;
  height: auto;
  text-align: center;
  font-size: 20px;
}

.scrollable-tbody {
  display: block;
  max-height: 400px;
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
