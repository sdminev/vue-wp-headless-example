<template>
  <div class="single-post">
    <template v-if="!authed">
      <n-alert title="Please log in" type="warning">
        You must be logged in to view this post.
        <br />
        <n-button type="primary" size="small" @click="router.push('/login')">Go to Login</n-button>
      </n-alert>
    </template>

    <template v-else>
      <n-button @click="goBack" tertiary style="margin-bottom: 1rem">
        ← Back to all posts
      </n-button>
      <n-card v-if="post" :title="post.title">
        <n-space vertical>
          <img v-if="post.thumbnail" :src="post.thumbnail" alt="Thumbnail" style="max-width:100%; height:auto;" />
          <div v-html="post.content"></div>
        </n-space>
      </n-card>
      <n-skeleton v-else text :repeat="4" />
    </template>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import api from '../services/api'
import { useMessage } from 'naive-ui'

const route = useRoute()
const router = useRouter()
const message = useMessage()
const post = ref(null)

const authed = !!localStorage.getItem('token')

const goBack = () => {
  router.push('/posts')
}

onMounted(async () => {
  if (!authed) return
  try {
    const { data } = await api.get(`posts/${route.params.id}`)
    post.value = data
  } catch (err) {
    message.error('Failed to load post')
  }
})
</script>

<style scoped>
.single-post {
  max-width: 800px;
  margin: 2rem auto;
  padding: 1rem;
}
</style>
