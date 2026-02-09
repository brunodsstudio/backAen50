# API de Galeria - Documentação Completa

## 📋 Resumo

Este recurso permite buscar imagens armazenadas no **Amazon S3** com paginação e autenticação JWT.

## 🔗 Endpoint

```
GET /api/Galerias/{PastaS3}/{Pagina}
```

### Parâmetros

| Parâmetro | Tipo | Obrigatório | Descrição | Padrão |
|-----------|------|-------------|-----------|--------|
| `PastaS3` | string | ✅ Sim | Nome da pasta no S3 | - |
| `Pagina` | integer | ✅ Sim | Número da página (≥ 1) | - |
| `quantidadePorPagina` | integer | ❌ Não | Itens por página (1-100) | 25 |

### Exemplo de Requisição

```bash
GET /api/Galerias/evento-cosplay-2024/1?quantidadePorPagina=10
Authorization: Bearer SEU_TOKEN_JWT
Accept: application/json
```

## 🔐 Autenticação

O endpoint **requer autenticação JWT**.

### Como obter o token:

```bash
POST /api/login
Content-Type: application/json

{
  "email": "usuario@exemplo.com",
  "password": "senha123"
}
```

**Resposta:**
```json
{
  "access_token": "eyJ0eXAiOiJKV1QiLCJhbGc...",
  "token_type": "bearer",
  "expires_in": 3600
}
```

Use o `access_token` no header `Authorization: Bearer {token}`.

## 📤 Respostas

### ✅ Sucesso (200 OK)

```json
{
  "success": true,
  "data": [
    {
      "foto": "https://aeranerd.s3.sa-east-1.amazonaws.com/images/galerias/evento-cosplay-2024/foto001.jpg"
    },
    {
      "foto": "https://aeranerd.s3.sa-east-1.amazonaws.com/images/galerias/evento-cosplay-2024/foto002.jpg"
    }
  ],
  "pagination": {
    "pagina_atual": 1,
    "itens_por_pagina": 25,
    "total_itens": 150,
    "total_paginas": 6
  }
}
```

### ❌ Erros

#### 401 - Não Autenticado
```json
{
  "message": "Unauthenticated."
}
```
**Causa:** Token ausente, inválido ou expirado.

#### 404 - Pasta Não Encontrada
```json
{
  "success": false,
  "message": "Nenhuma imagem encontrada na pasta especificada"
}
```
**Causa:** A pasta não existe no S3 ou está vazia.

#### 422 - Parâmetros Inválidos
```json
{
  "success": false,
  "message": "O número da página deve ser maior ou igual a 1"
}
```
**Causa:** Página < 1 ou quantidadePorPagina fora do intervalo (1-100).

#### 500 - Erro Interno
```json
{
  "success": false,
  "message": "Erro ao buscar imagens do S3",
  "error": "Detalhes do erro (apenas em debug mode)"
}
```
**Causa:** Erro de conexão com S3 ou erro interno do servidor.

## 📁 Estrutura de Arquivos Criados

```
backAen50/
├── app/
│   ├── Helpers/
│   │   └── PaginacaoHelper.php          ✅ Helper de paginação
│   └── Http/
│       └── Controllers/
│           └── GaleriaController.php    ✅ Controller principal
├── routes/
│   └── api.php                          ✅ Rota adicionada
├── GALERIA_API_GUZZLE_EXAMPLES.md       ✅ Exemplos GuzzleHttp
└── galeria-api-tests.http               ✅ Testes HTTP
```

## 🛠️ Funcionalidades Implementadas

### ✅ 1. Helper de Paginação

**Arquivo:** `app/Helpers/PaginacaoHelper.php`

```php
PaginacaoHelper::paginar($paginaAtual, $itensPorPagina, $itens);
```

### ✅ 2. Controller com Autenticação JWT

**Arquivo:** `app/Http/Controllers/GaleriaController.php`

**Características:**
- ✅ Middleware `auth:api` (JWT)
- ✅ Validação de parâmetros
- ✅ Filtro de imagens por extensão (jpg, jpeg, png, gif, webp)
- ✅ Paginação customizável
- ✅ URLs completas do S3
- ✅ Tratamento de erros robusto
- ✅ Logs de erro
- ✅ Documentação Swagger completa

### ✅ 3. Rota API

**Arquivo:** `routes/api.php`

```php
Route::get('/Galerias/{pastaS3}/{pagina}', [GaleriaController::class, 'fetchGaleria'])
    ->where(['pagina' => '[0-9]+']);
```

### ✅ 4. Documentação Swagger

**Anotações completas no controller:**
- Descrição do endpoint
- Parâmetros (path e query)
- Esquema de autenticação (bearerAuth)
- Respostas (200, 401, 404, 422, 500)
- Exemplos de JSON

**Acessar Swagger UI:**
```
http://localhost:8000/api/documentation
```

### ✅ 5. Exemplos de Uso

**Arquivo:** `GALERIA_API_GUZZLE_EXAMPLES.md`

**Contém:**
- 5 exemplos completos com GuzzleHttp
- Uso em Controllers Laravel
- Scripts PHP standalone
- Requisições assíncronas
- Commands/Jobs Laravel
- Tratamento de erros
- Exemplos com cURL

