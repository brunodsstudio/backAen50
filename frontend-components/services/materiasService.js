// services/materiasService.js
import axios from 'axios';

const API_BASE_URL = process.env.VUE_APP_API_URL || 'http://127.0.0.1:8000/api';

class MateriasService {
  constructor() {
    this.api = axios.create({
      baseURL: API_BASE_URL,
      headers: {
        'Content-Type': 'application/json',
      },
    });

    // Interceptor para adicionar token em todas as requisições
    this.api.interceptors.request.use(
      (config) => {
        const token = localStorage.getItem('token');
        if (token) {
          config.headers.Authorization = `Bearer ${token}`;
        }
        return config;
      },
      (error) => Promise.reject(error)
    );
  }

  // Listar matérias com paginação e filtros
  async getMaterias(params = {}) {
    const { page = 1, perPage = 10, search = '', editor = '', onLine = null } = params;
    
    const queryParams = new URLSearchParams({
      page: page.toString(),
      perPage: perPage.toString(),
    });

    if (search) queryParams.append('search', search);
    if (editor) queryParams.append('editor', editor);
    if (onLine !== null) queryParams.append('onLine', onLine.toString());

    const response = await this.api.get(`/materias?${queryParams.toString()}`);
    return response.data;
  }

  // Obter uma matéria específica
  async getMateria(id) {
    const response = await this.api.get(`/materias/${id}`);
    return response.data;
  }

  // Criar matéria
  async createMateria(data) {
    const response = await this.api.post('/materias', data);
    return response.data;
  }

  // Atualizar matéria
  async updateMateria(id, data) {
    const response = await this.api.put(`/materias/${id}`, data);
    return response.data;
  }

  // Deletar matéria
  async deleteMateria(id) {
    const response = await this.api.delete(`/materias/${id}`);
    return response.data;
  }

  // Atualizar status online
  async toggleOnline(id, status) {
    const response = await this.api.put(`/materias/${id}`, {
      bool_onLine: status,
    });
    return response.data;
  }

  // Obter lista de escritores (writers)
  async getWriters() {
    const response = await this.api.get('/writers');
    return response.data;
  }

  // Obter imagens de uma matéria
  async getImagens(materiaId) {
    const response = await this.api.get(`/materias/${materiaId}/images`);
    return response.data;
  }

  // Adicionar imagem a uma matéria
  async addImagem(materiaId, imageData) {
    const response = await this.api.post(`/materias/${materiaId}/images`, imageData);
    return response.data;
  }

  // Deletar imagem
  async deleteImagem(imageId) {
    const response = await this.api.delete(`/images/${imageId}`);
    return response.data;
  }
}

export default new MateriasService();
