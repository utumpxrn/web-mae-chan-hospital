<template>
    <div class="min-h-screen flex items-center justify-center">
        <div class="bg-white p-8 rounded-lg shadow-md w-80">
            <h2 class="text-2xl font-bold mb-6 text-center text-black">ลงทะเบียน</h2>
            <form @submit.prevent="register">
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="Username">
                        ชื่อผู้ใช้
                    <input v-model="Username"
                    class="shadow appearance-none border rounded w-full py-2 px-3
                    text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                    type="text" placeholder="ชื่อผู้ใช้"/>
                    </label>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="Email">
                        อีเมล
                    <input v-model="Email"
                        class="shadow appearance-none border rounded w-full py-2
                        px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        type="email" placeholder="อีเมล"/>
                    </label>
                </div>
                  <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="Name">
                      ชื่อ
                    <input v-model="Name"
                        class="shadow appearance-none border rounded w-full py-2
                        px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        type="text" placeholder="ชื่อ"/>
                    </label>
                </div>
                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="Password">
                    รหัสผ่าน
                    <input v-model="Password"
                    class="shadow appearance-none border rounded w-full py-2 px-3
                    text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                    type="password" placeholder="รหัสผ่าน"/>
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
      axios.post('http://localhost:3000/api/register/', {
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
