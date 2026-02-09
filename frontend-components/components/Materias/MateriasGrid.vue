<!-- components/Materias/MateriasGrid.vue -->
<template>
  <div class="materias-grid-container">
    <!-- Header -->
    <div class="grid-header">
      <h2 class="page-title">Gerenciamento de Matérias</h2>
      <button class="btn btn-primary" @click="showCreateDialog">
        <i class="fas fa-plus"></i> Nova Matéria
      </button>
    </div>

    <!-- Filtros -->
    <div class="filters-section">
      <div class="filter-group">
        <label>Buscar:</label>
        <input
          v-model="filters.search"
          type="text"
          placeholder="Título da matéria..."
          class="form-control"
          @input="debounceSearch"
        />
      </div>

      <div class="filter-group">
        <label>Editor:</label>
        <select v-model="filters.editor" class="form-control" @change="loadMaterias">
          <option value="">Todos</option>
          <option v-for="writer in writers" :key="writer.id" :value="writer.id">
            {{ writer.name }}
          </option>
        </select>
      </div>

      <div class="filter-group">
        <label>Status:</label>
        <select v-model="filters.onLine" class="form-control" @change="loadMaterias">
          <option :value="null">Todos</option>
          <option :value="true">Online</option>
          <option :value="false">Offline</option>
        </select>
      </div>

      <button class="btn btn-secondary" @click="clearFilters">
        <i class="fas fa-times"></i> Limpar
      </button>
    </div>

    <!-- Loading -->
    <div v-if="loading" class="loading-overlay">
      <div class="spinner"></div>
    </div>

    <!-- Grid de Matérias -->
    <div v-else class="materias-table-wrapper">
      <table class="materias-table">
        <thead>
          <tr>
            <th width="60">ID</th>
            <th width="150">Editor</th>
            <th>Título</th>
            <th width="150">Data Criação</th>
            <th width="100" class="text-center">Status</th>
            <th width="200" class="text-center">Ações</th>
          </tr>
        </thead>
        <tbody>
          <tr v-if="materias.length === 0">
            <td colspan="6" class="text-center no-data">
              Nenhuma matéria encontrada
            </td>
          </tr>
          <tr v-for="materia in materias" :key="materia.id" class="materia-row">
            <td>{{ materia.id }}</td>
            <td>{{ materia.vchr_autor || 'N/A' }}</td>
            <td>
              <a :href="`/materias/${materia.id}`" class="materia-title">
                {{ materia.vchr_titulo }}
              </a>
            </td>
            <td>{{ formatDate(materia.created_at) }}</td>
            <td class="text-center">
              <span :class="['badge', materia.bool_onLine ? 'badge-success' : 'badge-danger']">
                {{ materia.bool_onLine ? 'Online' : 'Offline' }}
              </span>
            </td>
            <td class="text-center actions-cell">
              <button
                class="btn-action btn-edit"
                @click="editMateria(materia)"
                title="Editar"
              >
                <i class="fas fa-edit"></i>
              </button>
              <button
                :class="['btn-action', materia.bool_onLine ? 'btn-offline' : 'btn-online']"
                @click="toggleOnline(materia)"
                :title="materia.bool_onLine ? 'Colocar Offline' : 'Colocar Online'"
              >
                <i :class="['fas', materia.bool_onLine ? 'fa-eye-slash' : 'fa-eye']"></i>
              </button>
              <button
                class="btn-action btn-images"
                @click="openImagesDialog(materia)"
                title="Imagens"
              >
                <i class="fas fa-images"></i>
              </button>
              <button
                class="btn-action btn-delete"
                @click="deleteMateria(materia)"
                title="Excluir"
              >
                <i class="fas fa-trash"></i>
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Paginação -->
    <div class="pagination-wrapper">
      <div class="pagination-info">
        Mostrando {{ (currentPage - 1) * perPage + 1 }} até {{ Math.min(currentPage * perPage, totalItems) }} de {{ totalItems }} itens
      </div>
      <div class="pagination-controls">
        <button
          class="btn btn-sm"
          :disabled="currentPage === 1"
          @click="changePage(currentPage - 1)"
        >
          <i class="fas fa-chevron-left"></i>
        </button>
        <span class="page-number">Página {{ currentPage }} de {{ totalPages }}</span>
        <button
          class="btn btn-sm"
          :disabled="currentPage === totalPages"
          @click="changePage(currentPage + 1)"
        >
          <i class="fas fa-chevron-right"></i>
        </button>
      </div>
      <div class="pagination-size">
        <label>Itens por página:</label>
        <select v-model="perPage" @change="changePerPage" class="form-control-sm">
          <option :value="10">10</option>
          <option :value="25">25</option>
          <option :value="50">50</option>
          <option :value="100">100</option>
        </select>
      </div>
    </div>

    <!-- Dialog de Imagens -->
    <ImagesDialog
      v-if="showImagesDialog"
      :materia="selectedMateria"
      @close="showImagesDialog = false"
      @refresh="loadMaterias"
    />
  </div>
