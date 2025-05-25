import { createApp } from 'vue'
import { createRouter, createWebHistory } from 'vue-router'
import App from './App.vue'
import Login from './views/Login.vue'
import PostsList from './views/PostsList.vue'
import SinglePost from './views/SinglePost.vue'
import naive from 'naive-ui'

const routes = [
  { path: '/', redirect: '/login' },
  { path: '/login', component: Login },
  { path: '/posts', component: PostsList },
  { path: '/posts/:id', component: SinglePost, props: true },
]

const router = createRouter({
  history: createWebHistory(),
  routes,
})

createApp(App).use(router).use(naive).mount('#app')
