// router/eventos.routes.js
export default [
  {
    path: '/eventos',
    name: 'Eventos',
    component: () => import('@/components/Eventos/EventosGrid.vue'),
    meta: {
      title: 'Gerenciar Eventos',
      requiresAuth: true,
    },
  },
];
