# 📝 Endpoints de Matérias - Documentação Swagger

## ✅ Endpoints Implementados

Todos os endpoints CRUD de Matérias foram documentados e implementados no Swagger!

### 📋 Lista de Endpoints

#### 1. **GET /api/materias** - Listar Matérias
- **Tag:** Matérias
- **Descrição:** Retorna uma lista paginada de todas as matérias
- **Parâmetros Query:**
  - `page` (opcional): Número da página (padrão: 1)
  - `perPage` (opcional): Itens por página (padrão: 10)
- **Resposta 200:** Array de matérias
- **Resposta 500:** Erro no servidor

#### 2. **GET /api/materias/{id}** - Obter Matéria
- **Tag:** Matérias
- **Descrição:** Retorna os detalhes de uma matéria específica
- **Parâmetros Path:**
  - `id` (obrigatório): ID da matéria
- **Resposta 200:** Dados da matéria
- **Resposta 404:** Matéria não encontrada
- **Resposta 500:** Erro no servidor

#### 3. **POST /api/materias** - Criar Matéria
- **Tag:** Matérias
- **Descrição:** Cria uma nova matéria no sistema
- **Autenticação:** Bearer Token JWT (🔒)
- **Campos Obrigatórios:**
  - `vchr_titulo` (string): Título da matéria
  - `vchr_conteudo` (string): Conteúdo da matéria
- **Campos Opcionais:**
  - `dt_post` (datetime): Data de publicação
  - `vchr_autor` (string): Nome do autor
  - `int_autor` (integer): ID do autor
  - `vchr_lide` (string): Lide da matéria
  - `vchr_area` (string): Nome da área
  - `id_area` (integer): ID da área
  - `vchr_tags` (string): Tags da matéria
  - `vchr_FontLink` (string): Link da fonte
  - `vchr_LinkTitulo` (string): Título do link
  - `vchr_seoTitle` (string): Título SEO
  - `vchr_seoKeywords` (string): Palavras-chave SEO
  - `og_title` (string): Título Open Graph
  - `og_description` (string): Descrição Open Graph
  - `og_image` (string): Imagem Open Graph
  - `vchr_s3_storage` (string): Armazenamento S3
  - `bool_onLine` (boolean): Se está online
  - `bool_home` (boolean): Se aparece na home
  - `base64Format` (boolean): Formato base64
  - `materiaUUID` (string): UUID da matéria
  - `IdSocialIconTemplate` (integer): ID do template de ícone social
  - `vchr_GalDir` (string): Diretório da galeria
- **Resposta 201:** Matéria criada com sucesso
- **Resposta 422:** Dados inválidos
- **Resposta 500:** Erro no servidor

#### 4. **PUT /api/materias/{id}** - Atualizar Matéria
- **Tag:** Matérias
- **Descrição:** Atualiza os dados de uma matéria existente
- **Autenticação:** Bearer Token JWT (🔒)
- **Parâmetros Path:**
  - `id` (obrigatório): ID da matéria
- **Campos (todos opcionais):**
  - `vchr_titulo` (string): Título da matéria
  - `vchr_conteudo` (string): Conteúdo da matéria
  - `dt_post` (datetime): Data de publicação
  - `vchr_autor` (string): Nome do autor
  - `int_autor` (integer): ID do autor
  - `vchr_lide` (string): Lide da matéria
  - `vchr_area` (string): Nome da área
  - `id_area` (integer): ID da área
  - `vchr_tags` (string): Tags da matéria
  - `bool_onLine` (boolean): Se está online
  - `bool_home` (boolean): Se aparece na home
- **Resposta 200:** Matéria atualizada
- **Resposta 404:** Matéria não encontrada
- **Resposta 422:** Dados inválidos
- **Resposta 500:** Erro no servidor

#### 5. **DELETE /api/materias/{id}** - Deletar Matéria
- **Tag:** Matérias
- **Descrição:** Remove uma matéria do sistema
- **Autenticação:** Bearer Token JWT (🔒)
- **Parâmetros Path:**
  - `id` (obrigatório): ID da matéria
- **Resposta 204:** Matéria deletada (sem conteúdo)
- **Resposta 404:** Matéria não encontrada
- **Resposta 500:** Erro no servidor

#### 6. **GET /api/MateriasHome** - Listar Matérias para Home
- **Tag:** Matérias
- **Descrição:** Retorna matérias ativas (bool_home=1 e bool_onLine=1) ordenadas por data de criação descendente, com suas respectivas imagens
- **Filtros Aplicados:**
  - `bool_home` = true
  - `bool_onLine` = true
  - Ordenação: `created_at` DESC
