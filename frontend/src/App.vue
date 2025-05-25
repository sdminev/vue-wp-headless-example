<template>
  <n-message-provider>
    <router-view />
  </n-message-provider>
</template>
<script setup>
import { NMessageProvider } from 'naive-ui'
console.log('App.vue loaded')
</script>
<style>
body {
  font-family: Arial, sans-serif;
  margin: 0;
  padding: 0;
  background: #f9f9f9;
}
</style>