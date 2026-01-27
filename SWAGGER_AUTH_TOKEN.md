# 🔐 Token JWT Bearer para Swagger

## Token Gerado

**Token JWT Bearer:**
```
eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vbG9jYWxob3N0IiwiaWF0IjoxNzYyNTU1NTc5LCJleHAiOjE3NjI1NTkxNzksIm5iZiI6MTc2MjU1NTU3OSwianRpIjoicXNMM29CZ3ZON29NV29rcyIsInN1YiI6IjIiLCJwcnYiOiIyM2JkNWM4OTQ5ZjYwMGFkYjM5ZTcwMWM0MDA4NzJkYjdhNTk3NmY3In0.4XTvrc-Ah-6ti4qykVnmZ0GMYiHqEvg6SzaEjLikN-U
```

## 📋 Credenciais do Usuário de Teste

- **Email:** test@aeranerd.com
- **Senha:** password123
- **Validade do Token:** 60 minutos

## 🚀 Como usar o Token no Swagger

### Passo 1: Acessar a Documentação
Abra seu navegador e acesse:
```
http://127.0.0.1:8000/api/documentation
```

### Passo 2: Autorizar
1. Clique no botão **"Authorize"** (ícone de cadeado 🔒) no topo da página
2. Na janela que abrir, você verá o campo **bearerAuth (http, Bearer)**
3. Cole o token JWT acima no campo **"Value"**
4. Clique em **"Authorize"**
5. Clique em **"Close"**

### Passo 3: Testar Endpoints
Agora você pode testar todos os endpoints protegidos! O token será incluído automaticamente no header `Authorization: Bearer {token}`.

## 🔄 Gerar Novo Token

Se o token expirar ou você precisar de um novo, execute:

```bash
php artisan token:generate-test
```

### Opções Personalizadas:

```bash
# Token com email personalizado
php artisan token:generate-test --email=seu@email.com

# Token com senha personalizada
php artisan token:generate-test --password=suaSenha123

# Token com nome personalizado
php artisan token:generate-test --name="Seu Nome"

# Todas as opções juntas
php artisan token:generate-test --email=admin@aeranerd.com --password=admin123 --name="Admin User"
```

## 📝 Testando a Autenticação

### Via Swagger UI
1. Após autorizar, teste o endpoint `/api/auth/me`
2. Clique em "Try it out" → "Execute"
3. Você deve ver os dados do usuário autenticado

### Via cURL
```bash
curl -X GET "http://127.0.0.1:8000/api/auth/me" \
  -H "Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9..."
```

### Via Postman
1. Crie uma nova requisição
2. Vá para a aba "Authorization"
3. Selecione "Bearer Token"
4. Cole o token no campo "Token"

## 🔑 Endpoints que Requerem Autenticação

Atualmente, os seguintes endpoints podem ser protegidos:
- `POST /api/auth/logout` - Fazer logout
- `POST /api/auth/refresh` - Renovar token
- `POST /api/auth/me` - Obter dados do usuário autenticado

## 🆕 Criar Novo Usuário via API

Você também pode criar novos usuários através do endpoint de registro:

**Endpoint:** `POST /api/register`

**Body (JSON):**
```json
{
  "name": "Novo Usuário",
  "email": "novo@usuario.com",
  "password": "senha123"
}
```

Isso retornará um token JWT automaticamente.

## 🔒 Fazer Login via API

**Endpoint:** `POST /api/login`

**Body (JSON):**
```json
{
  "email": "test@aeranerd.com",
  "password": "password123"
}
```

**Resposta:**
```json
{
  "access_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9...",
  "token_type": "bearer",
  "expires_in": 3600
}
```

## ⚙️ Configuração JWT

A configuração JWT está no arquivo `config/jwt.php`. 

**Principais configurações:**
- **TTL (Time To Live):** 60 minutos (padrão)
- **Algoritmo:** HS256
- **Secret Key:** Definido em `.env` como `JWT_SECRET`

Para alterar o tempo de expiração, edite o arquivo `.env`:
```env
JWT_TTL=60  # minutos
```

## 🛠️ Troubleshooting

### Token expirado
Se receber erro "Token has expired":
- Gere um novo token com `php artisan token:generate-test`
- Ou use o endpoint `/api/auth/refresh` para renovar

### Unauthorized (401)
- Verifique se copiou o token completo
- Confirme que o token está no formato correto no Swagger
- Não adicione "Bearer" manualmente, apenas cole o token

### Secret is not set
Se receber este erro:
```bash
php artisan jwt:secret
```

## 📚 Documentação Adicional

- [Documentação L5-Swagger](https://github.com/DarkaOnLine/L5-Swagger)
- [Documentação JWT-Auth](https://jwt-auth.readthedocs.io/)
- [OpenAPI Specification](https://swagger.io/specification/)
