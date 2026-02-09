<!-- components/Materias/UploadImageDialog.vue -->
<template>
  <div class="dialog-overlay" @click.self="closeDialog">
    <div class="upload-dialog-container">
      <div class="dialog-header">
        <h3>Adicionar Imagem - {{ getTipoLabel(tipo) }}</h3>
        <button class="btn-close" @click="closeDialog">
          <i class="fas fa-times"></i>
        </button>
      </div>

      <div class="dialog-body">
        <form @submit.prevent="uploadImage">
          <!-- URL da Imagem -->
          <div class="form-group">
            <label>URL da Imagem Principal *</label>
            <input
              v-model="formData.vchr_ImgLink"
              type="url"
              class="form-control"
              placeholder="https://exemplo.com/imagem.jpg"
              required
            />
          </div>

          <!-- URL da Thumbnail -->
          <div class="form-group">
            <label>URL da Thumbnail</label>
            <input
              v-model="formData.vchr_ImgThumbLink"
              type="url"
              class="form-control"
              placeholder="https://exemplo.com/thumb.jpg"
            />
          </div>

          <!-- Descrição -->
          <div class="form-group">
            <label>Descrição</label>
            <textarea
              v-model="formData.vchr_Descricao"
              class="form-control"
              rows="3"
              placeholder="Descrição da imagem..."
            ></textarea>
          </div>

          <!-- Link de Referência -->
          <div class="form-group">
            <label>Link de Referência (HRef)</label>
            <input
              v-model="formData.vchr_HRef"
              type="url"
              class="form-control"
              placeholder="https://exemplo.com/destino"
            />
          </div>

          <!-- Dimensões da Imagem -->
          <div class="form-row">
            <div class="form-group">
              <label>Largura (px)</label>
              <input
                v-model.number="formData.dl_SizeW"
                type="number"
                class="form-control"
                placeholder="1920"
              />
            </div>
            <div class="form-group">
              <label>Altura (px)</label>
              <input
                v-model.number="formData.dl_SizeH"
                type="number"
                class="form-control"
                placeholder="1080"
              />
            </div>
          </div>

          <!-- Dimensões da Thumbnail -->
          <div class="form-row">
            <div class="form-group">
              <label>Largura Thumb (px)</label>
              <input
                v-model.number="formData.dl_Thumb_SizeW"
                type="number"
                class="form-control"
                placeholder="400"
              />
            </div>
            <div class="form-group">
              <label>Altura Thumb (px)</label>
              <input
                v-model.number="formData.dl_Thumb_SizeH"
                type="number"
                class="form-control"
                placeholder="300"
              />
            </div>
          </div>

          <!-- Preview -->
          <div v-if="formData.vchr_ImgLink" class="preview-section">
            <h4>Preview:</h4>
            <img
              :src="formData.vchr_ImgLink"
              alt="Preview"
              class="preview-image"
              @error="imageError = true"
            />
            <p v-if="imageError" class="error-message">
              <i class="fas fa-exclamation-triangle"></i>
              Não foi possível carregar a imagem
            </p>
          </div>
        </form>
      </div>

      <div class="dialog-footer">
        <button class="btn btn-secondary" type="button" @click="closeDialog">
          Cancelar
        </button>
        <button
          class="btn btn-primary"
          type="submit"
          @click="uploadImage"
          :disabled="loading || !formData.vchr_ImgLink"
        >
          <i class="fas fa-save"></i>
          {{ loading ? 'Salvando...' : 'Salvar Imagem' }}
        </button>
      </div>
    </div>
  </div>
</template>

<script>
import materiasService from '@/services/materiasService';

export default {
  name: 'UploadImageDialog',
  props: {
    materiaId: {
      type: Number,
      required: true,
    },
    tipo: {
      type: String,
      required: true,
    },
  },
  data() {
    return {
      loading: false,
      imageError: false,
      formData: {
        vchr_ImgLink: '',
        vchr_ImgThumbLink: '',
        vchr_Tipo: this.tipo,
        vchr_Descricao: '',
        vchr_HRef: '',
        dl_SizeW: null,
        dl_SizeH: null,
        dl_Thumb_SizeW: null,
        dl_Thumb_SizeH: null,
      },
      tipoLabels: {
        'Slider_Home': 'Slider Home',
        'Facebook_share': 'Facebook Share',
        'Materia_home_thumb': 'Matéria Home Thumb',
        'Top_Materia': 'Top Matéria',
      },
    };
  },
  methods: {
    getTipoLabel(tipo) {
      return this.tipoLabels[tipo] || tipo;
    },

    async uploadImage() {
      if (!this.formData.vchr_ImgLink) {
        this.$toast.error('Por favor, informe a URL da imagem');
        return;
      }

      this.loading = true;
      try {
        await materiasService.addImagem(this.materiaId, {
          ...this.formData,
          dt_Upload: new Date().toISOString(),
        });

        this.$toast.success('Imagem adicionada com sucesso');
        this.$emit('uploaded');
        this.closeDialog();
      } catch (error) {
        console.error('Erro ao fazer upload:', error);
        this.$toast.error('Erro ao adicionar imagem');
      } finally {
        this.loading = false;
      }
    },

    closeDialog() {
      this.$emit('close');
    },
  },
  watch: {
    'formData.vchr_ImgLink'() {
      this.imageError = false;
    },
  },
};
</script>

<style scoped>
.upload-dialog-container {
  background: white;
  border-radius: 12px;
  max-width: 600px;
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
  padding: 20px 24px;
  border-bottom: 1px solid #e2e8f0;
  background: #f7fafc;
}

.dialog-header h3 {
  margin: 0;
  font-size: 18px;
  font-weight: 600;
  color: #2d3748;
}

.dialog-body {
  flex: 1;
  overflow-y: auto;
  padding: 24px;
}

.form-group {
  margin-bottom: 20px;
}

.form-group label {
  display: block;
  margin-bottom: 8px;
  font-weight: 600;
  font-size: 14px;
  color: #4a5568;
}

.form-control {
  width: 100%;
  padding: 10px 12px;
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

textarea.form-control {
  resize: vertical;
  font-family: inherit;
}

.form-row {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 16px;
  margin-bottom: 20px;
}

.preview-section {
  margin-top: 24px;
  padding: 16px;
  background: #f7fafc;
  border-radius: 8px;
  border: 1px solid #e2e8f0;
}

.preview-section h4 {
  margin: 0 0 12px 0;
  font-size: 14px;
  font-weight: 600;
  color: #4a5568;
}

.preview-image {
  width: 100%;
  max-height: 300px;
  object-fit: contain;
  border-radius: 6px;
  background: white;
}

.error-message {
  margin: 12px 0 0 0;
  padding: 12px;
  background: #fff5f5;
  color: #c53030;
  border-radius: 6px;
  font-size: 14px;
  display: flex;
  align-items: center;
  gap: 8px;
}

.dialog-footer {
  padding: 16px 24px;
  border-top: 1px solid #e2e8f0;
  display: flex;
  justify-content: flex-end;
  gap: 12px;
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
  font-size: 14px;
}

.btn:disabled {
  opacity: 0.6;
  cursor: not-allowed;
}

.btn-primary {
  background-color: #4299e1;
  color: white;
}

.btn-primary:hover:not(:disabled) {
  background-color: #3182ce;
}

.btn-secondary {
  background-color: #718096;
  color: white;
}

.btn-secondary:hover {
  background-color: #4a5568;
}

.btn-close {
  background: none;
  border: none;
  font-size: 20px;
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
</style>
