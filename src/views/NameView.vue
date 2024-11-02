<template>
  <div class="container">
    <div>
      <h2 class="text-3xl" style="text-align: center; padding-top: 50px;">รายชื่อพนักงาน</h2>
      <div class="mb-4">
        <button class="add-button" @click="showAddModal = true"
        @keydown.enter="showAddModal = true">เพิ่มบุคคล +</button>
      </div>
      <table>
        <thead>
          <tr>
            <th class=" w-28">ไอดี พนักงาน</th>
            <th class=" w-1/4">ชื่อไลน์</th>
            <th class=" w-2/5">ชื่อพนักงาน</th>
            <th>ตำแหน่ง</th>
            <th class=" w-48">จัดการ</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(item, index) in items" :key="index">
            <td>{{ item.ID }}</td>
            <td>{{ item.Line_name }}</td>
            <td>{{ item.Name }}</td>
            <td>{{ item.Role }}</td>
            <td>
              <button class="edit-button" @click="openEditModal(item)">
                <i class="fa-solid fa-pen-to-square"></i>
              </button>
              <button class="delete-button" @click="deleteUser(item.ID)">
                <i class="fa-solid fa-trash"></i>
              </button>
            </td>
          </tr>
        </tbody>
      </table>

      <!-- Modal for adding new employee -->
      <div v-if="showAddModal" class="modal">
        <div class="modal-content">
          <span class="close" @click="showAddModal = false" @keydown.enter="showAddModal = false"
            @keydown.space="showAddModal = false">&times;</span>
          <h2 class=" text-black py-4">เพิ่มบุคคล</h2>
          <form @submit.prevent="addUser">
            <label for="Name">ชื่อ:
              <input type="text" id="Name" v-model="newUser.Name" class="input-field" required />
            </label>
            <label for="Line_name">ชื่อไลน์:
              <input type="text" id="Line_name" v-model="newUser.Line_name"
              class="input-field" required />
            </label>
            <label for="Role">ตำแหน่ง:
              <input type="text" id="Role" v-model="newUser.Role" class="input-field" required />
            </label>

            <button type="submit" class="submit-button">เพิ่ม</button>
          </form>
        </div>
      </div>

      <!-- Edit Employee Modal -->
      <div v-if="showEditModal" class="modal">
        <div class="modal-content">
          <span class="close" @click="showEditModal = false"
          @keydown.space="showAddModal = false">&times;</span>
          <h2>แก้ไขบุคคล</h2>
          <form @submit.prevent="updateUser">
            <label for="editName">ชื่อ:
              <input type="text" id="editName" v-model="editUser.Name"
              class="input-field" required />
            </label>
            <label for="editLine_name">ชื่อไลน์:
              <input type="text" id="editLine_name" v-model="editUser.Line_name"
              class="input-field" required />
              </label>
              <label for="editRole">ตำแหน่ง:
                <input type="text" id="editRole" v-model="editUser.Role"
                class="input-field" required />
              </label>
              <button type="submit" class="submit-button">อัปเดต</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';
import { ref, onMounted } from 'vue';
import Swal from 'sweetalert2';

