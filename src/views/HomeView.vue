<template>
  <div class="container">
    <HelloWorld />
  </div>
</template>

<script>
// @ is an alias to /src
import HelloWorld from '@/components/HelloWorld.vue';

export default {
  name: 'HomeView',
  components: {
    HelloWorld,
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
@media(min-width: 640px) {
  .container {
    max-width: 640px;
  }
}
@media(min-width: 768px) {
  .container {
    max-width: 768px;
  }
}
@media(min-width: 1024px) {
  .container {
    max-width: 1024px;
  }
}
@media(min-width: 1280px) {
  .container {
    max-width: 1280px;
  }
}
@media(min-width: 1536px) {
  .container {
    max-width: 1536px;
  }
}
</style>