- **Parâmetros Query:**
  - `limit` (opcional): Limite de registros a retornar (padrão: 20, máx: definido pelo usuário)
- **Campos Retornados das Imagens:**
  - `int_Id`: ID da imagem
  - `int_MateriaId`: ID da matéria associada
  - `vchr_Tipo`: Tipo da imagem (ex: Slider_Home, Facebook_share, Materia_home_thumb, Top_Materia)
  - `vchr_HRef`: Link de referência da imagem
- **Resposta 200:** Array de matérias com imagens associadas
- **Resposta 500:** Erro no servidor

---

## 🔐 Endpoints com Autenticação

Os seguintes endpoints requerem autenticação JWT Bearer:
- ✅ POST /api/materias
- ✅ PUT /api/materias/{id}
- ✅ DELETE /api/materias/{id}

**Lembre-se de:**
1. Fazer login ou gerar um token
2. Clicar no botão "Authorize" no Swagger
3. Colar o token Bearer

---

## 🧪 Exemplos de Uso

### Criar uma Nova Matéria

**Request:**
```json
POST /api/materias
Authorization: Bearer {seu-token}

{
  "vchr_titulo": "Título da Matéria",
  "vchr_conteudo": "Conteúdo completo da matéria aqui...",
  "vchr_lide": "Breve resumo da matéria",
  "vchr_autor": "Nome do Autor",
  "id_area": 1,
  "vchr_tags": "tecnologia, inovação",
  "bool_onLine": true,
  "bool_home": false,
  "vchr_seoTitle": "SEO Title",
  "og_title": "Open Graph Title"
}
```

**Response (201):**
```json
{
  "id": 1,
  "vchr_titulo": "Título da Matéria",
  "vchr_conteudo": "Conteúdo completo da matéria aqui...",
  "vchr_lide": "Breve resumo da matéria",
  "vchr_autor": "Nome do Autor",
  "id_area": 1,
  "vchr_tags": "tecnologia, inovação",
  "bool_onLine": true,
  "bool_home": false,
  "created_at": "2025-11-07T19:50:00.000000Z",
  "updated_at": "2025-11-07T19:50:00.000000Z"
}
```

### Atualizar uma Matéria

**Request:**
```json
PUT /api/materias/1
Authorization: Bearer {seu-token}

{
  "vchr_titulo": "Título Atualizado",
  "bool_onLine": true,
  "bool_home": true
}
```

**Response (200):**
```json
{
  "id": 1,
  "vchr_titulo": "Título Atualizado",
  "bool_onLine": true,
  "bool_home": true,
  "updated_at": "2025-11-07T19:55:00.000000Z"
}
```

### Listar Matérias com Paginação

**Request:**
```
GET /api/materias?page=1&perPage=15
```

**Response (200):**
```json
[
  {
    "id": 1,
    "vchr_titulo": "Matéria 1",
    "vchr_lide": "Resumo da matéria 1",
    ...
  },
  {
    "id": 2,
    "vchr_titulo": "Matéria 2",
    "vchr_lide": "Resumo da matéria 2",
    ...
  }
]
```

### Deletar uma Matéria

**Request:**
```
DELETE /api/materias/1
Authorization: Bearer {seu-token}
```

**Response (204):**
```
(No Content)
```

### Listar Matérias para Home

**Request:**
```
GET /api/MateriasHome?limit=10
```

**Response (200):**
```json
[
  {
    "id": 1,
    "vchr_titulo": "Título da Matéria",
    "vchr_lide": "Resumo da matéria",
    "vchr_area": "Tecnologia",
    "vchr_autor": "João Silva",
    "created_at": "2026-01-16T10:30:00.000000Z",
    "updated_at": "2026-01-16T10:30:00.000000Z",
    "bool_home": true,
    "bool_onLine": true,
    "images": [
      {
        "int_Id": 1,
        "int_MateriaId": 1,
        "vchr_Tipo": "Slider_Home",
        "vchr_HRef": "https://example.com/images/slider-1.jpg"
      },
      {
        "int_Id": 2,
        "int_MateriaId": 1,
        "vchr_Tipo": "Facebook_share",
        "vchr_HRef": "https://example.com/images/facebook-1.jpg"
      }
    ]
  },
  {
    "id": 2,
    "vchr_titulo": "Outra Matéria",
    "vchr_lide": "Outro resumo",
    "vchr_area": "Ciência",
    "vchr_autor": "Maria Santos",
    "created_at": "2026-01-15T14:20:00.000000Z",
    "updated_at": "2026-01-15T14:20:00.000000Z",
    "bool_home": true,
    "bool_onLine": true,
    "images": [
      {
        "int_Id": 3,
        "int_MateriaId": 2,
        "vchr_Tipo": "Top_Materia",
        "vchr_HRef": "https://example.com/images/top-2.jpg"
      }
    ]
  }
]
```