</template>

<script>
import materiasService from '@/services/materiasService';
import ImagesDialog from './ImagesDialog.vue';

export default {
  name: 'MateriasGrid',
  components: {
    ImagesDialog,
  },
  data() {
    return {
      materias: [],
      writers: [],
      loading: false,
      filters: {
        search: '',
        editor: '',
        onLine: null,
      },
      currentPage: 1,
      perPage: 10,
      totalItems: 0,
      totalPages: 0,
      searchTimeout: null,
      showImagesDialog: false,
      selectedMateria: null,
    };
  },
  mounted() {
    this.loadMaterias();
    this.loadWriters();
  },
  methods: {
    async loadMaterias() {
      this.loading = true;
      try {
        const response = await materiasService.getMaterias({
          page: this.currentPage,
          perPage: this.perPage,
          search: this.filters.search,
          editor: this.filters.editor,
          onLine: this.filters.onLine,
        });

        this.materias = response.data || response;
        this.totalItems = response.total || this.materias.length;
        this.totalPages = Math.ceil(this.totalItems / this.perPage);
      } catch (error) {
        console.error('Erro ao carregar matérias:', error);
        this.$toast.error('Erro ao carregar matérias');
      } finally {
        this.loading = false;
      }
    },

    async loadWriters() {
      try {
        this.writers = await materiasService.getWriters();
      } catch (error) {
        console.error('Erro ao carregar editores:', error);
      }
    },

    debounceSearch() {
      clearTimeout(this.searchTimeout);
      this.searchTimeout = setTimeout(() => {
        this.currentPage = 1;
        this.loadMaterias();
      }, 500);
    },

    clearFilters() {
      this.filters = {
        search: '',
        editor: '',
        onLine: null,
      };
      this.currentPage = 1;
      this.loadMaterias();
    },

    changePage(page) {
      this.currentPage = page;
      this.loadMaterias();
    },

    changePerPage() {
      this.currentPage = 1;
      this.loadMaterias();
    },

    showCreateDialog() {
      this.$router.push('/materias/create');
    },

    editMateria(materia) {
      this.$router.push(`/materias/edit/${materia.id}`);
    },

    async toggleOnline(materia) {
      try {
        await materiasService.toggleOnline(materia.id, !materia.bool_onLine);
        this.$toast.success(`Matéria ${!materia.bool_onLine ? 'publicada' : 'despublicada'} com sucesso`);
        this.loadMaterias();
      } catch (error) {
        console.error('Erro ao alterar status:', error);
        this.$toast.error('Erro ao alterar status da matéria');
      }
    },

    openImagesDialog(materia) {
      this.selectedMateria = materia;
      this.showImagesDialog = true;
    },

    async deleteMateria(materia) {
      if (!confirm(`Tem certeza que deseja excluir a matéria "${materia.vchr_titulo}"?`)) {
        return;
      }

      try {
        await materiasService.deleteMateria(materia.id);
        this.$toast.success('Matéria excluída com sucesso');
        this.loadMaterias();
      } catch (error) {
        console.error('Erro ao excluir matéria:', error);
        this.$toast.error('Erro ao excluir matéria');
      }
    },

    formatDate(dateString) {
      if (!dateString) return 'N/A';
      const date = new Date(dateString);
      return date.toLocaleDateString('pt-BR', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
      });
    },
  },
};
</script>

<style scoped>
.materias-grid-container {
  padding: 24px;
  background: #f5f7fa;
  min-height: 100vh;
}

.grid-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 24px;
}

.page-title {
  font-size: 28px;
  font-weight: 600;
  color: #2c3e50;
  margin: 0;
}

.filters-section {
  display: flex;
  gap: 16px;
  margin-bottom: 24px;
  padding: 20px;
  background: white;
  border-radius: 8px;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
  flex-wrap: wrap;
}

.filter-group {
  display: flex;
  flex-direction: column;
  gap: 8px;
  flex: 1;
  min-width: 200px;
}

.filter-group label {
  font-weight: 600;
  font-size: 14px;
  color: #4a5568;
}

