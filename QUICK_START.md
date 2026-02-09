# 🚀 Quick Start - Swagger com Autenticação JWT

## ⚡ Acesso Rápido

**URL da Documentação:** http://127.0.0.1:8000/api/documentation

**Token JWT Bearer (válido por 60 min):**
```
eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vbG9jYWxob3N0IiwiaWF0IjoxNzYyNTU1NTc5LCJleHAiOjE3NjI1NTkxNzksIm5iZiI6MTc2MjU1NTU3OSwianRpIjoicXNMM29CZ3ZON29NV29rcyIsInN1YiI6IjIiLCJwcnYiOiIyM2JkNWM4OTQ5ZjYwMGFkYjM5ZTcwMWM0MDA4NzJkYjdhNTk3NmY3In0.4XTvrc-Ah-6ti4qykVnmZ0GMYiHqEvg6SzaEjLikN-U
```

## 🔑 Credenciais
- Email: `test@aeranerd.com`
- Senha: `password123`

## 📖 3 Passos para Testar

1. **Inicie o servidor** (se ainda não estiver rodando):
   ```bash
   php artisan serve
   ```

2. **Acesse a documentação**:
   - Abra http://127.0.0.1:8000/api/documentation

3. **Autorize com JWT**:
   - Clique no botão **🔒 Authorize** no topo
   - Cole o token acima
   - Clique em **Authorize** → **Close**

## ✅ Pronto!
Agora você pode testar todos os endpoints diretamente no Swagger!

---

**Gerar novo token:**
```bash
php artisan token:generate-test
```

**Documentação completa:** Ver arquivo `SWAGGER_AUTH_TOKEN.md`
