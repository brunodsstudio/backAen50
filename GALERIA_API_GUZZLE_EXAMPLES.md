# Exemplo de Requisição - API Galeria

Este documento demonstra como consumir a API de Galeria utilizando GuzzleHttp no Laravel.

## Instalação do GuzzleHttp

```bash
composer require guzzlehttp/guzzle
```

## Exemplo 1: Requisição Básica com Autenticação

```php
<?php

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class GaleriaServiceExample
{
    protected $client;
    protected $apiUrl;
    protected $token;

    public function __construct()
    {
        $this->apiUrl = env('API_URL', 'http://localhost:8000/api');
        $this->token = null;
        
        $this->client = new Client([
            'base_uri' => $this->apiUrl,
            'timeout'  => 30.0,
            'verify' => false, // Para desenvolvimento local
        ]);
    }

    /**
     * Fazer login e obter token JWT
     */
    public function login($email, $password)
    {
        try {
            $response = $this->client->post('/login', [
                'json' => [
                    'email' => $email,
                    'password' => $password
                ]
            ]);

            $data = json_decode($response->getBody(), true);
            $this->token = $data['access_token'] ?? null;

            return $this->token;
        } catch (GuzzleException $e) {
            throw new \Exception('Erro ao fazer login: ' . $e->getMessage());
        }
    }

    /**
     * Buscar galeria de imagens do S3
     * 
     * @param string $pastaS3 Nome da pasta no S3
     * @param int $pagina Número da página
     * @param int $quantidadePorPagina Quantidade de itens por página (padrão: 25)
     * @return array
     */
    public function fetchGaleria($pastaS3, $pagina = 1, $quantidadePorPagina = 25)
    {
        if (!$this->token) {
            throw new \Exception('Token de autenticação não encontrado. Faça login primeiro.');
        }

        try {
            $response = $this->client->get("/Galerias/{$pastaS3}/{$pagina}", [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->token,
                    'Accept' => 'application/json',
                ],
                'query' => [
                    'quantidadePorPagina' => $quantidadePorPagina
                ]
            ]);

            return json_decode($response->getBody(), true);
        } catch (GuzzleException $e) {
            if ($e->hasResponse()) {
                $statusCode = $e->getResponse()->getStatusCode();
                $errorBody = json_decode($e->getResponse()->getBody(), true);
                
                throw new \Exception("Erro ao buscar galeria (Status {$statusCode}): " . 
                    ($errorBody['message'] ?? $e->getMessage()));
            }
            
            throw new \Exception('Erro ao buscar galeria: ' . $e->getMessage());
        }
    }
}
```

## Exemplo 2: Uso em um Controller Laravel

```php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class FrontendGaleriaController extends Controller
{
    /**
     * Exibir galeria de imagens
     */
    public function show(Request $request, $pastaS3)
    {
        try {
            // Obter o token do usuário autenticado (sessão ou JWT)
            $token = $request->user()->token ?? session('api_token');
            
            if (!$token) {
                return redirect()->route('login')->with('error', 'Você precisa estar autenticado');
            }

            $client = new Client([
                'base_uri' => env('API_URL', 'http://localhost:8000/api'),
                'timeout' => 30.0,
            ]);

            $pagina = $request->query('pagina', 1);
            $quantidadePorPagina = $request->query('quantidade', 25);

            $response = $client->get("/Galerias/{$pastaS3}/{$pagina}", [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token,
                    'Accept' => 'application/json',
                ],
                'query' => [
                    'quantidadePorPagina' => $quantidadePorPagina
                ]
            ]);

            $galeria = json_decode($response->getBody(), true);

            return view('galeria.show', [
                'imagens' => $galeria['data'],
                'pagination' => $galeria['pagination'],
                'pastaS3' => $pastaS3
            ]);

        } catch (GuzzleException $e) {
            \Log::error('Erro ao buscar galeria: ' . $e->getMessage());
            
            if ($e->hasResponse() && $e->getResponse()->getStatusCode() === 401) {
                return redirect()->route('login')->with('error', 'Sessão expirada. Faça login novamente.');
            }

            return back()->with('error', 'Erro ao carregar galeria. Tente novamente.');
        }
    }
}
```

## Exemplo 3: Requisição Simples (Script PHP Puro)

```php
<?php

require 'vendor/autoload.php';

use GuzzleHttp\Client;

// Configurações
$apiUrl = 'http://localhost:8000/api';
$email = 'usuario@exemplo.com';
$password = 'senha123';
$pastaS3 = 'evento-cosplay-2024';
$pagina = 1;

$client = new Client(['base_uri' => $apiUrl]);

try {
    // 1. Fazer login
    echo "Fazendo login...\n";
    $loginResponse = $client->post('/login', [
        'json' => [
            'email' => $email,
            'password' => $password
        ]
    ]);
    
    $loginData = json_decode($loginResponse->getBody(), true);
    $token = $loginData['access_token'];
    echo "Login realizado com sucesso!\n\n";

    // 2. Buscar galeria
    echo "Buscando galeria: {$pastaS3} - Página {$pagina}\n";
    $galeriaResponse = $client->get("/Galerias/{$pastaS3}/{$pagina}", [
        'headers' => [
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json',
        ],
        'query' => [
            'quantidadePorPagina' => 10
        ]
    ]);

    $galeriaData = json_decode($galeriaResponse->getBody(), true);
    
    echo "Sucesso! Encontradas {$galeriaData['pagination']['total_itens']} imagens\n";
    echo "Página {$galeriaData['pagination']['pagina_atual']} de {$galeriaData['pagination']['total_paginas']}\n\n";
    
    echo "Primeiras imagens:\n";
    foreach ($galeriaData['data'] as $index => $imagem) {
        echo ($index + 1) . ". {$imagem['foto']}\n";
    }

} catch (\Exception $e) {
    echo "Erro: " . $e->getMessage() . "\n";
}
```

