<template>
  <div class="posts">
    <n-card title="Posts">
      <template v-if="!authed">
        <n-alert title="Please log in" type="warning">
          You must be logged in to view posts.
          <br />
          <n-button type="primary" size="small" @click="router.push('/login')">Go to Login</n-button>
        </n-alert>
      </template>

      <n-space vertical v-else-if="loading">
        <n-skeleton text :repeat="3" />
      </n-space>

      <n-space vertical v-else>
        <n-card v-for="post in posts" :key="post.id" size="small">
          <template #header>
            <n-space justify="space-between">
              <n-button text @click="() => goToPost(post.id)">{{ post.title }}</n-button>
              <n-button v-if="isEditor" type="error" ghost @click="() => deletePost(post.id)">Delete</n-button>
            </n-space>
          </template>
          <n-thing>
            <template #avatar>
              <img v-if="post.thumbnail" :src="post.thumbnail" alt="Thumbnail" style="width: 200px" />
            </template>
            <n-text depth="3">{{ post.excerpt }}</n-text>
            <br />
            <small>{{ formatDate(post.date) }} by {{ post.author }}</small>
          </n-thing>
        </n-card>
      </n-space>
    </n-card>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { useMessage } from 'naive-ui'
import api from '../services/api'

const router = useRouter()
const message = useMessage()
const posts = ref([])
const loading = ref(true)
const isEditor = ref(false) // changed from true → false

const authed = !!localStorage.getItem('token')

const fetchPosts = async () => {
  try {
    const { data } = await api.get('posts')
    posts.value = data
  } catch (err) {
    message.error('Failed to fetch posts')
  } finally {
    loading.value = false
  }
}

const deletePost = async (id) => {
  try {
    await api.delete(`posts/${id}`)
    posts.value = posts.value.filter(p => p.id !== id)
    message.success('Post deleted')
  } catch (err) {
    message.error('Failed to delete post')
  }
}

const goToPost = (id) => {
  router.push(`/posts/${id}`)
}

if (authed) {
  const role = localStorage.getItem('role')
  isEditor.value = ['editor', 'administrator'].includes(role)

  onMounted(fetchPosts)
}

const formatDate = (iso) => {
  return new Intl.DateTimeFormat('en-GB', {
    dateStyle: 'medium',
    timeStyle: 'short'
  }).format(new Date(iso))
}
</script>


<style scoped>
.posts {
  max-width: 800px;
  margin: 2rem auto;
  padding: 1rem;
}
</style>