.form-control {
  padding: 8px 12px;
  border: 1px solid #e2e8f0;
  border-radius: 6px;
  font-size: 14px;
  transition: all 0.2s;
}

.form-control:focus {
  outline: none;
  border-color: #4299e1;
  box-shadow: 0 0 0 3px rgba(66, 153, 225, 0.1);
}

.loading-overlay {
  display: flex;
  justify-content: center;
  align-items: center;
  min-height: 400px;
}

.spinner {
  width: 50px;
  height: 50px;
  border: 4px solid #f3f3f3;
  border-top: 4px solid #3498db;
  border-radius: 50%;
  animation: spin 1s linear infinite;
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}

.materias-table-wrapper {
  background: white;
  border-radius: 8px;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
  overflow: hidden;
}

.materias-table {
  width: 100%;
  border-collapse: collapse;
}

.materias-table thead {
  background: #f7fafc;
}

.materias-table th {
  padding: 16px;
  text-align: left;
  font-weight: 600;
  font-size: 14px;
  color: #4a5568;
  text-transform: uppercase;
  letter-spacing: 0.5px;
  border-bottom: 2px solid #e2e8f0;
}

.materias-table tbody tr {
  border-bottom: 1px solid #e2e8f0;
  transition: background-color 0.2s;
}

.materias-table tbody tr:hover {
  background-color: #f7fafc;
}

.materias-table td {
  padding: 16px;
  font-size: 14px;
  color: #2d3748;
}

.materia-title {
  color: #4299e1;
  text-decoration: none;
  font-weight: 500;
  transition: color 0.2s;
}

.materia-title:hover {
  color: #2b6cb0;
  text-decoration: underline;
}

.text-center {
  text-align: center;
}

.no-data {
  padding: 48px;
  color: #a0aec0;
  font-style: italic;
}

.badge {
  display: inline-block;
  padding: 4px 12px;
  border-radius: 12px;
  font-size: 12px;
  font-weight: 600;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

.badge-success {
  background-color: #c6f6d5;
  color: #22543d;
}

.badge-danger {
  background-color: #fed7d7;
  color: #742a2a;
}

.actions-cell {
  display: flex;
  gap: 8px;
  justify-content: center;
  align-items: center;
}

.btn-action {
  padding: 8px 12px;
  border: none;
  border-radius: 6px;
  cursor: pointer;
  transition: all 0.2s;
  background: transparent;
  color: #4a5568;
}

.btn-action:hover {
  transform: translateY(-2px);
}

.btn-edit {
  color: #4299e1;
}

.btn-edit:hover {
  background-color: #ebf8ff;
}

.btn-online {
  color: #48bb78;
}

.btn-online:hover {
  background-color: #f0fff4;
}

.btn-offline {
  color: #f56565;
}

.btn-offline:hover {
  background-color: #fff5f5;
}

.btn-images {
  color: #9f7aea;
}

.btn-images:hover {
  background-color: #faf5ff;
}

.btn-delete {
  color: #f56565;
}

.btn-delete:hover {
  background-color: #fff5f5;
}

.pagination-wrapper {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-top: 24px;
  padding: 16px 20px;
  background: white;
  border-radius: 8px;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
}

.pagination-info {
  font-size: 14px;
  color: #4a5568;
}

.pagination-controls {
  display: flex;
  align-items: center;
  gap: 16px;
}

.page-number {
  font-weight: 600;
  color: #2d3748;
}

.pagination-size {
  display: flex;
  align-items: center;
  gap: 8px;
}

.pagination-size label {
  font-size: 14px;
  color: #4a5568;
}

.form-control-sm {
  padding: 6px 10px;
  border: 1px solid #e2e8f0;
  border-radius: 6px;
  font-size: 14px;
}

.btn {
  padding: 10px 20px;
  border: none;
  border-radius: 6px;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.2s;
  display: inline-flex;
  align-items: center;
  gap: 8px;
}

.btn-primary {
  background-color: #4299e1;
  color: white;
}

.btn-primary:hover {
  background-color: #3182ce;
  transform: translateY(-2px);
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.btn-secondary {
  background-color: #718096;
  color: white;
}

.btn-secondary:hover {
  background-color: #4a5568;
}

.btn-sm {
  padding: 6px 12px;
  font-size: 14px;
  background: #e2e8f0;
  color: #2d3748;
}

.btn-sm:hover:not(:disabled) {
  background: #cbd5e0;
}

.btn-sm:disabled {
  opacity: 0.5;
  cursor: not-allowed;
}
</style>
