<template>
  <div class="min-h-screen flex items-center justify-center">
    <div class="bg-white p-8 rounded-lg shadow-md w-80">
      <h2 class="text-2xl font-bold mb-6 text-center text-black">เข้าสู่ระบบ</h2>
      <form @submit.prevent="signIn">
        <div class="mb-4">
          <label class="block text-gray-700 text-sm font-bold mb-2" for="Username">
            <input
            v-model="Username"
            class="shadow appearance-none border rounded w-full py-2 px-3
            text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
            placeholder="ชื่อผู้ใช้"/>
          </label>
        </div>
        <div class="mb-6">
          <label class="block text-gray-700 text-sm font-bold mb-2" for="Password">
            <input
            v-model="Password"
            class="shadow appearance-none border rounded w-full py-2 px-3
            text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
            type="password" placeholder="รหัสผ่าน"/>
          </label>
        </div>
        <button type="submit" class="w-full bg-sky-500
            text-white py-2 rounded-md hover:bg-sky-600 transition duration-300">
          เข้าสู่ระบบ
        </button>
      </form>
      <div class="mt-4 text-center">
        <a href="/register" class="text-sky-500 hover:underline">ลงทะเบียน</a>
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
    const Password = ref('');

    const signIn = () => {
      console.log('Login submitted', { Username: Username.value, Password: Password.value });
      axios.post('http://localhost:3000/api/signIn', {
        Username: Username.value,
        Password: Password.value,
      })
        .then((response) => {
          console.log('Login successful', response.data);
          if (response.data.token !== undefined) {
            localStorage.setItem('token', response.data.token);
            window.location = '/';
          }
        })
        .catch((error) => {
          console.error('Login failed', error);
        });
    };

    return {
      Username,
      Password,
      signIn,
    };
  },
};
</script>
