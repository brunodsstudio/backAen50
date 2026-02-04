# API de Eventos GEEK - Aeranerd

## Descrição
Sistema de gerenciamento de eventos GEEK com suporte a agenda, atrações, concursos e galerias.

## Estrutura do Banco de Dados

### Tabelas Principais

#### aen_eventos
Armazena informações dos eventos principais.
- `id`: Identificador único
- `nome`: Nome do evento (obrigatório)
- `descricao`: Descrição completa (obrigatório)
- `realizacao`: Data e hora de realização
- `link_foto`, `link_logo`, `link_site`, `link_instagram`, `link_video`, `link_x`, `link_tiktok`: Links de mídia e redes sociais
- `created_at`, `updated_at`, `deleted_at`: Timestamps

#### aen_concursos
Concursos realizados nos eventos.
- `id`: Identificador único
- `nome`: Nome do concurso (obrigatório)
- `tipo_concurso_id`: FK para aen_tipo_concurso (obrigatório)
- `link_foto`: Imagem do concurso
- `descricao`: Detalhes do concurso
- `datas_horas`: Informações de horário (texto livre)
- `created_at`, `updated_at`, `deleted_at`: Timestamps

#### aen_atracoes
Atrações que participam dos eventos.
- `id`: Identificador único
- `nome`: Nome da atração (obrigatório)
- `tipo_atracao_id`: FK para aen_tipo_atracao (obrigatório)
- `link_foto`, `link_instagram`, `link_perfil`: Links de mídia
- `descricao`: Informações sobre a atração
- `created_at`, `updated_at`, `deleted_at`: Timestamps

#### aen_agenda_evento
Datas e locais dos eventos (1:N com eventos).
- `id`: Identificador único
- `evento_id`: FK para aen_eventos (obrigatório)
- `data`: Data do evento (obrigatório)
- `endereco`: Local (obrigatório)
- `cidade`: Cidade (obrigatório)
- `created_at`, `updated_at`, `deleted_at`: Timestamps

#### aen_evento_galeria
Galerias de fotos dos eventos.
- `id`: Identificador único
- `evento_id`: FK para aen_eventos (obrigatório)
- `link_materia`: URL da matéria relacionada
- `dia`: Data das fotos
- `descricao`: Descrição da galeria
- `pasta_aws`: Caminho no S3
- `created_at`, `updated_at`, `deleted_at`: Timestamps

#### aen_tipo_atracao
Tipos de atrações disponíveis.
- `id`: Identificador único
- `nome`: Nome do tipo (Banda, Dublador, Cantor, Ator, Celebridade, Food Truck, Expositores, Artist Alley, Painel, Estréia)

#### aen_tipo_concurso
Tipos de concursos.
- `id`: Identificador único
- `nome`: Nome do tipo (Dança Kpop, Arte/Ilustração, Troféu, Cosplay)

#### aen_evento_atracao
Tabela pivot: relacionamento N:N entre eventos e atrações.
- `id`, `evento_id`, `atracao_id`

#### aen_evento_concurso
Tabela pivot: relacionamento N:N entre eventos e concursos.
- `id`, `evento_id`, `concurso_id`

---

## Endpoints da API

### Base URL
```
/api/events
```

### Eventos

#### **GET** `/api/events`
Lista todos os eventos com relacionamentos.

**Query Parameters:**
- `cidade` (string): Filtrar por cidade
- `data` (date Y-m-d): Filtrar por data
- `tipo_atracao` (int): Filtrar por tipo de atração

**Response 200:**
```json
[
  {
    "id": 1,
    "nome": "Anime Festival 2026",
    "descricao": "O maior evento de anime do Brasil",
    "realizacao": "2026-06-15 10:00:00",
    "agendas": [...],
    "atracoes": [...],
    "concursos": [...],
    "galerias": [...]
  }
]
```

---

#### **POST** `/api/events`
Cria um novo evento.

