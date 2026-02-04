// router/index.js
import { createRouter, createWebHistory } from 'vue-router';
import materiasRoutes from './materias.routes';
import eventosRoutes from './eventos.routes';

const routes = [
  {
    path: '/',
    name: 'Dashboard',
    component: () => import('@/views/Dashboard.vue'),
    meta: {
      title: 'Dashboard',
      requiresAuth: true,
    },
  },
  ...materiasRoutes,
  ...eventosRoutes,
];

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes,
});

// Navigation guard para autenticação
router.beforeEach((to, from, next) => {
  const token = localStorage.getItem('token');
  const requiresAuth = to.matched.some(record => record.meta.requiresAuth);

  if (requiresAuth && !token) {
    next({ name: 'Login' });
  } else {
    // Atualiza o título da página
    document.title = to.meta.title ? `${to.meta.title} - AERANERD Admin` : 'AERANERD Admin';
    next();
  }
});

export default router;
