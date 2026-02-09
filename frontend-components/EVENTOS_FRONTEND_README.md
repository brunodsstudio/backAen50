# 🎮 Frontend - CRUD de Eventos GEEK

## ✅ Implementação Completa

Sistema frontend completo para gerenciamento de eventos GEEK desenvolvido em Vue.js 3 + Vuetify.

---

## 📁 Arquivos Criados

### 1. Service Layer
**`frontend-components/services/eventosService.js`**
- Serviço para comunicação com API de eventos
- Métodos CRUD completos (GET, POST, PUT, DELETE)
- Interceptor para autenticação em POST, DELETE e PATCH
- Suporte a filtros (cidade, data, tipo_atracao)
- Integração com atrações e concursos

### 2. Componente Principal
**`frontend-components/components/Eventos/EventosGrid.vue`**
- Listagem de eventos em tabela responsiva
- Filtros: cidade, data, tipo de atração
- Ações: visualizar, editar, excluir
- Modal Vuetify para criar/editar eventos
- Gerenciamento dinâmico de agendas (add/remove)
- Multi-select para atrações e concursos
- Interface completa com validação de formulário

### 3. Rotas
**`frontend-components/router/eventos.routes.js`**
- Rota `/eventos` com autenticação
- Integrado ao sistema de rotas principal

### 4. Navegação
**`frontend-components/components/SidebarMenu.vue`** (atualizado)
- Item "Eventos GEEK" adicionado no menu Conteúdo
- Ícone de calendário
- Destaque visual para rota ativa

### 5. Router Principal
**`frontend-components/router/index.js`** (atualizado)
- Import de rotas de eventos
- Integração com guard de autenticação

---

## 🎨 Recursos Implementados

### Listagem de Eventos
✅ Tabela com colunas: ID, Nome, Realização, Cidade, Ações  
✅ Exibição de descrição truncada  
✅ Badge mostrando múltiplas agendas  
✅ Loading state com spinner animado  
✅ Mensagem quando não há dados  

### Filtros
✅ Busca por cidade (com debounce)  
✅ Filtro por data  
✅ Filtro por tipo de atração  
✅ Botão limpar filtros  

### Modal de Formulário (Vuetify)
✅ Criação e edição no mesmo modal  
✅ Validação de campos obrigatórios  
✅ Campos: nome, descrição, data/hora, links (foto, logo, site, redes sociais)  
✅ **Agendas dinâmicas:** adicionar/remover múltiplas datas e locais  
✅ **Multi-select de atrações** com chips  
✅ **Multi-select de concursos** com chips  
✅ Layout responsivo com Vuetify Grid  
✅ Botões de ação (salvar/cancelar)  

### Modal de Visualização
✅ Exibição completa dos dados do evento  
✅ Imagem destacada (se disponível)  
✅ Listagem de agendas formatadas  
✅ Chips para atrações e concursos  
✅ Links de redes sociais com ícones  

### Ações
✅ **Visualizar:** abre modal read-only com todos os detalhes  
✅ **Editar:** abre modal de formulário preenchido  
✅ **Excluir:** confirmação antes de deletar  
✅ Toast notifications para feedback  

---

## 🚀 Como Usar

### 1. Acessar o Sistema
```
http://seu-dominio/eventos
```

### 2. Criar Novo Evento
1. Clique no botão "Novo Evento"
2. Preencha nome e descrição (obrigatórios)
3. Adicione data/hora de realização (opcional)
4. Preencha links de mídia (foto, logo, site, redes sociais)
5. **Adicione agendas:**
   - Clique no botão "+" ao lado de "Agenda do Evento"
   - Preencha data, endereço e cidade
   - Adicione múltiplas datas se necessário
   - Remova com o botão de lixeira
6. **Selecione atrações** no multi-select
7. **Selecione concursos** no multi-select
8. Clique em "Criar"