---

## 📊 Schema da Matéria

O schema completo está documentado no Swagger com todos os campos:

```javascript
{
  id: integer,
  dt_post: datetime,
  vchr_autor: string,
  int_autor: integer,
  vchr_lide: string,
  vchr_titulo: string,
  vchr_conteudo: string,
  vchr_area: string,
  id_area: integer,
  vchr_tags: string,
  vchr_FontLink: string,
  vchr_LinkTitulo: string,
  vchr_seoTitle: string,
  vchr_seoKeywords: string,
  og_title: string,
  og_description: string,
  og_image: string,
  vchr_s3_storage: string,
  bool_onLine: boolean,
  bool_home: boolean,
  base64Format: boolean,
  materiaUUID: string,
  IdSocialIconTemplate: integer,
  vchr_GalDir: string,
  created_at: datetime,
  updated_at: datetime
}
```

---

## 🚀 Acessar Documentação

**URL:** http://127.0.0.1:8000/api/documentation

1. Acesse a URL acima
2. Role até a seção "Matérias"
3. Você verá todos os 5 endpoints documentados
4. Clique em cada endpoint para ver detalhes
5. Use "Try it out" para testar diretamente

---

## 🔄 Atualizar Documentação

Após qualquer mudança nos endpoints, execute:

```bash
php artisan l5-swagger:generate
```

---

## ✅ Checklist de Implementação

- ✅ GET /api/materias - Listar (com paginação)
- ✅ GET /api/materias/{id} - Buscar por ID
- ✅ POST /api/materias - Criar (com autenticação)
- ✅ PUT /api/materias/{id} - Atualizar (com autenticação)
- ✅ DELETE /api/materias/{id} - Deletar (com autenticação)
- ✅ GET /api/MateriasHome - Listar matérias para home com imagens (filtro bool_home=1, bool_onLine=1, ordenado por created_at DESC)
- ✅ GET /api/MateriasCategoria - Listar matérias por categoria com paginação e imagens
- ✅ Validação de dados
- ✅ Tratamento de erros
- ✅ Documentação Swagger completa
- ✅ Schema do modelo documentado
- ✅ Rotas registradas no Laravel

---

## 📄 Detalhes dos Endpoints Especiais

### GET /api/MateriasHome
**Descrição:** Retorna matérias destacadas para exibição na home  
**Filtros aplicados:**
- `bool_home = true`
- `bool_onLine = true`
- Ordenação: `created_at DESC`

**Parâmetros:**
- `limit` (opcional): Quantidade de registros (padrão: 20)

**Retorno:** Array de matérias com imagens relacionadas (todos os tipos)

---

### GET /api/MateriasCategoria ⭐ NOVO
**Descrição:** Retorna matérias filtradas por categoria com paginação completa  
**Filtros aplicados:**
- `id_area = {valor obrigatório}`
- `bool_onLine = true`
- Ordenação padrão: `created_at DESC`

**Parâmetros:**
- `id_area` (obrigatório): ID da categoria/área
- `page` (opcional): Número da página (padrão: 1)
- `perPage` (opcional): Itens por página (padrão: 10)
- `orderBy` (opcional): Campo de ordenação
  - Opções: `created_at`, `dt_post`, `vchr_titulo`, `vchr_autor`
  - Padrão: `created_at`
- `orderDirection` (opcional): Direção da ordenação
  - Opções: `asc`, `desc`
  - Padrão: `desc`

**Retorno:** Objeto de paginação com:
- `current_page`: Página atual
- `per_page`: Itens por página
- `total`: Total de registros
- `last_page`: Última página
- `data`: Array de matérias com imagens do tipo **"Top_Materia"**

**Importante:** 
- Retorna matérias independente de `bool_home` (pode ser true ou false)
- Imagens filtradas exclusivamente pelo tipo "Top_Materia"
- Ideal para páginas de categoria/área específica

**Exemplo de uso:**
```
GET /api/MateriasCategoria?id_area=1&page=1&perPage=10&orderBy=created_at&orderDirection=desc
```

---

**Todos os endpoints de Matérias estão prontos e documentados! 🎉**