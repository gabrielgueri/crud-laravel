<template>
  <div id="app">
    <h1>Laravel + Vue.js SPA</h1>
    <p>Welcome to your containerized application!</p>
  </div>
</template>

<script>
export default {
  name: 'App',
  data() {
    return {
      message: 'Hello from Vue.js',
    }
  },
}
</script>

<style scoped>
div {
  text-align: center;
  padding: 20px;
}
</style>
