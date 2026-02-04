<template>
  <aside class="sidebar" :class="{ collapsed: isCollapsed }">
    <div class="sidebar-header">
      <div class="logo">
        <img v-if="!isCollapsed" src="/logo.png" alt="AERANERD" />
        <span v-else class="logo-icon">A</span>
      </div>
      <button class="collapse-btn" @click="toggleCollapse" :title="isCollapsed ? 'Expandir' : 'Recolher'">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <line v-if="!isCollapsed" x1="19" y1="12" x2="5" y2="12"></line>
          <polyline v-if="!isCollapsed" points="12 19 5 12 12 5"></polyline>
          <line v-else x1="5" y1="12" x2="19" y2="12"></line>
          <polyline v-else points="12 5 19 12 12 19"></polyline>
        </svg>
      </button>
    </div>

    <nav class="sidebar-nav">
      <ul class="nav-list">
        <!-- Dashboard -->
        <li class="nav-item">
          <router-link to="/" class="nav-link" :class="{ active: $route.path === '/' }">
            <svg class="nav-icon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <rect x="3" y="3" width="7" height="7"></rect>
              <rect x="14" y="3" width="7" height="7"></rect>
              <rect x="14" y="14" width="7" height="7"></rect>
              <rect x="3" y="14" width="7" height="7"></rect>
            </svg>
            <span v-if="!isCollapsed" class="nav-text">Dashboard</span>
          </router-link>
        </li>

        <!-- Conteúdo -->
        <li class="nav-category">
          <div class="category-header" @click="toggleCategory('conteudo')">
            <div class="category-title">
              <svg class="nav-icon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path>
                <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path>
              </svg>
              <span v-if="!isCollapsed" class="nav-text">Conteúdo</span>
            </div>
            <svg v-if="!isCollapsed" class="chevron" :class="{ rotated: expandedCategories.includes('conteudo') }" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <polyline points="6 9 12 15 18 9"></polyline>
            </svg>
          </div>
          
          <ul v-if="!isCollapsed" class="submenu" :class="{ expanded: expandedCategories.includes('conteudo') }">
            <li class="submenu-item">
              <router-link to="/materias" class="nav-link" :class="{ active: $route.path.startsWith('/materias') }">
                <svg class="nav-icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                  <polyline points="14 2 14 8 20 8"></polyline>
                  <line x1="16" y1="13" x2="8" y2="13"></line>
                  <line x1="16" y1="17" x2="8" y2="17"></line>
                  <polyline points="10 9 9 9 8 9"></polyline>
                </svg>
                <span class="nav-text">Matérias</span>
              </router-link>
            </li>
            <li class="submenu-item">
              <router-link to="/areas" class="nav-link" :class="{ active: $route.path.startsWith('/areas') }">
                <svg class="nav-icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"></path>
                </svg>
                <span class="nav-text">Áreas</span>
              </router-link>
            </li>
            <li class="submenu-item">
              <router-link to="/eventos" class="nav-link" :class="{ active: $route.path.startsWith('/eventos') }">
                <svg class="nav-icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                  <line x1="16" y1="2" x2="16" y2="6"></line>
                  <line x1="8" y1="2" x2="8" y2="6"></line>
                  <line x1="3" y1="10" x2="21" y2="10"></line>
                </svg>
                <span class="nav-text">Eventos GEEK</span>
              </router-link>
            </li>
          </ul>
        </li>

        <!-- Usuários -->
        <li class="nav-category">
          <div class="category-header" @click="toggleCategory('usuarios')">
            <div class="category-title">
              <svg class="nav-icon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                <circle cx="9" cy="7" r="4"></circle>
                <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
              </svg>
              <span v-if="!isCollapsed" class="nav-text">Usuários</span>
            </div>
            <svg v-if="!isCollapsed" class="chevron" :class="{ rotated: expandedCategories.includes('usuarios') }" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <polyline points="6 9 12 15 18 9"></polyline>
            </svg>
          </div>
          
          <ul v-if="!isCollapsed" class="submenu" :class="{ expanded: expandedCategories.includes('usuarios') }">
            <li class="submenu-item">
              <router-link to="/writers" class="nav-link" :class="{ active: $route.path.startsWith('/writers') }">
                <svg class="nav-icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M12 20h9"></path>
                  <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path>
                </svg>
                <span class="nav-text">Editores</span>
              </router-link>
            </li>
          </ul>
        </li>
      </ul>
    </nav>

    <div class="sidebar-footer">
      <div class="user-info">
        <div class="user-avatar">
          <span>{{ userInitials }}</span>
        </div>
        <div v-if="!isCollapsed" class="user-details">
          <div class="user-name">{{ userName }}</div>
          <button class="logout-btn" @click="handleLogout">
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
              <polyline points="16 17 21 12 16 7"></polyline>
              <line x1="21" y1="12" x2="9" y2="12"></line>
            </svg>
            Sair
          </button>
        </div>
      </div>
    </div>
  </aside>