## Exemplo 4: Requisição com Async (Múltiplas Páginas)

```php
<?php

use GuzzleHttp\Client;
use GuzzleHttp\Promise;
use GuzzleHttp\Exception\GuzzleException;

class GaleriaAsyncService
{
    protected $client;
    protected $token;

    public function __construct($token)
    {
        $this->token = $token;
        $this->client = new Client([
            'base_uri' => env('API_URL', 'http://localhost:8000/api'),
            'timeout' => 30.0,
        ]);
    }

    /**
     * Buscar múltiplas páginas de uma vez
     */
    public function fetchMultiplePaginas($pastaS3, array $paginas)
    {
        $promises = [];
        
        foreach ($paginas as $pagina) {
            $promises["pagina_{$pagina}"] = $this->client->getAsync("/Galerias/{$pastaS3}/{$pagina}", [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->token,
                    'Accept' => 'application/json',
                ]
            ]);
        }

        try {
            $results = Promise\Utils::unwrap($promises);
            
            $todasImagens = [];
            foreach ($results as $key => $response) {
                $data = json_decode($response->getBody(), true);
                $todasImagens[$key] = $data;
            }

            return $todasImagens;
        } catch (\Exception $e) {
            throw new \Exception('Erro ao buscar múltiplas páginas: ' . $e->getMessage());
        }
    }
}
```

## Exemplo 5: Uso em um Job/Command Laravel

```php
<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use GuzzleHttp\Client;

class SyncGaleriaCommand extends Command
{
    protected $signature = 'galeria:sync {pasta} {--pagina=1}';
    protected $description = 'Sincronizar galeria do S3';

    public function handle()
    {
        $pasta = $this->argument('pasta');
        $pagina = $this->option('pagina');
        
        $this->info("Sincronizando galeria: {$pasta}");

        try {
            $client = new Client([
                'base_uri' => env('API_URL', 'http://localhost:8000/api'),
            ]);

            // Fazer login como usuário administrativo
            $loginResponse = $client->post('/login', [
                'json' => [
                    'email' => env('ADMIN_EMAIL'),
                    'password' => env('ADMIN_PASSWORD')
                ]
            ]);

            $token = json_decode($loginResponse->getBody(), true)['access_token'];

            // Buscar galeria
            $response = $client->get("/Galerias/{$pasta}/{$pagina}", [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token,
                    'Accept' => 'application/json',
                ]
            ]);

            $data = json_decode($response->getBody(), true);
            
            $this->info("Total de imagens: {$data['pagination']['total_itens']}");
            $this->info("Página {$data['pagination']['pagina_atual']} de {$data['pagination']['total_paginas']}");

            $this->table(
                ['#', 'URL'],
                collect($data['data'])->map(fn($img, $idx) => [$idx + 1, $img['foto']])->toArray()
            );

            $this->info('Sincronização concluída!');

        } catch (\Exception $e) {
            $this->error('Erro: ' . $e->getMessage());
            return 1;
        }

        return 0;
    }
}
```

## Tratamento de Erros

### Resposta de Sucesso (200)
```json
{
    "success": true,
    "data": [
        {
            "foto": "https://aeranerd.s3.sa-east-1.amazonaws.com/images/galerias/evento-cosplay-2024/foto001.jpg"
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

### Erro de Autenticação (401)
```json
{
    "message": "Unauthenticated."
}
```

### Pasta Não Encontrada (404)
```json
{
    "success": false,
    "message": "Nenhuma imagem encontrada na pasta especificada"
}
```

### Parâmetros Inválidos (422)
```json
{
    "success": false,
    "message": "O número da página deve ser maior ou igual a 1"
}
```

### Erro Interno (500)
```json
{
    "success": false,
    "message": "Erro ao buscar imagens do S3",
    "error": "Detalhes do erro (apenas em ambiente de desenvolvimento)"
}
```

## Testando a API

### Usando cURL
```bash
# 1. Fazer login
curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{"email":"usuario@exemplo.com","password":"senha123"}'

# 2. Buscar galeria (substitua SEU_TOKEN pelo token retornado no login)
curl -X GET "http://localhost:8000/api/Galerias/evento-cosplay-2024/1?quantidadePorPagina=10" \
  -H "Authorization: Bearer SEU_TOKEN" \
  -H "Accept: application/json"
```

## Notas Importantes

1. **Token JWT**: O token expira após um período configurado. Implemente lógica de refresh token quando necessário.
2. **HTTPS**: Em produção, sempre use HTTPS para proteger o token JWT.
3. **Rate Limiting**: A API pode ter limitação de taxa. Implemente retry logic se necessário.
4. **Timeout**: Ajuste o timeout conforme o tamanho esperado da resposta e velocidade da rede.
5. **Cache**: Considere implementar cache para respostas de galeria que não mudam frequentemente.
