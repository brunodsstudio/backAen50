# 🎯 Novo Endpoint: MateriasCategoria

## 📋 Resumo da Implementação

Novo endpoint criado para listar matérias filtradas por categoria com paginação completa e imagens relacionadas.

---

## 🔗 Endpoint

**URL:** `GET /api/MateriasCategoria`

**Descrição:** Retorna matérias ativas (`bool_onLine=1`) filtradas por categoria (`id_area`), com paginação e imagens do tipo **"Top_Materia"**.

---

## 📥 Parâmetros

| Parâmetro | Tipo | Obrigatório | Descrição | Padrão | Exemplo |
|-----------|------|-------------|-----------|--------|---------|
| `id_area` | integer | ✅ Sim | ID da área/categoria | - | `1` |
| `page` | integer | ❌ Não | Número da página | `1` | `1` |
| `perPage` | integer | ❌ Não | Itens por página | `10` | `10` |
| `orderBy` | string | ❌ Não | Campo de ordenação | `created_at` | `created_at` |
| `orderDirection` | string | ❌ Não | Direção da ordenação | `desc` | `desc` |

### Opções para `orderBy`:
- `created_at` - Data de criação
- `dt_post` - Data de publicação
- `vchr_titulo` - Título
- `vchr_autor` - Autor

### Opções para `orderDirection`:
- `asc` - Ascendente
- `desc` - Descendente

---

## 📤 Exemplo de Requisição

```bash
GET /api/MateriasCategoria?id_area=1&page=1&perPage=10&orderBy=created_at&orderDirection=desc
```

```javascript
// JavaScript/Axios
const response = await axios.get('/api/MateriasCategoria', {
  params: {
    id_area: 1,
    page: 1,
    perPage: 10,
    orderBy: 'created_at',
    orderDirection: 'desc'
  }
});
```

---

## 📨 Estrutura de Resposta

### Sucesso (200)

```json
{
  "current_page": 1,
  "per_page": 10,
  "total": 45,
  "last_page": 5,
  "from": 1,
  "to": 10,
  "data": [
    {
      "id": 1,
      "dt_post": "2026-01-16T10:30:00.000000Z",
      "vchr_autor": "João Silva",
      "int_autor": 5,
      "vchr_titulo": "Título da Matéria",
      "vchr_lide": "Lide da matéria",
      "vchr_area": "Tecnologia",
      "id_area": 1,
      "vchr_tags": "tag1,tag2,tag3",
      "vchr_LinkTitulo": "titulo-da-materia",
      "bool_onLine": true,
      "bool_home": false,
      "created_at": "2026-01-16T10:30:00.000000Z",
      "updated_at": "2026-01-16T11:00:00.000000Z",
      "images": [
        {
          "int_Id": 1,
          "int_MateriaId": 1,
          "vchr_Tipo": "Top_Materia",
          "vchr_HRef": "https://example.com/image.jpg"
        }
      ]
    }
  ]
}
```

### Erro 400 - Parâmetro Obrigatório Ausente

```json
{
  "error": "O parâmetro id_area é obrigatório."
}
```

### Erro 500 - Erro Interno

```json
{
  "error": "Could not retrieve materias by category."
}
```

---

## 🔍 Filtros Aplicados Automaticamente

1. **Por Categoria:** `id_area = {valor fornecido}`
2. **Apenas Online:** `bool_onLine = 1`
3. **Ordenação Padrão:** `created_at DESC`
4. **Imagens:** Apenas tipo `"Top_Materia"`

---

## ⚙️ Características Especiais

✅ **Paginação Completa:** Retorna informações de paginação (total, páginas, etc.)  
✅ **Filtro por Categoria:** Obrigatório via parâmetro `id_area`  
✅ **Ordenação Flexível:** Permite ordenar por data, título ou autor  
✅ **Imagens Filtradas:** Apenas imagens do tipo "Top_Materia"  
✅ **bool_home Independente:** Retorna matérias independente de estarem na home  

---

## 🆚 Diferenças entre MateriasHome e MateriasCategoria

| Característica | MateriasHome | MateriasCategoria |
|----------------|--------------|-------------------|
| **Paginação** | Limite simples | Paginação completa |
| **Filtro Categoria** | ❌ Não | ✅ Sim (obrigatório) |
| **bool_home** | Apenas `true` | Independente |
| **bool_onLine** | Apenas `true` | Apenas `true` |
| **Imagens** | Todos os tipos | Apenas "Top_Materia" |
| **Ordenação** | Fixa (created_at desc) | Flexível |
| **Uso** | Home do site | Páginas de categoria |

---

## 📚 Arquivos Modificados

### 1. **Repository**
- **Arquivo:** `app/Repositories/MateriaRepository.php`
- **Método:** `getMateriasCategoria()`
- **Função:** Query com filtros, paginação e eager loading de imagens

### 2. **Service**
- **Arquivo:** `app/Services/MateriaService.php`
- **Método:** `getMateriasCategoria()`
- **Função:** Camada intermediária entre Controller e Repository

### 3. **Controller**
- **Arquivo:** `app/Http/Controllers/MateriaController.php`
- **Método:** `materiasCategoria()`
- **Função:** Validação, tratamento de erros e documentação Swagger

### 4. **Rotas**
- **Arquivo:** `routes/api.php`
- **Rota:** `GET /api/MateriasCategoria`

### 5. **Documentação**
- **Arquivo:** `MATERIAS_ENDPOINTS.md`
- **Atualização:** Adicionado detalhes do novo endpoint

---

## 🧪 Como Testar

### 1. Via Swagger UI
```
http://127.0.0.1:3001/api/documentation
```
1. Acesse a URL acima
2. Procure por "Matérias" na lista
3. Encontre "GET /MateriasCategoria"
4. Clique em "Try it out"
5. Preencha `id_area` (obrigatório)
6. Execute

### 2. Via cURL
```bash
curl -X GET "http://127.0.0.1:3001/api/MateriasCategoria?id_area=1&page=1&perPage=10" \
  -H "Accept: application/json"
```

### 3. Via Postman
```
GET http://127.0.0.1:3001/api/MateriasCategoria
Query Params:
  - id_area: 1
  - page: 1
  - perPage: 10
  - orderBy: created_at
  - orderDirection: desc
```

---

## ✅ Status

**Implementação:** ✅ Concluída  
**Documentação Swagger:** ✅ Atualizada  
**Documentação MD:** ✅ Atualizada  
**Testes:** ⏳ Aguardando testes

---

## 🎉 Pronto para Uso!

O endpoint está totalmente implementado, documentado e pronto para ser utilizado no frontend.