</template>

<script>
export default {
  name: 'SidebarMenu',
  data() {
    return {
      isCollapsed: false,
      expandedCategories: ['conteudo'], // Categorias abertas por padrão
      userName: 'Admin User',
    };
  },
  computed: {
    userInitials() {
      return this.userName
        .split(' ')
        .map(n => n[0])
        .join('')
        .toUpperCase()
        .substring(0, 2);
    },
  },
  mounted() {
    // Carregar estado do localStorage
    const collapsed = localStorage.getItem('sidebar-collapsed');
    if (collapsed !== null) {
      this.isCollapsed = collapsed === 'true';
    }

    const expanded = localStorage.getItem('sidebar-expanded-categories');
    if (expanded) {
      try {
        this.expandedCategories = JSON.parse(expanded);
      } catch (e) {
        console.error('Erro ao carregar categorias expandidas:', e);
      }
    }

    // Carregar nome do usuário
    const user = localStorage.getItem('user');
    if (user) {
      try {
        const userData = JSON.parse(user);
        this.userName = userData.name || 'Admin User';
      } catch (e) {
        console.error('Erro ao carregar dados do usuário:', e);
      }
    }
  },
  methods: {
    toggleCollapse() {
      this.isCollapsed = !this.isCollapsed;
      localStorage.setItem('sidebar-collapsed', this.isCollapsed.toString());
    },
    toggleCategory(category) {
      if (this.isCollapsed) {
        // Se sidebar está recolhido, expande primeiro
        this.isCollapsed = false;
        localStorage.setItem('sidebar-collapsed', 'false');
      }

      const index = this.expandedCategories.indexOf(category);
      if (index > -1) {
        this.expandedCategories.splice(index, 1);
      } else {
        this.expandedCategories.push(category);
      }
      localStorage.setItem('sidebar-expanded-categories', JSON.stringify(this.expandedCategories));
    },
    handleLogout() {
      if (confirm('Deseja realmente sair?')) {
        localStorage.removeItem('token');
        localStorage.removeItem('user');
        this.$router.push('/login');
      }
    },
  },
};
</script>

<style scoped>
.sidebar {
  width: 260px;
  background: #1e293b;
  color: #e2e8f0;
  display: flex;
  flex-direction: column;
  height: 100vh;
  position: fixed;
  left: 0;
  top: 0;
  transition: width 0.3s ease;
  z-index: 1000;
  box-shadow: 2px 0 8px rgba(0, 0, 0, 0.1);
}

.sidebar.collapsed {
  width: 70px;
}

/* Header */
.sidebar-header {
  padding: 20px 16px;
  border-bottom: 1px solid rgba(255, 255, 255, 0.1);
  display: flex;
  align-items: center;
  justify-content: space-between;
  min-height: 70px;
}

.logo {
  display: flex;
  align-items: center;
  font-size: 20px;
  font-weight: 700;
  color: #3b82f6;
}

.logo img {
  max-height: 35px;
  width: auto;
}

.logo-icon {
  width: 38px;
  height: 38px;
  display: flex;
  align-items: center;
  justify-content: center;
  background: #3b82f6;
  border-radius: 8px;
  font-weight: 700;
  font-size: 18px;
  color: white;
}

