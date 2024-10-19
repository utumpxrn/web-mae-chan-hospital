<template>
  <nav>
    <img alt="photo" src="https://maechanhospital.go.th/images/logo66.png" style="width: 160px; height: auto; "/>
    <div>
      <router-link to="/">สถิติการทำงาน</router-link>
      <router-link to="/people">การทำงานรายบุคคล</router-link>
      <router-link to="/time">บันทึกเวลาการทำงาน</router-link>
      <router-link to="/name">รายชื่อพนักงาน</router-link>
    </div>
  </nav>
  <div class="content">
    <router-view />
  </div>
</template>
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
</style>
