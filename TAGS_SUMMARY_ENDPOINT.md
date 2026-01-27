# Endpoint: Resumo de Tags

## Descrição
Endpoint que retorna um resumo das tags mais utilizadas nas últimas matérias publicadas no sistema.

## URL
```
GET /api/materias/tags/summary
```

## Parâmetros de Query

| Parâmetro | Tipo    | Obrigatório | Padrão | Descrição |
|-----------|---------|-------------|--------|-----------|
| limit     | integer | Não         | 50     | Número de matérias a analisar para extrair as tags |

## Funcionamento

1. Busca as últimas N matérias publicadas (online) que possuem tags
2. Extrai todas as tags do campo `vchr_tags` (separadas por `;` ou `,`)
3. Conta a frequência de uso de cada tag
4. Retorna as tags ordenadas por frequência (mais usadas primeiro)

## Resposta

### Sucesso (200)

```json
{
  "success": true,
  "data": [
    {
      "tag": "MCU",
      "count": 5
    },
    {
      "tag": "Batman",
      "count": 4
    },
    {
      "tag": "Cosplay",
      "count": 4
    },
    {
      "tag": "Disney Plus",
      "count": 2
    }
  ],
  "total_tags": 150,
  "materias_analyzed": 50
}
```

### Estrutura da Resposta

| Campo              | Tipo    | Descrição |
|--------------------|---------|-----------|
| success            | boolean | Status da operação |
| data               | array   | Lista de tags com suas contagens |
| data[].tag         | string  | Nome da tag |
| data[].count       | integer | Número de vezes que a tag aparece |
| total_tags         | integer | Total de tags únicas encontradas |
| materias_analyzed  | integer | Número de matérias analisadas |

### Erro (500)

```json
{
  "error": "Could not retrieve tags summary."
}
```

## Exemplos de Uso

### Exemplo 1: Buscar resumo das últimas 50 matérias (padrão)
```bash
curl http://127.0.0.1:3001/api/materias/tags/summary
```

### Exemplo 2: Buscar resumo das últimas 100 matérias
```bash
curl http://127.0.0.1:3001/api/materias/tags/summary?limit=100
```

### Exemplo 3: Buscar resumo das últimas 20 matérias
```bash
curl http://127.0.0.1:3001/api/materias/tags/summary?limit=20
```

### Exemplo 4: Usando JavaScript (Fetch API)
```javascript
fetch('http://127.0.0.1:3001/api/materias/tags/summary?limit=50')
  .then(response => response.json())
  .then(data => {
    console.log('Total de tags:', data.total_tags);
    console.log('Tags mais usadas:');
    data.data.slice(0, 10).forEach(item => {
      console.log(`${item.tag}: ${item.count} vezes`);
    });
  });
```

### Exemplo 5: Usando PHP (Guzzle)
```php
use GuzzleHttp\Client;

$client = new Client();
$response = $client->get('http://127.0.0.1:3001/api/materias/tags/summary', [
    'query' => ['limit' => 50]
]);

$data = json_decode($response->getBody(), true);
echo "Total de tags: " . $data['total_tags'] . "\n";
foreach (array_slice($data['data'], 0, 10) as $item) {
    echo "{$item['tag']}: {$item['count']} vezes\n";
}
```

## Casos de Uso

### 1. Nuvem de Tags
Use os dados para criar uma nuvem de tags interativa, onde o tamanho de cada tag é proporcional à sua frequência de uso.

### 2. Tags Trending
Exiba as 10 tags mais populares no sidebar ou footer do site.

### 3. Sugestões de Tags
Ao criar uma nova matéria, sugira tags populares para o autor.

### 4. Análise de Conteúdo
Identifique os temas mais abordados no portal em um determinado período.

### 5. Navegação por Tags
Crie uma página de navegação por tags populares para melhorar o SEO e a experiência do usuário.

## Notas Técnicas

- O campo `vchr_tags` aceita separadores `;` ou `,`
- Espaços em branco antes e depois das tags são removidos automaticamente
- Tags vazias são ignoradas
- Apenas matérias com `bool_onLine = true` são consideradas
- As matérias são ordenadas por `created_at DESC` antes da análise
- O resultado é ordenado por frequência (descendente)

## Documentação Swagger

Este endpoint está documentado no Swagger da aplicação. Acesse:
```
http://127.0.0.1:3001/api/documentation
```
