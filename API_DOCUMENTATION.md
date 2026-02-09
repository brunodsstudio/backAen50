# Documentação da API AERANERD

Este projeto inclui documentação completa da API usando Swagger/OpenAPI 3.0.

## Acessando a Documentação

### Via Browser
Após iniciar o servidor Laravel, acesse:
```
http://localhost:8000/api/documentation
```

### Via JSON
Para acessar a documentação em formato JSON:
```
http://localhost:8000/api/api-docs.json
```

## Iniciando o Servidor

Para iniciar o servidor de desenvolvimento e acessar a documentação:

```bash
php artisan serve
```

Em seguida, acesse http://localhost:8000/api/documentation

## Gerando/Atualizando a Documentação

Sempre que você modificar as anotações Swagger nos controllers ou models, execute:

```bash
php artisan l5-swagger:generate
```

## Estrutura da Documentação

A documentação da API inclui:

### Endpoints Documentados

#### Autenticação
- `POST /api/login` - Fazer login no sistema
- `POST /api/register` - Registrar novo usuário

#### Áreas
- `GET /api/areas` - Listar todas as áreas
- `GET /api/areas/{id}` - Obter uma área específica
- `POST /api/areas` - Criar uma nova área

#### Matérias
- `GET /api/materias` - Listar todas as matérias
- `GET /api/materias/{id}` - Obter uma matéria específica

### Schemas Documentados
- **Area** - Modelo de área com todas as propriedades
- **Materia** - Modelo de matéria com todas as propriedades
- **User** - Modelo de usuário para autenticação

### Autenticação JWT
A API utiliza JWT Bearer tokens para autenticação. Para endpoints protegidos:

1. Faça login através do endpoint `/api/login`
2. Use o token retornado no header Authorization: `Bearer {token}`

## Configuração

### Arquivo de Configuração
As configurações do Swagger estão em `config/l5-swagger.php`.

### Principais Configurações
- **Título da API**: AERANERD API Documentation
- **Rota de Documentação**: `/api/documentation`
- **Formato**: JSON (padrão)
- **Diretórios de Scan**: `app/` (escaneia controllers e models)

## Adicionando Nova Documentação

### Para novos endpoints:
1. Adicione anotações `@OA\` no método do controller
2. Defina o path, método HTTP, parâmetros e responses
3. Execute `php artisan l5-swagger:generate`

### Para novos models:
1. Adicione anotação `@OA\Schema` na classe do model
2. Defina todas as propriedades com tipos e descrições
3. Execute `php artisan l5-swagger:generate`

### Exemplo de Anotação de Endpoint:
```php
/**
 * @OA\Get(
 *     path="/areas",
 *     tags={"Areas"},
 *     summary="Listar todas as áreas",
 *     description="Retorna uma lista de todas as áreas disponíveis",
 *     @OA\Response(
 *         response=200,
 *         description="Lista de áreas retornada com sucesso",
 *         @OA\JsonContent(
 *             type="array",
 *             @OA\Items(ref="#/components/schemas/Area")
 *         )
 *     )
 * )
 */
```

### Exemplo de Anotação de Schema:
```php
/**
 * @OA\Schema(
 *     schema="Area",
 *     type="object",
 *     title="Area",
 *     description="Modelo de Área",
 *     @OA\Property(property="int_Id", type="integer", description="ID da área"),
 *     @OA\Property(property="vchr_AreaNome", type="string", description="Nome da área")
 * )
 */
```

## Troubleshooting

### Erro "Required @OA\Info() not found"
- Certifique-se que o arquivo `ApiDocumentation.php` existe em `app/Http/Controllers/`
- Execute `php artisan l5-swagger:generate`

### Documentação não atualiza
- Execute `php artisan l5-swagger:generate` após qualquer mudança
- Limpe o cache se necessário: `php artisan config:clear`

### Página de documentação não carrega
- Verifique se o servidor está rodando
- Confirme que a rota `/api/documentation` está configurada
- Verifique o arquivo `storage/api-docs/api-docs.json` foi gerado