**Request Body:**
```json
{
  "nome": "Anime Festival 2026",
  "descricao": "O maior evento de anime do Brasil",
  "realizacao": "2026-06-15 10:00:00",
  "link_foto": "https://example.com/foto.jpg",
  "link_logo": "https://example.com/logo.png",
  "agendas": [
    {
      "data": "2026-06-15",
      "endereco": "Av. Paulista, 1000",
      "cidade": "São Paulo"
    }
  ],
  "atracoes": [1, 2, 3],
  "concursos": [1, 2]
}
```

**Response 201:**
```json
{
  "id": 1,
  "nome": "Anime Festival 2026",
  ...
}
```

---

#### **GET** `/api/events/{id}`
Retorna detalhes de um evento específico.

**Response 200:**
```json
{
  "id": 1,
  "nome": "Anime Festival 2026",
  "agendas": [...],
  "atracoes": [...],
  "concursos": [...]
}
```

---

#### **PUT** `/api/events/{id}`
Atualiza um evento existente.

**Request Body:**
```json
{
  "nome": "Anime Festival 2026 - Atualizado",
  "descricao": "Nova descrição",
  "atracoes": [1, 3, 5],
  "concursos": [2]
}
```

---

#### **DELETE** `/api/events/{id}`
Remove um evento (soft delete).

**Response 204:** No content

---

### Atrações

#### **GET** `/api/events/atracoes`
Lista todas as atrações.

**Query Parameters:**
- `tipo_atracao_id` (int): Filtrar por tipo

---

#### **POST** `/api/events/atracoes`
Cria uma nova atração.

**Request Body:**
```json
{
  "nome": "Banda Anime Rock",
  "tipo_atracao_id": 1,
  "link_foto": "https://example.com/banda.jpg",
  "link_instagram": "https://instagram.com/banda",
  "descricao": "Banda especializada em covers de animes"
}
```

---

#### **GET** `/api/events/atracoes/{id}`
Detalhes de uma atração.

---

#### **PUT** `/api/events/atracoes/{id}`
Atualiza uma atração.

---

#### **DELETE** `/api/events/atracoes/{id}`
Remove uma atração.

---

### Concursos

#### **GET** `/api/events/concursos`
Lista todos os concursos.

**Query Parameters:**
- `tipo_concurso_id` (int): Filtrar por tipo

---

#### **POST** `/api/events/concursos`
Cria um novo concurso.

**Request Body:**
```json
{
  "nome": "Concurso de Cosplay",
  "tipo_concurso_id": 4,
  "link_foto": "https://example.com/concurso.jpg",
  "descricao": "Melhor cosplay do evento",
  "datas_horas": "15/06/2026 às 14h - Palco Principal"
}
```

---

#### **GET** `/api/events/concursos/{id}`
Detalhes de um concurso.

---

#### **PUT** `/api/events/concursos/{id}`
Atualiza um concurso.

---

#### **DELETE** `/api/events/concursos/{id}`
Remove um concurso.

---

## Instalação e Uso

### 1. Executar Migrations
```bash
cd /home/dsstudio/aeranerd/backAen50
php artisan migrate
```

### 2. Executar Seeders
```bash
php artisan db:seed --class=TipoAtracaoSeeder
php artisan db:seed --class=TipoConcursoSeeder
```

### 3. Gerar Documentação Swagger
```bash
php artisan l5-swagger:generate
```

### 4. Acessar Swagger UI
```
http://seu-dominio/api/documentation
```

---

## Recursos Implementados

✅ Migrations normalizadas com soft deletes  
✅ Models com relacionamentos (1:N e N:N)  
✅ Controllers com CRUD completo  
✅ Filtros em listagens (cidade, data, tipo)  
✅ Rotas públicas sem autenticação  
✅ Seeders para tipos predefinidos  
✅ Documentação Swagger completa  

---

## Próximos Passos (Opcional)

- Adicionar validação de requests com Form Requests
- Implementar paginação nas listagens
- Adicionar recursos de upload de imagens
- Criar endpoints para AgendaEvento e EventoGaleria
- Implementar busca full-text nos eventos
- Adicionar autenticação nos endpoints administrativos
