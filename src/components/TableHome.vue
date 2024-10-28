<template>
  <div class="leaderboard">
    <p class="text-2xl flex justify-center">ตารางอันดับการทำงาน</p>
    <label for="date" class="flex justify-end py-2">
      <input type="date" class="border border-black rounded-md px-2" v-model="selectedDate" />
    </label>
    <table>
      <thead>
        <tr>
          <th>ชื่อผู้รับงาน</th>
          <th>จำนวนรอบ</th>
          <th>เวลาเฉลี่ยรวม</th>
        </tr>
      </thead>
      <tbody>
        <!-- Check if there is no data in filteredNames -->
        <tr v-if="filteredNames.length === 0">
          <td colspan="3" class="mx-auto text-center">ไม่มีข้อมูล</td>
        </tr>
        <!-- Display the data when available -->
        <tr v-for="(name, index) in filteredNames" :key="index">
          <td>{{ name }}</td>
          <td>{{ nameCounts[name] }}</td>
          <td>{{ calculateTotalTimeForName(name) }} นาที</td>
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
      axios.get('http://localhost/my-draft2/phpchatbot-jew/people.php')
        .then((response) => {
          items.value = response.data;
          console.log(response.data);
        })
        .catch((error) => {
          console.error('There was an error fetching the data:', error);
        });
    };

    const getCurrentDate = () => {
      const date = new Date();
      const year = date.getFullYear();
      const month = String(date.getMonth() + 1).padStart(2, '0'); // Zero-based month
      const day = String(date.getDate()).padStart(2, '0');
      return `${year}-${month}-${day}`;
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
        return items.value.filter((item) => formatDate(item.stretcher_register_accept_date)
          === selectedDate.value);
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
      selectedDate.value = getCurrentDate();
    });

    const calculateTimeDifference = (sendTime, returnTime) => {
      // Check if sendTime and returnTime are defined
      if (!sendTime || !returnTime) {
        return 0; // Return 0 if either is undefined or null
      }

      const timeToSeconds = (time) => {
        const [hours, minutes, seconds] = time.split(':').map(Number);
        return hours * 3600 + minutes * 60 + seconds;
      };

      const sendTimeInSeconds = timeToSeconds(sendTime);
      const returnTimeInSeconds = timeToSeconds(returnTime);

      let timeDifference = returnTimeInSeconds - sendTimeInSeconds;

      if (timeDifference < 0) {
        timeDifference += 86400; // 24 hours in seconds
      }

      return timeDifference; // Return the difference in seconds, not as a formatted string
    };

    const secondsToTime = (seconds) => {
      const hours = Math.floor(seconds / 3600).toString().padStart(2, '0');
      const minutes = Math.floor((seconds % 3600) / 60).toString().padStart(2, '0');
      const secs = (seconds % 60).toString().padStart(2, '0');
      return `${hours}:${minutes}:${secs}`;
    };

    const calculateTotalTimeForName = (name) => {
      const itemsForName = filteredItems.value.filter((item) => item.ผู้รับ === name);
      let totalSeconds = 0;

      itemsForName.forEach((item) => {
        const t = item.stretcher_register_send_time;
        const d = item.stretcher_register_return_time;
        totalSeconds += calculateTimeDifference(t, d);
      });

      return secondsToTime(totalSeconds);
    };

    // Make sure filteredItems is returned so it's available in the template
    return {
      selectedDate,
      filteredNames,
      nameCounts,
      formatDate,
      calculateTimeDifference,
      filteredItems, // Return filteredItems here
      calculateTotalTimeForName,
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
  height: 100%;
}

h2 {
  margin-bottom: 20px;
  color: #000000;
}

table {
  width: 100%;
  border-collapse: collapse;
}

th,
td {
  border: 1px solid #dddddd;
  padding: 8px;
  text-align: center;
}

th {
  background-color:rgb(113, 207, 238);
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
