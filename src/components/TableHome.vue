<template>
  <div class="leaderboard">
    <p class="text-2xl flex justify-center">ตารางอันดับ</p>
    <label for="date" class="flex justify-end py-2">
      <input
        type="date"
        class="border border-black rounded-md px-2"
        v-model="selectedDate"
      />
    </label>
    <table>
      <thead>
        <tr>
          <th>ชื่อ</th>
          <th>รอบ</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="(name, index) in filteredNames" :key="index">
          <td>{{ name }}</td>
          <td>{{ nameCounts[name] }}</td>
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

    const fetchUsers = () => {
      axios.get('http://localhost:3000/api/people')
        .then((response) => {
          items.value = response.data;
        })
        .catch((error) => {
          console.error('There was an error fetching the data:', error);
        });
    };

    const formatDate = (dateString) => {
      const date = new Date(dateString);
      const year = date.getFullYear();
      const month = String(date.getMonth() + 1).padStart(2, '0'); // Months are zero-based
      const day = String(date.getDate()).padStart(2, '0');
      return `${year}-${month}-${day}`;
    };

    const filteredItems = computed(() => {
      if (!selectedDate.value) {
        return items.value;
      }
      return items.value.filter((item) => formatDate(item.stretcher_register_accept_date)
       === selectedDate.value);
    });

    const nameCounts = computed(() => {
      const counts = {};
      filteredItems.value.forEach((item) => {
        const name = item.ผู้รับ.trim(); // Ensure consistent formatting of the names
        if (name) {
          counts[name] = (counts[name] || 0) + 1; // Increment count for each name
        }
      });
      return counts;
    });

    const filteredNames = computed(() => Object.keys(nameCounts.value)
      .sort((a, b) => nameCounts.value[b] - nameCounts.value[a]));

    onMounted(() => {
      fetchUsers();
    });

    return {
      selectedDate,
      filteredNames,
      nameCounts,
      formatDate,
    };
  },
};
</script>

<style scoped>
.leaderboard {
  background-color: #FFFFFF;
  color: #000000;
  padding: 20px;
  border-radius: 8px;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
  width: 100%;
}
h2 {
  margin-bottom: 20px;
  color: #000000;
}
table {
  width: 100%;
  border-collapse: collapse;
}
th, td {
  padding: 10px;
  text-align: left;
}
th {
  background-color: #59a2db;
  font-weight: bold;
}
td {
  border-bottom: 1px solid #1F2C42;
}
.avatar {
  width: 40px;
  height: 40px;
  border-radius: 50%;
}
</style>