.collapse-btn {
  background: transparent;
  border: none;
  color: #94a3b8;
  cursor: pointer;
  padding: 6px;
  border-radius: 6px;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: all 0.2s;
}

.collapse-btn:hover {
  background: rgba(255, 255, 255, 0.1);
  color: #e2e8f0;
}

.sidebar.collapsed .collapse-btn {
  position: absolute;
  right: 8px;
  top: 20px;
}

/* Navigation */
.sidebar-nav {
  flex: 1;
  overflow-y: auto;
  overflow-x: hidden;
  padding: 16px 0;
}

.sidebar-nav::-webkit-scrollbar {
  width: 6px;
}

.sidebar-nav::-webkit-scrollbar-track {
  background: transparent;
}

.sidebar-nav::-webkit-scrollbar-thumb {
  background: rgba(255, 255, 255, 0.1);
  border-radius: 3px;
}

.sidebar-nav::-webkit-scrollbar-thumb:hover {
  background: rgba(255, 255, 255, 0.2);
}

.nav-list {
  list-style: none;
  padding: 0;
  margin: 0;
}

.nav-item,
.nav-category {
  margin: 4px 12px;
}

.nav-link {
  display: flex;
  align-items: center;
  padding: 12px 14px;
  color: #94a3b8;
  text-decoration: none;
  border-radius: 8px;
  transition: all 0.2s;
  gap: 12px;
}

.nav-link:hover {
  background: rgba(255, 255, 255, 0.05);
  color: #e2e8f0;
}

.nav-link.active {
  background: #3b82f6;
  color: white;
}

.nav-icon {
  min-width: 20px;
  flex-shrink: 0;
}

.nav-text {
  font-size: 14px;
  font-weight: 500;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}

/* Categories */
.category-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 12px 14px;
  cursor: pointer;
  border-radius: 8px;
  transition: all 0.2s;
  color: #94a3b8;
}

.category-header:hover {
  background: rgba(255, 255, 255, 0.05);
  color: #e2e8f0;
}

.category-title {
  display: flex;
  align-items: center;
  gap: 12px;
}

.chevron {
  transition: transform 0.3s ease;
  flex-shrink: 0;
}

.chevron.rotated {
  transform: rotate(180deg);
}

.submenu {
  list-style: none;
  padding: 0;
  margin: 0;
  max-height: 0;
  overflow: hidden;
  transition: max-height 0.3s ease;
}

.submenu.expanded {
  max-height: 500px;
}

.submenu-item {
  margin: 2px 0 2px 20px;
}

.submenu-item .nav-link {
  padding: 10px 14px;
  font-size: 13px;
}

.sidebar.collapsed .submenu {
  display: none;
}

.sidebar.collapsed .category-header {
  justify-content: center;
}

.sidebar.collapsed .chevron {
  display: none;
}

/* Footer */
.sidebar-footer {
  padding: 16px;
  border-top: 1px solid rgba(255, 255, 255, 0.1);
}

.user-info {
  display: flex;
  align-items: center;
  gap: 12px;
}

.user-avatar {
  width: 38px;
  height: 38px;
  border-radius: 50%;
  background: #3b82f6;
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: 600;
  font-size: 14px;
  color: white;
  flex-shrink: 0;
}

.user-details {
  flex: 1;
  min-width: 0;
}

.user-name {
  font-size: 14px;
  font-weight: 600;
  color: #e2e8f0;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
  margin-bottom: 4px;
}

.logout-btn {
  background: transparent;
  border: none;
  color: #94a3b8;
  font-size: 12px;
  cursor: pointer;
  padding: 0;
  display: flex;
  align-items: center;
  gap: 6px;
  transition: color 0.2s;
}

.logout-btn:hover {
  color: #ef4444;
}

.sidebar.collapsed .user-details {
  display: none;
}

/* Responsive */
@media (max-width: 768px) {
  .sidebar:not(.collapsed) {
    width: 260px;
  }
  
  .sidebar.collapsed {
    width: 0;
    padding: 0;
  }
}
</style>
