<!-- components/Eventos/EventosGrid.vue -->
<template>
  <div class="eventos-grid-container">
    <!-- Header -->
    <div class="grid-header">
      <h2 class="page-title">Gerenciamento de Eventos GEEK</h2>
      <button class="btn btn-primary" @click="openCreateDialog">
        <i class="fas fa-plus"></i> Novo Evento
      </button>
    </div>

    <!-- Filtros -->
    <div class="filters-section">
      <div class="filter-group">
        <label>Buscar por Cidade:</label>
        <input
          v-model="filters.cidade"
          type="text"
          placeholder="Nome da cidade..."
          class="form-control"
          @input="debounceSearch"
        />
      </div>

      <div class="filter-group">
        <label>Data:</label>
        <input
          v-model="filters.data"
          type="date"
          class="form-control"
          @change="loadEventos"
        />
      </div>

      <div class="filter-group">
        <label>Tipo de Atração:</label>
        <select v-model="filters.tipo_atracao" class="form-control" @change="loadEventos">
          <option value="">Todos</option>
          <option value="1">Banda</option>
          <option value="2">Dublador</option>
          <option value="3">Cantor</option>
          <option value="4">Ator</option>
          <option value="5">Celebridade</option>
          <option value="6">Food Truck</option>
          <option value="7">Expositores</option>
          <option value="8">Artist Alley</option>
          <option value="9">Painel</option>
          <option value="10">Estréia</option>
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

    <!-- Grid de Eventos -->
    <div v-else class="eventos-table-wrapper">
      <table class="eventos-table">
        <thead>
          <tr>
            <th width="60">ID</th>
            <th>Nome</th>
            <th width="150">Realização</th>
            <th width="120">Cidade</th>
            <th width="200" class="text-center">Ações</th>
          </tr>
        </thead>
        <tbody>
          <tr v-if="eventos.length === 0">
            <td colspan="5" class="text-center no-data">
              Nenhum evento encontrado
            </td>
          </tr>
          <tr v-for="evento in eventos" :key="evento.id" class="evento-row">
            <td>{{ evento.id }}</td>
            <td>
              <div class="evento-info">
                <strong>{{ evento.nome }}</strong>
                <span class="evento-desc">{{ truncate(evento.descricao, 80) }}</span>
              </div>
            </td>
            <td>{{ formatDateTime(evento.realizacao) }}</td>
            <td>
              <span v-if="evento.agendas && evento.agendas.length > 0">
                {{ evento.agendas[0].cidade }}
                <span v-if="evento.agendas.length > 1" class="badge-info">+{{ evento.agendas.length - 1 }}</span>
              </span>
              <span v-else class="text-muted">N/A</span>
            </td>
            <td class="text-center actions-cell">
              <button
                class="btn-action btn-view"
                @click="viewEvento(evento)"
                title="Visualizar"
              >
                <i class="fas fa-eye"></i>
              </button>
              <button
                class="btn-action btn-edit"
                @click="editEvento(evento)"
                title="Editar"
              >
                <i class="fas fa-edit"></i>
              </button>
              <button
                class="btn-action btn-delete"
                @click="deleteEvento(evento)"
                title="Excluir"
              >
                <i class="fas fa-trash"></i>
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Modal de Formulário -->
    <v-dialog v-model="showFormDialog" max-width="900px" persistent>
      <v-card>
        <v-card-title class="dialog-header">
          <span class="text-h5">{{ isEditing ? 'Editar Evento' : 'Novo Evento' }}</span>
          <v-btn icon @click="closeFormDialog">
            <v-icon>mdi-close</v-icon>
          </v-btn>
        </v-card-title>
        
        <v-card-text>
          <v-form ref="form" v-model="formValid">
            <v-row>
              <v-col cols="12">
                <v-text-field
                  v-model="formData.nome"
                  label="Nome do Evento *"
                  :rules="[rules.required]"
                  outlined
                  dense
                ></v-text-field>
              </v-col>

              <v-col cols="12">
                <v-textarea
                  v-model="formData.descricao"
                  label="Descrição *"
                  :rules="[rules.required]"
                  outlined
                  dense
                  rows="3"
                ></v-textarea>
              </v-col>

              <v-col cols="12" md="6">
                <v-text-field
                  v-model="formData.realizacao"
                  label="Data/Hora de Realização"
                  type="datetime-local"
                  outlined
                  dense
                ></v-text-field>
              </v-col>

              <v-col cols="12" md="6">
                <v-text-field
                  v-model="formData.link_foto"
                  label="Link da Foto"
                  outlined
                  dense
                  hint="URL da imagem principal"
                ></v-text-field>
              </v-col>

              <v-col cols="12" md="6">
                <v-text-field
                  v-model="formData.link_logo"
                  label="Link do Logo"
                  outlined
                  dense
                ></v-text-field>
              </v-col>

              <v-col cols="12" md="6">
                <v-text-field
                  v-model="formData.link_site"
                  label="Site Oficial"
                  outlined
                  dense
                ></v-text-field>
              </v-col>

              <v-col cols="12" md="6">
                <v-text-field
                  v-model="formData.link_instagram"
                  label="Instagram"
                  outlined
                  dense
                  prepend-inner-icon="mdi-instagram"
                ></v-text-field>
              </v-col>

              <v-col cols="12" md="6">
                <v-text-field
                  v-model="formData.link_video"
                  label="Link do Vídeo"
                  outlined
                  dense
                  prepend-inner-icon="mdi-youtube"
                ></v-text-field>
              </v-col>

              <v-col cols="12" md="6">
                <v-text-field
                  v-model="formData.link_x"
                  label="X (Twitter)"
                  outlined
                  dense
                  prepend-inner-icon="mdi-twitter"
                ></v-text-field>
              </v-col>

              <v-col cols="12" md="6">
                <v-text-field
                  v-model="formData.link_tiktok"
                  label="TikTok"
                  outlined
                  dense
                ></v-text-field>
              </v-col>

              <!-- Agendas (Datas e Locais) -->
              <v-col cols="12">
                <v-divider class="my-3"></v-divider>
                <h3 class="section-title">
                  Agenda do Evento
                  <v-btn icon small color="primary" @click="addAgenda">
                    <v-icon>mdi-plus</v-icon>
                  </v-btn>
                </h3>
              </v-col>

              <v-col cols="12" v-for="(agenda, index) in formData.agendas" :key="index">
                <v-card outlined class="agenda-card">
                  <v-card-text>
                    <v-row align="center">
                      <v-col cols="12" md="3">
                        <v-text-field
                          v-model="agenda.data"
                          label="Data *"
                          type="date"
                          :rules="[rules.required]"
                          outlined
                          dense
                        ></v-text-field>
                      </v-col>
                      <v-col cols="12" md="5">
                        <v-text-field
                          v-model="agenda.endereco"
                          label="Endereço *"
                          :rules="[rules.required]"
                          outlined
                          dense
                        ></v-text-field>
                      </v-col>
                      <v-col cols="12" md="3">
                        <v-text-field
                          v-model="agenda.cidade"
                          label="Cidade *"
                          :rules="[rules.required]"
                          outlined
                          dense
                        ></v-text-field>
                      </v-col>
                      <v-col cols="12" md="1">
                        <v-btn icon color="error" @click="removeAgenda(index)">
                          <v-icon>mdi-delete</v-icon>
                        </v-btn>
                      </v-col>
                    </v-row>
                  </v-card-text>
                </v-card>
              </v-col>

              <!-- Atrações -->
              <v-col cols="12">
                <v-divider class="my-3"></v-divider>
                <h3 class="section-title">Atrações</h3>
              </v-col>

              <v-col cols="12">
                <v-select
                  v-model="formData.atracoes"
                  :items="atracoesList"
                  item-title="nome"
                  item-value="id"
                  label="Selecione as Atrações"
                  multiple
                  chips
                  outlined
                  dense
                  :loading="loadingAtracoes"
                ></v-select>
              </v-col>

              <!-- Concursos -->
              <v-col cols="12">
                <v-divider class="my-3"></v-divider>
                <h3 class="section-title">Concursos</h3>
              </v-col>

              <v-col cols="12">
                <v-select
                  v-model="formData.concursos"
                  :items="concursosList"
                  item-title="nome"
                  item-value="id"
                  label="Selecione os Concursos"
                  multiple
                  chips
                  outlined
                  dense
                  :loading="loadingConcursos"
                ></v-select>
              </v-col>
            </v-row>
          </v-form>
        </v-card-text>

        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn text @click="closeFormDialog">Cancelar</v-btn>
          <v-btn color="primary" :loading="saving" @click="saveEvento">
            {{ isEditing ? 'Atualizar' : 'Criar' }}
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>

    <!-- Modal de Visualização -->
    <v-dialog v-model="showViewDialog" max-width="800px">
      <v-card v-if="selectedEvento">
        <v-card-title class="dialog-header">
          <span class="text-h5">{{ selectedEvento.nome }}</span>
          <v-btn icon @click="showViewDialog = false">
            <v-icon>mdi-close</v-icon>
          </v-btn>
        </v-card-title>
        
        <v-card-text class="view-content">
          <div v-if="selectedEvento.link_foto" class="evento-image">
            <img :src="selectedEvento.link_foto" :alt="selectedEvento.nome" />
          </div>

          <div class="info-section">
            <h4>Descrição</h4>
            <p>{{ selectedEvento.descricao }}</p>
          </div>

          <div v-if="selectedEvento.realizacao" class="info-section">
            <h4>Data de Realização</h4>
            <p>{{ formatDateTime(selectedEvento.realizacao) }}</p>
          </div>

          <div v-if="selectedEvento.agendas && selectedEvento.agendas.length" class="info-section">
            <h4>Agenda</h4>
            <div v-for="agenda in selectedEvento.agendas" :key="agenda.id" class="agenda-item">
              <strong>{{ formatDate(agenda.data) }}</strong> - {{ agenda.endereco }}, {{ agenda.cidade }}
            </div>
          </div>

          <div v-if="selectedEvento.atracoes && selectedEvento.atracoes.length" class="info-section">
            <h4>Atrações</h4>
            <v-chip
              v-for="atracao in selectedEvento.atracoes"
              :key="atracao.id"
              class="ma-1"
              small
            >
              {{ atracao.nome }}
            </v-chip>
          </div>

          <div v-if="selectedEvento.concursos && selectedEvento.concursos.length" class="info-section">
            <h4>Concursos</h4>
            <v-chip
              v-for="concurso in selectedEvento.concursos"
              :key="concurso.id"
              class="ma-1"
              small
              color="primary"
            >
              {{ concurso.nome }}
            </v-chip>
          </div>

          <div class="info-section links-section">
            <h4>Links</h4>
            <div class="social-links">
              <a v-if="selectedEvento.link_site" :href="selectedEvento.link_site" target="_blank" class="social-link">
                <v-icon>mdi-web</v-icon> Site
              </a>
              <a v-if="selectedEvento.link_instagram" :href="selectedEvento.link_instagram" target="_blank" class="social-link">
                <v-icon>mdi-instagram</v-icon> Instagram
              </a>
              <a v-if="selectedEvento.link_video" :href="selectedEvento.link_video" target="_blank" class="social-link">
                <v-icon>mdi-youtube</v-icon> Vídeo
              </a>
              <a v-if="selectedEvento.link_x" :href="selectedEvento.link_x" target="_blank" class="social-link">
                <v-icon>mdi-twitter</v-icon> X
              </a>
              <a v-if="selectedEvento.link_tiktok" :href="selectedEvento.link_tiktok" target="_blank" class="social-link">
                <v-icon>mdi-music-note</v-icon> TikTok
              </a>
            </div>
          </div>
        </v-card-text>

        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn text @click="showViewDialog = false">Fechar</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </div>
