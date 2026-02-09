<?php

namespace App\Helpers;

class PaginacaoHelper
{
    /**
     * Pagina um array de itens
     * 
     * @param int $paginaAtual Página atual (começando em 1)
     * @param int $itensPorPagina Quantidade de itens por página
     * @param array $itens Array de itens a serem paginados
     * @return array Array com os itens da página atual
     */
    public static function paginar(int $paginaAtual, int $itensPorPagina, array $itens): array
    {
        $totalItens = count($itens);
        $totalPaginas = ceil($totalItens / $itensPorPagina);
        
        // Garantir que a página atual seja válida
        if ($paginaAtual < 1) {
            $paginaAtual = 1;
        }
        
        if ($paginaAtual > $totalPaginas && $totalPaginas > 0) {
            $paginaAtual = $totalPaginas;
        }
        
        // Calcular o offset
        $offset = ($paginaAtual - 1) * $itensPorPagina;
        
        // Retornar o slice do array
        return array_slice($itens, $offset, $itensPorPagina);
    }
}