## 🧪 Como Testar

### Opção 1: VS Code REST Client

1. Instale a extensão **REST Client** no VS Code
2. Abra o arquivo `galeria-api-tests.http`
3. Atualize as variáveis no topo do arquivo:
   ```
   @email = seu.email@exemplo.com
   @password = sua_senha
   @pastaS3 = nome-da-pasta-s3
   ```
4. Execute o teste **"1. Login"** clicando em "Send Request"
5. Copie o `access_token` da resposta
6. Cole na variável `@token`
7. Execute os outros testes

### Opção 2: cURL

```bash
# 1. Login
TOKEN=$(curl -s -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{"email":"usuario@exemplo.com","password":"senha123"}' \
  | jq -r '.access_token')

# 2. Buscar galeria
curl -X GET "http://localhost:8000/api/Galerias/evento-cosplay-2024/1?quantidadePorPagina=10" \
  -H "Authorization: Bearer $TOKEN" \
  -H "Accept: application/json" | jq
```

### Opção 3: GuzzleHttp (Laravel)

Veja exemplos completos no arquivo `GALERIA_API_GUZZLE_EXAMPLES.md`.

## 🌐 Configuração S3 (.env)

O arquivo `.env` já contém as configurações necessárias:

```env
AWS_ACCESS_KEY_ID=AKIA2X2BQGQM6VIIGBEL
AWS_SECRET_ACCESS_KEY=KdMlPqHW/Zf3uzYtM/Uo/4yKb6ZqoPSq6VinmS1L
AWS_DEFAULT_REGION=sa-east-1
AWS_BUCKET=aeranerd
AWS_USE_PATH_STYLE_ENDPOINT=false
```

**Estrutura esperada no S3:**
```
aeranerd/
└── images/
    └── galerias/
        ├── evento-cosplay-2024/
        │   ├── foto001.jpg
        │   ├── foto002.jpg
        │   └── ...
        ├── anime-fest-2025/
        │   └── ...
        └── ...
```

## 📊 Informações de Paginação

A resposta inclui objeto `pagination` com:

| Campo | Descrição |
|-------|-----------|
| `pagina_atual` | Página atual solicitada |
| `itens_por_pagina` | Quantidade de itens por página |
| `total_itens` | Total de imagens na pasta |
| `total_paginas` | Número total de páginas disponíveis |

## 🔧 Comandos Úteis

### Gerar documentação Swagger
```bash
cd /home/dsstudio/aeranerd/backAen50
php artisan l5-swagger:generate
```

### Limpar cache
```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
```

### Ver rotas
```bash
php artisan route:list | grep Galeria
```

## 🚀 Integração com Frontend

O frontend em [galeria.blade.php](frontAen50/resources/views/pages/galeria.blade.php) já está preparado:

```javascript
$grid.infiniteScroll({
  path: function() {
    return `/api/Galerias/{{$materia[0]->vchr_s3_storage}}/${this.pageIndex}`;
  },
  responseBody: 'json',
  outlayer: msnry,
  status: '.page-load-status',
  history: false,
});
```

**Para adicionar autenticação JWT no frontend:**

```javascript
$grid.infiniteScroll({
  path: function() {
    return `/api/Galerias/{{$materia[0]->vchr_s3_storage}}/${this.pageIndex}`;
  },
  responseBody: 'json',
  requestOptions: {
    headers: {
      'Authorization': 'Bearer ' + sessionStorage.getItem('api_token'),
      'Accept': 'application/json'
    }
  },
  outlayer: msnry,
  status: '.page-load-status',
  history: false,
});
```

## 📝 Notas Importantes

1. **Segurança**: O token JWT deve ser armazenado de forma segura (sessionStorage, localStorage ou cookie HttpOnly)
2. **HTTPS**: Em produção, sempre use HTTPS
3. **Cache**: Considere implementar cache para melhorar performance
4. **Rate Limiting**: Implemente rate limiting para evitar abuso
5. **Logs**: Todos os erros são logados automaticamente
6. **Extensões**: Apenas imagens (jpg, jpeg, png, gif, webp) são retornadas
7. **Timeout**: Ajuste timeout do GuzzleHttp para pastas grandes

## 🐛 Troubleshooting

### Erro: "Unauthenticated"
- Verifique se o token está sendo enviado corretamente
- Verifique se o token não expirou
- Faça login novamente para obter novo token

### Erro: "Nenhuma imagem encontrada"
- Verifique se a pasta existe no S3
- Verifique se há imagens na pasta
- Verifique permissões do bucket S3

### Erro: "Erro ao buscar imagens do S3"
- Verifique credenciais AWS no .env
- Verifique conectividade com AWS
- Verifique logs em `storage/logs/laravel.log`

## 📞 Suporte

Para mais informações, consulte:
- **Swagger UI**: http://localhost:8000/api/documentation
- **Exemplos GuzzleHttp**: `GALERIA_API_GUZZLE_EXAMPLES.md`
- **Testes HTTP**: `galeria-api-tests.http`