export default {
  name: 'DataFetcher',
  setup() {
    const items = ref([]);
    const showAddModal = ref(false);
    const showEditModal = ref(false);
    const newUser = ref({ Name: '', Line_name: '', Role: '' });
    const editUser = ref({
      ID: '', Name: '', Line_name: '', Role: '',
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
      axios.post('http://localhost/my-draft2/phpchatbot-jew/user.php', {
        Name: newUser.value.Name,
        Line_name: newUser.value.Line_name,
        Role: newUser.value.Role,
      })
        .then(() => {
          fetchUsers(); // Refresh the list
          showAddModal.value = false; // Close the modal
          newUser.value = { Name: '', Line_name: '', Role: '' }; // Reset the form
        })
        .catch((error) => {
          console.error('There was an error adding the user:', error);
        });
    };

    const openEditModal = (user) => {
      editUser.value = { ...user }; // Clone the user object
      showEditModal.value = true;
    };

    const updateUser = () => {
      axios.put('http://localhost/my-draft2/phpchatbot-jew/user.php', {
        ID: editUser.value.ID,
        Line_name: editUser.value.Line_name,
        Name: editUser.value.Name,
        Role: editUser.value.Role,
      })
        .then(() => {
          fetchUsers(); // Refresh the user list after updating
          showEditModal.value = false; // Close the edit modal
          editUser.value = {
            ID: '', Line_name: '', Name: '', Role: '',
          }; // Reset the edit form
        })
        .catch((error) => {
          console.error('There was an error updating the user:', error);
        });
    };

    const deleteUser = (userID) => {
      // Show SweetAlert2 confirmation dialog
      Swal.fire({
        title: 'คุณต้องการลบหรือไม่?',
        text: 'การกระทำนี้ไม่สามารถยกเลิกได้',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'ใช่',
        cancelButtonText: 'ยกเลิก',
      }).then((result) => {
        if (result.isConfirmed) {
          // Proceed with deletion if confirmed
          axios.delete('http://localhost/my-draft2/phpchatbot-jew/user.php', {
            data: { ID: userID },
          })
            .then(() => {
              fetchUsers(); // Refresh the user list after deletion
              Swal.fire(
                'เสร็จสิ้น!',
                'รายชื่อได้ถูกลบแล้ว',
                'success',
              );
            })
            .catch((error) => {
              console.error('There was an error deleting the user:', error);
              Swal.fire(
                'เกิดข้อผิดพลาด!',
                'เกิดข้อผิดพลาดในการลบผู้ใช้.',
                'error',
              );
            });
        }
      });
    };

    onMounted(() => {
      fetchUsers();
    });

    return {
      items,
      showAddModal,
      showEditModal,
      newUser,
      editUser,
      addUser,
      openEditModal,
      updateUser,
      deleteUser,
    };
  },
};
</script>

<style>
.container {
  width: 100%;
  margin: 0 auto;
  padding: 0 4rem;
}

.add-button {
  background-color: #fff;
  border: 1px solid #ddd;
  border-radius: 6px;
  padding: 0.5rem 1rem;
  margin-left: auto;
  display: block;
  cursor: pointer;
  transition: background-color 0.3s;
}

.add-button:hover {
  background-color: #f2f2f2;
}

.modal {
  position: fixed;
  z-index: 10;
  left: 0;
  top: 0;
  width: 100%;
  height: 100%;
  display: flex;
  align-items: center;
  justify-content: center;
  background-color: rgba(0, 0, 0, 0.5);
}

.modal-content {
  background-color: #fff;
  border-radius: 10px;
  padding: 2rem;
  width: 80%;
  max-width: 500px;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
  position: relative;
}

.close {
  position: absolute;
  top: 1rem;
  right: 1rem;
  font-size: 1.5rem;
  color: #333;
  cursor: pointer;
}

.input-field {
  display: block;
  width: 100%;
  padding: 0.5rem;
  margin-top: 0.5rem;
  margin-bottom: 1.5rem;
  border: 1px solid #ddd;
  border-radius: 6px;
  outline: none;
}

.input-field:focus {
  border-color: #29b6f6;
  box-shadow: 0 0 5px rgba(41, 182, 246, 0.5);
}

.submit-button {
  background-color: #29b6f6;
  color: #fff;
  padding: 0.5rem 1rem;
  border-radius: 6px;
  border: none;
  cursor: pointer;
  transition: background-color 0.3s;
}

.submit-button:hover {
  background-color: #0288d1;
}

table {
  width: 100%;
  border-collapse: collapse;
  margin-top: 1rem;
}

th,
td {
  border: 1px solid #ddd;
  padding: 0.75rem;
  background: #fff;
  text-align: center;
}

th {
  background-color: #f2f2f2;
}

h2 {
  color: #FFFFFF;
  font-size: 32px;
}

.edit-button,
.delete-button {
  padding: 0.5rem 1.5rem;
  border-radius: 6px;
  color: #fff;
  margin: 0 4px;
}

.edit-button {
  background-color: #29b6f6;
}

.delete-button {
  background-color: #e57373;
}

.edit-button:hover {
  background-color: #5dccff;
}

.delete-button:hover {
  background-color: #f58989;
}
</style>