</template>

<script>
import eventosService from '@/services/eventosService';

export default {
  name: 'EventosGrid',
  data() {
    return {
      eventos: [],
      atracoesList: [],
      concursosList: [],
      loading: false,
      loadingAtracoes: false,
      loadingConcursos: false,
      saving: false,
      filters: {
        cidade: '',
        data: '',
        tipo_atracao: '',
      },
      searchTimeout: null,
      showFormDialog: false,
      showViewDialog: false,
      selectedEvento: null,
      isEditing: false,
      formValid: false,
      formData: {
        nome: '',
        descricao: '',
        realizacao: '',
        link_foto: '',
        link_logo: '',
        link_site: '',
        link_instagram: '',
        link_video: '',
        link_x: '',
        link_tiktok: '',
        agendas: [],
        atracoes: [],
        concursos: [],
      },
      rules: {
        required: value => !!value || 'Campo obrigatório',
      },
    };
  },
  mounted() {
    this.loadEventos();
    this.loadAtracoes();
    this.loadConcursos();
  },
  methods: {
    async loadEventos() {
      this.loading = true;
      try {
        const response = await eventosService.getEventos(this.filters);
        this.eventos = Array.isArray(response) ? response : response.data || [];
      } catch (error) {
        console.error('Erro ao carregar eventos:', error);
        this.$toast?.error('Erro ao carregar eventos');
      } finally {
        this.loading = false;
      }
    },

    async loadAtracoes() {
      this.loadingAtracoes = true;
      try {
        const response = await eventosService.getAtracoes();
        this.atracoesList = Array.isArray(response) ? response : response.data || [];
      } catch (error) {
        console.error('Erro ao carregar atrações:', error);
      } finally {
        this.loadingAtracoes = false;
      }
    },

    async loadConcursos() {
      this.loadingConcursos = true;
      try {
        const response = await eventosService.getConcursos();
        this.concursosList = Array.isArray(response) ? response : response.data || [];
      } catch (error) {
        console.error('Erro ao carregar concursos:', error);
      } finally {
        this.loadingConcursos = false;
      }
    },

    debounceSearch() {
      clearTimeout(this.searchTimeout);
      this.searchTimeout = setTimeout(() => {
        this.loadEventos();
      }, 500);
    },

    clearFilters() {
      this.filters = {
        cidade: '',
        data: '',
        tipo_atracao: '',
      };
      this.loadEventos();
    },

    openCreateDialog() {
      this.resetForm();
      this.isEditing = false;
      this.showFormDialog = true;
    },

    editEvento(evento) {
      this.isEditing = true;
      this.formData = {
        ...evento,
        realizacao: evento.realizacao ? this.formatDateTimeForInput(evento.realizacao) : '',
        agendas: evento.agendas ? evento.agendas.map(a => ({
          data: a.data,
          endereco: a.endereco,
          cidade: a.cidade,
        })) : [],
        atracoes: evento.atracoes ? evento.atracoes.map(a => a.id) : [],
        concursos: evento.concursos ? evento.concursos.map(c => c.id) : [],
      };
      this.showFormDialog = true;
    },

    viewEvento(evento) {
      this.selectedEvento = evento;
      this.showViewDialog = true;
    },

    async deleteEvento(evento) {
      if (!confirm(`Tem certeza que deseja excluir o evento "${evento.nome}"?`)) {
        return;
      }

      try {
        await eventosService.deleteEvento(evento.id);
        this.$toast?.success('Evento excluído com sucesso');
        this.loadEventos();
      } catch (error) {
        console.error('Erro ao excluir evento:', error);
        this.$toast?.error('Erro ao excluir evento');
      }
    },

    async saveEvento() {
      if (!this.$refs.form.validate()) {
        return;
      }

      this.saving = true;
      try {
        if (this.isEditing) {
          await eventosService.updateEvento(this.formData.id, this.formData);
          this.$toast?.success('Evento atualizado com sucesso');
        } else {
          await eventosService.createEvento(this.formData);
          this.$toast?.success('Evento criado com sucesso');
        }
        this.closeFormDialog();
        this.loadEventos();
      } catch (error) {
        console.error('Erro ao salvar evento:', error);
        this.$toast?.error('Erro ao salvar evento');
      } finally {
        this.saving = false;
      }
    },

    addAgenda() {
      this.formData.agendas.push({
        data: '',
        endereco: '',
        cidade: '',
      });
    },

    removeAgenda(index) {
      this.formData.agendas.splice(index, 1);
    },

    closeFormDialog() {
      this.showFormDialog = false;
      this.resetForm();
    },

    resetForm() {
      this.formData = {
        nome: '',
        descricao: '',
        realizacao: '',
        link_foto: '',
        link_logo: '',
        link_site: '',
        link_instagram: '',
        link_video: '',
        link_x: '',
        link_tiktok: '',
        agendas: [],
        atracoes: [],
        concursos: [],
      };
      this.$refs.form?.resetValidation();
    },

    formatDateTime(dateString) {
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

    formatDate(dateString) {
      if (!dateString) return 'N/A';
      const date = new Date(dateString);
      return date.toLocaleDateString('pt-BR', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric',
      });
    },

    formatDateTimeForInput(dateString) {
      if (!dateString) return '';
      const date = new Date(dateString);
      return date.toISOString().slice(0, 16);
    },

    truncate(text, length) {
      if (!text) return '';
      return text.length > length ? text.substring(0, length) + '...' : text;
    },
  },
};
</script>

