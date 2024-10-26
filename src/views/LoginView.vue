<template>
  <div class="absolute w-[100dvw] h-[80dvh]">
    <div class="absolute bg-white px-10 py-14 rounded-lg shadow-md w-1/4 top-1/2 left-1/2
      justify-center items-center transform -translate-x-1/2 -translate-y-1/2">
      <h2 class="text-2xl font-bold mb-6 text-center text-black">เข้าสู่ระบบ</h2>
      <form @submit.prevent="signIn">
        <div class="mb-4">
          <label class="block text-gray-700 text-sm font-bold mb-2" for="Username">
            <input
              v-model="Username"
              class="shadow appearance-none border rounded w-full py-2 px-3
              text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
              placeholder="ชื่อผู้ใช้"
            />
          </label>
        </div>
        <div class="mb-6">
          <label class="block text-gray-700 text-sm font-bold mb-2" for="Password">
            <input
              v-model="Password"
              class="shadow appearance-none border rounded w-full py-2 px-3
              text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
              type="password"
              placeholder="รหัสผ่าน"
            />
          </label>
        </div>
        <button type="submit" class="w-full bg-sky-500 text-white py-2 rounded-md
        hover:bg-sky-600 transition duration-300">
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
import { useStore } from 'vuex';
import { useRouter } from 'vue-router';
import Swal from 'sweetalert2';

export default {
  setup() {
    const Username = ref('');
    const Password = ref('');
    const store = useStore();
    const router = useRouter();

    const signIn = () => {
      // Dispatch login action to Vuex store
      store.dispatch('login', {
        Username: Username.value,
        Password: Password.value,
      })
        .then(() => {
          Swal.fire({
            icon: 'success',
            title: 'เข้าสู่ระบบสำเร็จ!',
            text: 'ยินดีต้อนรับกลับ',
          }).then(() => {
            router.push({ name: 'home' }); // Redirect to home after clicking OK
          });
        })
        .catch((error) => {
          console.error('Login failed', error);
          Swal.fire({
            icon: 'error',
            title: 'เข้าสู่ระบบล้มเหลว',
            text: 'กรุณาตรวจสอบชื่อผู้ใช้และรหัสผ่าน',
          });
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
