<template>
  <br>
  <div class="container">
    <div>
      <h2 class="text-3xl" style="text-align: center;">รายชื่อพนักงาน</h2>
      <div class="mb-4">
        <button class="bg-white rounded-md p-1"
        @click="showModal = true" @keydown.enter="showModal = true"
          style="margin-left: 90%;">เพิ่มบุคคล +</button>
      </div>
      <table>
        <thead>
          <tr>
            <th>ไอดี พนักงาน</th>
            <th>ชื่อพนักงาน</th>
            <th>ตำแหน่ง</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(item, index) in items" :key="index">
            <td>{{ item.ID }}</td>
            <td>{{ item.Name }}</td>
            <td>{{ item.Role }}</td>
          </tr>
        </tbody>
      </table>
      <!-- Modal for adding new employee -->
      <div v-if="showModal" class="modal">
        <div class="modal-content">
          <span class="close" @click="showModal = false" @keydown.enter="showModal = false"
          @keydown.space="showModal = false">&times;</span>
          <h2>เพิ่มบุคคล</h2>
          <form @submit.prevent="addUser">
            <label For="name">Name:
              <input type="text" id="name" v-model="newUser.Name" class="mt-1 block w-full
            border border-slate-500 rounded-md focus:outline-none pl-1" required><br><br>
            </label>

            <label For="position">Role:
              <input type="text" id="position" v-model="newUser.Role" class="mt-1 block w-full
            border border-slate-500 rounded-md focus:outline-none pl-1" required><br><br>
            </label>

            <button type="submit" class="bg-blue-500 text-white px-4 py-2
            rounded-md hover:bg-blue-600
            focus:outline-none focus:bg-blue-600">เพิ่ม</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';
import { ref, onMounted } from 'vue';

export default {
  name: 'DataFetcher',
  setup() {
    const items = ref([]);
    const showModal = ref(false);
    const newUser = ref({
      Name: '',
      Role: '',
    });

    const fetchUsers = () => {
      axios.get('http://localhost/my-draft2/phpchatbot-jew/user.php')
        .then((response) => {
          items.value = response.data;
          console.log(items);
        })
        .catch((error) => {
          console.error('There was an error fetching the data:', error);
        });
    };

    const addUser = () => {
      axios.post('http://localhost:3000/api/addusers', {
        Name: newUser.value.Name,
        Role: newUser.value.Role,
      })
        .then(() => {
          fetchUsers(); // Refresh the list
          showModal.value = false; // Close the modal
          newUser.value = { Name: '', Role: '' }; // Reset the form
        })
        .catch((error) => {
          console.error('There was an error adding the user:', error);
        });
    };

    onMounted(() => {
      fetchUsers();
    });

    return {
      items,
      showModal,
      newUser,
      addUser,
    };
  },
};
</script>

<style>
.container {
  width: 100%;
  margin-right: auto;
  margin-left: auto;
  padding-right: 4rem;
  padding-left: 4rem;
}

.modal {
  position: fixed;
  z-index: 1;
  left: 0;
  top: 0;
  width: 100%;
  height: 100%;
  overflow: auto;
  background-color: rgba(0, 0, 0, 0.4);
}

.modal-content {
  background-color: #fefefe;
  margin: 15% auto;
  padding: 20px;
  border: 1px solid #888;
  width: 30%;
}

.close {
  color: #aaa;
  float: right;
  font-size: 28px;
  font-weight: bold;
  cursor: pointer;
}

.close:hover,
.close:focus {
  color: black;
  text-decoration: none;
}

table {
  width: 100%;
  border-collapse: collapse;
}

th,
td {
  border: 1px solid #dddddd;
  padding: 8px;
  background: #FFFFFF;
  text-align: center;
}

th {
  background-color: #f2f2f2;
}

h2 {
  color: #FFFFFF;
  font-size: 32px;
}
</style>