<style scoped>
.eventos-grid-container {
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
  color: #2d3748;
  margin: 0;
}

.filters-section {
  display: flex;
  gap: 16px;
  margin-bottom: 24px;
  flex-wrap: wrap;
  padding: 20px;
  background: white;
  border-radius: 8px;
  box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}

.filter-group {
  flex: 1;
  min-width: 200px;
}

.filter-group label {
  display: block;
  margin-bottom: 8px;
  font-weight: 500;
  color: #4a5568;
  font-size: 14px;
}

.form-control {
  width: 100%;
  padding: 8px 12px;
  border: 1px solid #cbd5e0;
  border-radius: 4px;
  font-size: 14px;
}

.btn {
  padding: 8px 16px;
  border-radius: 4px;
  border: none;
  cursor: pointer;
  font-weight: 500;
  transition: all 0.2s;
  font-size: 14px;
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
}

.btn-secondary {
  background-color: #718096;
  color: white;
}

.btn-secondary:hover {
  background-color: #4a5568;
}

.loading-overlay {
  display: flex;
  justify-content: center;
  align-items: center;
  padding: 48px;
}

.spinner {
  width: 40px;
  height: 40px;
  border: 4px solid #e2e8f0;
  border-top-color: #4299e1;
  border-radius: 50%;
  animation: spin 0.8s linear infinite;
}

