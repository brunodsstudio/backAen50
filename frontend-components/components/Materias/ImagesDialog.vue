<!-- components/Materias/ImagesDialog.vue -->
<template>
  <div class="dialog-overlay" @click.self="closeDialog">
    <div class="dialog-container">
      <div class="dialog-header">
        <h3>Gerenciar Imagens - {{ materia.vchr_titulo }}</h3>
        <button class="btn-close" @click="closeDialog">
          <i class="fas fa-times"></i>
        </button>
      </div>

      <div class="dialog-body">
        <!-- Tipos de Imagens -->
        <div class="image-types-grid">
          <div
            v-for="tipo in imagemTipos"
            :key="tipo.value"
            class="image-type-card"
          >
            <div class="card-header">
              <h4>{{ tipo.label }}</h4>
              <span class="image-count">{{ getImagesByTipo(tipo.value).length }}</span>
            </div>

            <div class="card-body">
              <!-- Imagem Atual -->
              <div v-if="getImagesByTipo(tipo.value).length > 0" class="current-image">
                <img
                  :src="getImagesByTipo(tipo.value)[0].vchr_ImgThumbLink || getImagesByTipo(tipo.value)[0].vchr_ImgLink"
                  :alt="tipo.label"
                  class="preview-image"
                />
                <div class="image-info">
                  <p><strong>Link:</strong></p>
                  <input
                    type="text"
                    :value="getImagesByTipo(tipo.value)[0].vchr_ImgLink"
                    readonly
                    class="form-control-sm"
                  />
                  <button
                    class="btn btn-danger btn-sm mt-2"
                    @click="deleteImage(getImagesByTipo(tipo.value)[0])"
                  >
                    <i class="fas fa-trash"></i> Remover
                  </button>
                </div>
              </div>

              <!-- Upload Nova Imagem -->
              <div v-else class="upload-area">
                <i class="fas fa-cloud-upload-alt upload-icon"></i>
                <p>Nenhuma imagem definida</p>
                <button
                  class="btn btn-primary btn-sm"
                  @click="openUploadDialog(tipo.value)"
                >
                  <i class="fas fa-plus"></i> Adicionar Imagem
                </button>
              </div>
            </div>
          </div>
        </div>

        <!-- Descrição dos Tipos -->
        <div class="tipos-info">
          <h4>Tipos de Imagem:</h4>
          <ul>
            <li><strong>Slider Home:</strong> Imagem exibida no slider da página inicial</li>
            <li><strong>Facebook Share:</strong> Imagem para compartilhamento no Facebook (OG Image)</li>
            <li><strong>Matéria Home Thumb:</strong> Thumbnail exibida na listagem da home</li>
            <li><strong>Top Matéria:</strong> Imagem de destaque no topo da matéria</li>
          </ul>
        </div>
      </div>

      <div class="dialog-footer">
        <button class="btn btn-secondary" @click="closeDialog">
          Fechar
        </button>
      </div>
    </div>

    <!-- Dialog de Upload -->
    <UploadImageDialog
      v-if="showUploadDialog"
      :materiaId="materia.id"
      :tipo="selectedTipo"
      @close="showUploadDialog = false"
      @uploaded="onImageUploaded"
    />
  </div>
</template>

<script>
import materiasService from '@/services/materiasService';
import UploadImageDialog from './UploadImageDialog.vue';

export default {
  name: 'ImagesDialog',
  components: {
    UploadImageDialog,
  },
  props: {
    materia: {
      type: Object,
      required: true,
    },
  },
  data() {
    return {
      images: [],
      loading: false,
      imagemTipos: [
        { value: 'Slider_Home', label: 'Slider Home' },
        { value: 'Facebook_share', label: 'Facebook Share' },
        { value: 'Materia_home_thumb', label: 'Matéria Home Thumb' },
        { value: 'Top_Materia', label: 'Top Matéria' },
      ],
      showUploadDialog: false,
      selectedTipo: null,
    };
  },
  mounted() {
    this.loadImages();
  },
  methods: {
    async loadImages() {
      this.loading = true;
      try {
        this.images = await materiasService.getImagens(this.materia.id);
      } catch (error) {
        console.error('Erro ao carregar imagens:', error);
        this.$toast.error('Erro ao carregar imagens');
      } finally {
        this.loading = false;
      }
    },

    getImagesByTipo(tipo) {
      return this.images.filter((img) => img.vchr_Tipo === tipo);
    },

    openUploadDialog(tipo) {
      this.selectedTipo = tipo;
      this.showUploadDialog = true;
    },

    async deleteImage(image) {
      if (!confirm('Tem certeza que deseja remover esta imagem?')) {
        return;
      }

      try {
        await materiasService.deleteImagem(image.int_Id);
        this.$toast.success('Imagem removida com sucesso');
        this.loadImages();
      } catch (error) {
        console.error('Erro ao deletar imagem:', error);
        this.$toast.error('Erro ao deletar imagem');
      }
    },

    onImageUploaded() {
      this.loadImages();
      this.$emit('refresh');
    },

    closeDialog() {
      this.$emit('close');
    },
  },
};
</script>

