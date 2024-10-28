<template>
  <nav>
    <router-link to="/dashboard"><img alt="photo" src="https://maechanhospital.go.th/images/logo66.png" style="width: 160px; height: auto; "/></router-link>
    <div>
      <router-link to="/dashboard" class="hover:text-[#29b6f6]">สถิติการทำงาน</router-link>
      <router-link to="/people" class="hover:text-[#29b6f6]">การทำงานรายบุคคล</router-link>
      <router-link to="/time" class="hover:text-[#29b6f6]">บันทึกเวลาการทำงาน</router-link>
      <router-link to="/name" class="hover:text-[#29b6f6]">รายชื่อพนักงาน</router-link>
      <button v-if="isAuthenticated" @click="logout">
        <i class="fas fa-sign-out-alt icon hover:text-[#ff6060]"></i>
      </button>
      <router-link v-else to="/login">
        <i class="fas fa-sign-in-alt icon hover:text-[#64ceff]"></i>
      </router-link>
    </div>
  </nav>
  <div class="content">
    <router-view />
  </div>
</template>

<script>
import { useStore } from 'vuex';
import { computed } from 'vue';
import { useRouter } from 'vue-router';
import Swal from 'sweetalert2';

export default {
  setup() {
    const store = useStore();
    const router = useRouter();

    const isAuthenticated = computed(() => store.getters.isAuthenticated);

    const logout = async () => {
      await store.dispatch('logout');
      Swal.fire({
        icon: 'success',
        title: 'ออกจากระบบสำเร็จ!',
        text: 'คุณได้ออกจากระบบเรียบร้อยแล้ว',
      });
      router.push({ name: 'login' }); // Redirect to login page after logout
    };

    return {
      isAuthenticated,
      logout,
    };
  },
};
</script>

<style>
*{
  padding: 0;
  margin: 0;
  box-sizing: border-box;
}

#app {
  font-family: "K2D", sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  /* text-align: center; */
  color: #2c3e50;
  /* position: relative; */
}

#app::before {
  content: "";
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  z-index: -1;
  background: linear-gradient(to bottom, #3399FF, #87cefa); /* ไล่เฉดจากสีน้ำเงินเข้มไปสีฟ้าอ่อน */
}

nav {
  position: fixed;
  top: 0;
  width: 100%;
  padding: 10px;
  z-index: 1000;
  display: flex;
  padding: 10px 20px;
  justify-content: space-between;
  align-items: center;
  background-color: #FFFFFF;
  box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);

  button {
    color: #ff0000;
    font-weight: bold;
    padding: 0 20px;
    text-decoration: none;
    font-size: larger;
  }
}
nav a {
  font-weight: bold;
  color: #000000;
  padding: 0 20px;
  text-decoration: none;
  font-size: larger;
}
nav a.router-link-exact-active {
  color: #29b6f6;
}

.content {
  padding-top: 90px; /* Adjust this value based on your navbar height */
}

.icon {
  font-size: 22px;
  margin-right: 5px;
}

.swal2-title {
  font-family: 'K2D', sans-serif;
}

.swal2-content {
  font-family: 'K2D', sans-serif;
}

.swal2-confirm, .swal2-cancel {
  font-family: 'K2D', sans-serif;
}

.swal2-html-container{
  font-family: 'K2D', sans-serif;
}
</style>
