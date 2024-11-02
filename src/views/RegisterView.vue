<template>
  <div class="absolute w-[100dvw] h-[80dvh]">
    <div class="absolute bg-white px-10 py-10 rounded-lg shadow-md w-1/4 top-1/2 left-1/2
            justify-center items-center transform -translate-x-1/2 -translate-y-1/2">
      <h2 class="text-2xl font-bold mb-6 text-center text-black">ลงทะเบียน</h2>
      <form @submit.prevent="register">
        <div class="mb-4">
          <label class="block text-gray-700 text-sm font-bold mb-2" for="Username">
            ชื่อจริง
            <input v-model="Name" class="shadow appearance-none border rounded w-full py-2 px-3
            text-gray-700 leading-tight focus:outline-none focus:shadow-outline" type="text"
              placeholder="ชื่อจริง" />
          </label>
        </div>
        <div class="mb-4">
          <label class="block text-gray-700 text-sm font-bold mb-2" for="Username">
            ชื่อผู้ใช้
            <input v-model="Username" class="shadow appearance-none border rounded w-full py-2 px-3
            text-gray-700 leading-tight focus:outline-none focus:shadow-outline" type="text"
              placeholder="ชื่อผู้ใช้" />
          </label>
        </div>
        <div class="mb-4">
          <label class="block text-gray-700 text-sm font-bold mb-2" for="Email">
            อีเมล
            <input v-model="Email" class="shadow appearance-none border rounded w-full py-2
              px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" type="email"
              placeholder="อีเมล" />
          </label>
        </div>
        <div class="mb-6">
          <label class="block text-gray-700 text-sm font-bold mb-2" for="Password">
            รหัสผ่าน
            <input v-model="Password" class="shadow appearance-none border rounded w-full py-2 px-3
            text-gray-700 leading-tight focus:outline-none focus:shadow-outline" type="password"
              placeholder="รหัสผ่าน" />
          </label>
        </div>
        <button type="submit" class="w-full bg-sky-500 text-white py-2
                    rounded-md hover:bg-sky-600 transition duration-300">
          ลงทะเบียน
        </button>
      </form>
      <div class="mt-4 text-center">
        <a href="/login" class="text-sky-500 hover:underline">เข้าสู่ระบบ</a>
      </div>
    </div>
  </div>
</template>

<script>
import { ref } from 'vue';
import axios from 'axios';

export default {
  setup() {
    const Username = ref('');
    const Email = ref('');
    const Password = ref('');
    const Name = ref('');

    const register = () => {
      if (!Username.value || !Email.value || !Password.value || !Name.value) {
        alert('Please fill in all fields.');
        return;
      }
      axios.post('http://localhost/my-draft2/phpchatbot-jew/register.php', {
        Username: Username.value,
        Email: Email.value,
        Password: Password.value,
        Name: Name.value,
      })
        .then((response) => {
          console.log('Registration successful', response.data);
          window.location = '/login';
        })
        .catch((error) => {
          console.error('Registration failed', error);
        });
    };

    return {
      Username,
      Email,
      Password,
      Name,
      register,
    };
  },
};
</script>