<style scoped>
.dialog-overlay {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: rgba(0, 0, 0, 0.7);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 1000;
  padding: 20px;
}

.dialog-container {
  background: white;
  border-radius: 12px;
  max-width: 1200px;
  width: 100%;
  max-height: 90vh;
  overflow: hidden;
  display: flex;
  flex-direction: column;
  box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
}

.dialog-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 24px;
  border-bottom: 1px solid #e2e8f0;
  background: #f7fafc;
}

.dialog-header h3 {
  margin: 0;
  font-size: 20px;
  font-weight: 600;
  color: #2d3748;
}

.btn-close {
  background: none;
  border: none;
  font-size: 24px;
  color: #718096;
  cursor: pointer;
  padding: 4px;
  display: flex;
  align-items: center;
  justify-content: center;
  width: 32px;
  height: 32px;
  border-radius: 6px;
  transition: all 0.2s;
}

.btn-close:hover {
  background-color: #e2e8f0;
  color: #2d3748;
}

.dialog-body {
  flex: 1;
  overflow-y: auto;
  padding: 24px;
}

.image-types-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
  gap: 20px;
  margin-bottom: 24px;
}

.image-type-card {
  border: 1px solid #e2e8f0;
  border-radius: 8px;
  overflow: hidden;
  background: white;
  transition: box-shadow 0.2s;
}

.image-type-card:hover {
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.card-header {
  background: #f7fafc;
  padding: 16px;
  display: flex;
  justify-content: space-between;
  align-items: center;
  border-bottom: 1px solid #e2e8f0;
}

.card-header h4 {
  margin: 0;
  font-size: 16px;
  font-weight: 600;
  color: #2d3748;
}

.image-count {
  background: #4299e1;
  color: white;
  padding: 4px 10px;
  border-radius: 12px;
  font-size: 12px;
  font-weight: 600;
}

.card-body {
  padding: 16px;
}

.current-image {
  display: flex;
  flex-direction: column;
  gap: 12px;
}

.preview-image {
  width: 100%;
  height: 160px;
  object-fit: cover;
  border-radius: 6px;
  border: 1px solid #e2e8f0;
}

.image-info {
  display: flex;
  flex-direction: column;
  gap: 8px;
}

.image-info p {
  margin: 0;
  font-size: 12px;
  font-weight: 600;
  color: #4a5568;
}

.upload-area {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  padding: 32px 16px;
  border: 2px dashed #cbd5e0;
  border-radius: 6px;
  text-align: center;
  background: #f7fafc;
}

.upload-icon {
  font-size: 48px;
  color: #a0aec0;
  margin-bottom: 16px;
}

.upload-area p {
  margin: 0 0 16px 0;
  color: #718096;
  font-size: 14px;
}

.tipos-info {
  background: #ebf8ff;
  padding: 20px;
  border-radius: 8px;
  border-left: 4px solid #4299e1;
}

.tipos-info h4 {
  margin: 0 0 12px 0;
  font-size: 16px;
  font-weight: 600;
  color: #2c5282;
}

.tipos-info ul {
  margin: 0;
  padding-left: 20px;
}

.tipos-info li {
  margin-bottom: 8px;
  color: #2d3748;
  font-size: 14px;
  line-height: 1.6;
}

.dialog-footer {
  padding: 16px 24px;
  border-top: 1px solid #e2e8f0;
  display: flex;
  justify-content: flex-end;
  background: #f7fafc;
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
}

.btn-secondary {
  background-color: #718096;
  color: white;
}

.btn-secondary:hover {
  background-color: #4a5568;
}

.btn-danger {
  background-color: #f56565;
  color: white;
}

.btn-danger:hover {
  background-color: #e53e3e;
}

.btn-sm {
  padding: 6px 12px;
  font-size: 14px;
}

.form-control-sm {
  width: 100%;
  padding: 6px 10px;
  border: 1px solid #e2e8f0;
  border-radius: 4px;
  font-size: 12px;
  font-family: 'Courier New', monospace;
  background: #f7fafc;
}

.mt-2 {
  margin-top: 8px;
}
</style>