### 3. Editar Evento
1. Clique no ícone de editar (lápis)
2. Modifique os campos desejados
3. Clique em "Atualizar"

### 4. Visualizar Detalhes
1. Clique no ícone de visualizar (olho)
2. Veja todos os detalhes do evento
3. Acesse links de redes sociais

### 5. Excluir Evento
1. Clique no ícone de excluir (lixeira)
2. Confirme a ação

### 6. Filtrar Eventos
- Digite cidade no campo de busca
- Selecione uma data
- Escolha tipo de atração
- Clique em "Limpar" para resetar

---

## 🎨 Estilo Visual

### Cores
- **Primary:** `#4299e1` (azul)
- **Background:** `#f5f7fa` (cinza claro)
- **Ações Visualizar:** `#90cdf4` (azul claro)
- **Ações Editar:** `#fbd38d` (laranja)
- **Ações Excluir:** `#fc8181` (vermelho)

### Componentes Vuetify
- `v-dialog` - Modais
- `v-form` - Formulários com validação
- `v-text-field` - Campos de texto
- `v-textarea` - Área de texto
- `v-select` - Multi-select
- `v-card` - Cards
- `v-chip` - Chips para tags
- `v-btn` - Botões
- `v-icon` - Ícones Material Design

---

## 🔐 Autenticação

Apenas os métodos **POST**, **DELETE** e **PATCH** requerem autenticação JWT.

Os métodos **GET** são públicos conforme solicitado.

O token é automaticamente adicionado no header:
```javascript
Authorization: Bearer <token>
```

---

## 📡 Integração com API

### Endpoints Utilizados

**Eventos:**
- `GET /api/events` - Listar (com filtros)
- `GET /api/events/{id}` - Buscar um
- `POST /api/events` - Criar
- `PUT /api/events/{id}` - Atualizar
- `DELETE /api/events/{id}` - Deletar

**Atrações:**
- `GET /api/events/atracoes` - Listar
- `POST /api/events/atracoes` - Criar

**Concursos:**
- `GET /api/events/concursos` - Listar
- `POST /api/events/concursos` - Criar

---

## 🧪 Teste da Interface

1. **Navegue até /eventos** no menu lateral
2. **Crie um evento de teste:**
   ```
   Nome: Anime Fest 2026
   Descrição: Maior evento de anime do Brasil
   Realização: 2026-12-15 10:00
   ```
3. **Adicione agendas:**
   ```
   Data: 2026-12-15
   Endereço: Av. Paulista, 1000
   Cidade: São Paulo
   ```
4. **Selecione atrações** disponíveis
5. **Salve e verifique** na listagem
6. **Teste filtros** por cidade e data

---

## 🎯 Próximos Passos (Opcional)

- [ ] Paginação na listagem
- [ ] Upload de imagens local/S3
- [ ] Preview de imagens no formulário
- [ ] Exportar lista de eventos (PDF/Excel)
- [ ] Filtros avançados (por atração, concurso)
- [ ] Dashboard com estatísticas
- [ ] Gestão de galeria de fotos por evento

---

## ✨ Características Especiais

### Agendas Dinâmicas
Sistema permite adicionar/remover múltiplas datas e locais para o mesmo evento, ideal para eventos que acontecem em vários dias ou cidades.

### Multi-Select Inteligente
Atrações e concursos são apresentados em multi-select com chips, facilitando a visualização das seleções.

### Responsividade
Layout totalmente responsivo usando Vuetify Grid System (v-row, v-col).

### Validação em Tempo Real
Formulário valida campos obrigatórios antes de permitir submit.

---

## 🎉 Status: CONCLUÍDO

✅ Service criado  
✅ Componente principal desenvolvido  
✅ Modais de formulário e visualização  
✅ Rotas configuradas  
✅ Menu atualizado  
✅ Integração com backend completa  
✅ Estilo seguindo padrão do projeto  

**O sistema está 100% funcional e pronto para uso!** 🚀
