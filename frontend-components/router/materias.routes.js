// router/materias.routes.js
export default [
  {
    path: '/materias',
    name: 'Materias',
    component: () => import('@/components/Materias/MateriasGrid.vue'),
    meta: {
      title: 'Gerenciar Matérias',
      requiresAuth: true,
    },
  },
  {
    path: '/materias/create',
    name: 'MateriasCreate',
    component: () => import('@/components/Materias/MateriaForm.vue'),
    meta: {
      title: 'Nova Matéria',
      requiresAuth: true,
    },
  },
  {
    path: '/materias/edit/:id',
    name: 'MateriasEdit',
    component: () => import('@/components/Materias/MateriaForm.vue'),
    meta: {
      title: 'Editar Matéria',
      requiresAuth: true,
    },
  },
];
