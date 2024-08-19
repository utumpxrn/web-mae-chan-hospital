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
      <input type="date" v-model="selectedDate"/>
  </label>
    <br><br>
    <div class="grid">
      <div class="box">รับแล้ว<br><br>{{ fil.length }}</div>
      <div class="box">งานที่สำเร็จ<br><br>{{ fil.length }}</div>
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
    const selectedOption = ref('ทั้งหมด'); // Set a default value or leave it empty
    const selectedDate = ref('');

    onMounted(() => {
      axios.get('http://localhost:3000/api/people')
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
      const month = String(date.getMonth() + 1).padStart(2, '0'); // Months are zero-based
      const day = String(date.getDate()).padStart(2, '0');
      return `${year}-${month}-${day}`;
    };

    const filteredItems = computed(() => items.value.filter((item) => {
      const matchName = selectedOption.value === 'ทั้งหมด' || item.ผู้รับ === selectedOption.value;
      const matchDate = !selectedDate.value || formatDate(item.stretcher_register_accept_date)
      === selectedDate.value;
      return matchName && matchDate;
    }));

    const uniqueNames = computed(() => {
      const namesSet = new Set(items.value.map((item) => item.ผู้รับ));
      return Array.from(namesSet);
    });

    const fil = computed(() => (selectedOption.value === 'ทั้งหมด'
      ? items.value
      : items.value.filter((item) => item.ผู้รับ === selectedOption.value)));

    return {
      items,
      selectedOption,
      fil,
      uniqueNames,
      filteredItems,
      selectedDate,
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
  text-align: left;
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

.name-select {
  border-radius: 8px;
  background-color: aqua;
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
