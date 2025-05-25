<template>
  <div class="login">
    <n-card title="Login">
      <n-space vertical>
        <n-input v-model:value="username" placeholder="Username" />
        <n-input v-model:value="password" placeholder="Password" type="password" />
        <n-button type="primary" @click="handleLogin">Login</n-button>
        <n-text v-if="error" type="error">{{ error }}</n-text>
      </n-space>
    </n-card>
  </div>
</template>
<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import api from '../services/api'
import { useMessage } from 'naive-ui'

const router = useRouter()
const username = ref('')
const password = ref('')
const error = ref('')
const message = useMessage()

const handleLogin = async () => {
  try {
    const { data } = await api.post('login', {
      username: username.value,
      password: password.value,
    })
    localStorage.setItem('token', data.token)
	localStorage.setItem('role', data.role)
    message.success('Login successful')
    router.push('/posts')
  } catch (err) {
    error.value = 'Login failed. Please check your credentials.'
    message.error(error.value)
  }
}
</script>
<style scoped>
.login {
  max-width: 400px;
  margin: 100px auto;
  padding: 1rem;
}
</style>