@keyframes spin {
  to { transform: rotate(360deg); }
}

.eventos-table-wrapper {
  background: white;
  border-radius: 8px;
  box-shadow: 0 1px 3px rgba(0,0,0,0.1);
  overflow: hidden;
}

.eventos-table {
  width: 100%;
  border-collapse: collapse;
}

.eventos-table thead {
  background-color: #edf2f7;
}

.eventos-table th {
  padding: 16px;
  text-align: left;
  font-weight: 600;
  color: #2d3748;
  font-size: 14px;
  border-bottom: 2px solid #cbd5e0;
}

.eventos-table tbody tr {
  border-bottom: 1px solid #e2e8f0;
  transition: background-color 0.2s;
}

.eventos-table tbody tr:hover {
  background-color: #f7fafc;
}

.eventos-table td {
  padding: 16px;
  font-size: 14px;
  color: #2d3748;
}

.evento-info {
  display: flex;
  flex-direction: column;
  gap: 4px;
}

.evento-desc {
  color: #718096;
  font-size: 13px;
}

.text-center {
  text-align: center;
}

.text-muted {
  color: #a0aec0;
}

.no-data {
  padding: 48px;
  color: #a0aec0;
  font-style: italic;
}

.badge-info {
  display: inline-block;
  padding: 2px 8px;
  background-color: #bee3f8;
  color: #2c5282;
  border-radius: 12px;
  font-size: 11px;
  font-weight: 600;
  margin-left: 4px;
}

