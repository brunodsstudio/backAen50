// services/eventosService.js
import axios from 'axios';

const API_BASE_URL = process.env.VUE_APP_API_URL || 'http://127.0.0.1:8000/api';

class EventosService {
  constructor() {
    this.api = axios.create({
      baseURL: API_BASE_URL,
      headers: {
        'Content-Type': 'application/json',
      },
    });

    // Interceptor para adicionar token apenas em POST, DELETE e PATCH
    this.api.interceptors.request.use(
      (config) => {
        const methodsWithAuth = ['post', 'delete', 'patch', 'put'];
        if (methodsWithAuth.includes(config.method.toLowerCase())) {
          const token = localStorage.getItem('token');
          if (token) {
            config.headers.Authorization = `Bearer ${token}`;
          }
        }
        return config;
      },
      (error) => Promise.reject(error)
    );
  }

  // Listar eventos com filtros
  async getEventos(params = {}) {
    const { cidade = '', data = '', tipo_atracao = '' } = params;
    
    const queryParams = new URLSearchParams();
    if (cidade) queryParams.append('cidade', cidade);
    if (data) queryParams.append('data', data);
    if (tipo_atracao) queryParams.append('tipo_atracao', tipo_atracao);

    const response = await this.api.get(`/events?${queryParams.toString()}`);
    return response.data;
  }

  // Obter um evento específico
  async getEvento(id) {
    const response = await this.api.get(`/events/${id}`);
    return response.data;
  }

  // Criar evento
  async createEvento(data) {
    const response = await this.api.post('/events', data);
    return response.data;
  }

  // Atualizar evento
  async updateEvento(id, data) {
    const response = await this.api.put(`/events/${id}`, data);
    return response.data;
  }

  // Deletar evento
  async deleteEvento(id) {
    const response = await this.api.delete(`/events/${id}`);
    return response.data;
  }

  // Listar tipos de atração
  async getTiposAtracao() {
    const response = await this.api.get('/events/tipos-atracao');
    return response.data;
  }

  // Listar atrações
  async getAtracoes(params = {}) {
    const { tipo_atracao_id = '' } = params;
    const queryParams = new URLSearchParams();
    if (tipo_atracao_id) queryParams.append('tipo_atracao_id', tipo_atracao_id);

    const response = await this.api.get(`/events/atracoes?${queryParams.toString()}`);
    return response.data;
  }

  // Criar atração
  async createAtracao(data) {
    const response = await this.api.post('/events/atracoes', data);
    return response.data;
  }

  // Listar concursos
  async getConcursos(params = {}) {
    const { tipo_concurso_id = '' } = params;
    const queryParams = new URLSearchParams();
    if (tipo_concurso_id) queryParams.append('tipo_concurso_id', tipo_concurso_id);

    const response = await this.api.get(`/events/concursos?${queryParams.toString()}`);
    return response.data;
  }

  // Criar concurso
  async createConcurso(data) {
    const response = await this.api.post('/events/concursos', data);
    return response.data;
  }

  // Upload de imagem local
  async uploadImage(file) {
    const formData = new FormData();
    formData.append('image', file);
    
    const response = await this.api.post('/upload/image', formData, {
      headers: {
        'Content-Type': 'multipart/form-data',
      },
    });
    return response.data;
  }
}

export default new EventosService();