.actions-cell {
  display: flex;
  gap: 8px;
  justify-content: center;
}

.btn-action {
  width: 32px;
  height: 32px;
  border-radius: 4px;
  border: none;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: all 0.2s;
}

.btn-view {
  background-color: #90cdf4;
  color: #1a365d;
}

.btn-view:hover {
  background-color: #63b3ed;
}

.btn-edit {
  background-color: #fbd38d;
  color: #744210;
}

.btn-edit:hover {
  background-color: #f6ad55;
}

.btn-delete {
  background-color: #fc8181;
  color: #742a2a;
}

.btn-delete:hover {
  background-color: #f56565;
}

.dialog-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  background-color: #edf2f7;
  padding: 16px 24px;
}

.section-title {
  font-size: 16px;
  font-weight: 600;
  color: #2d3748;
  margin-bottom: 12px;
  display: flex;
  align-items: center;
  gap: 8px;
}

.agenda-card {
  margin-bottom: 12px;
}

.view-content {
  padding: 24px;
}

.evento-image {
  width: 100%;
  margin-bottom: 20px;
}

.evento-image img {
  width: 100%;
  border-radius: 8px;
  object-fit: cover;
}

.info-section {
  margin-bottom: 20px;
}

.info-section h4 {
  font-size: 16px;
  font-weight: 600;
  color: #2d3748;
  margin-bottom: 8px;
}

.info-section p {
  color: #4a5568;
  line-height: 1.6;
}

.agenda-item {
  padding: 8px 0;
  border-bottom: 1px solid #e2e8f0;
}

.agenda-item:last-child {
  border-bottom: none;
}

.social-links {
  display: flex;
  flex-wrap: wrap;
  gap: 12px;
}

.social-link {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  padding: 8px 16px;
  background-color: #edf2f7;
  color: #2d3748;
  text-decoration: none;
  border-radius: 4px;
  transition: all 0.2s;
}

.social-link:hover {
  background-color: #cbd5e0;
}
</style>
