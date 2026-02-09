<?php

namespace App\Http\Controllers;

/**
 * @OA\Schema(
 *     schema="Atracao",
 *     type="object",
 *     title="Atração",
 *     required={"nome", "tipo_atracao_id"},
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="nome", type="string", example="Banda Anime Rock"),
 *     @OA\Property(property="tipo_atracao_id", type="integer", example=1),
 *     @OA\Property(property="link_foto", type="string", example="https://example.com/banda.jpg"),
 *     @OA\Property(property="link_instagram", type="string", example="https://instagram.com/banda"),
 *     @OA\Property(property="link_perfil", type="string", example="https://example.com/perfil"),
 *     @OA\Property(property="descricao", type="string", example="Banda especializada em covers de animes"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 *
 * @OA\Schema(
 *     schema="AtracaoRequest",
 *     type="object",
 *     required={"nome", "tipo_atracao_id"},
 *     @OA\Property(property="nome", type="string", example="Banda Anime Rock"),
 *     @OA\Property(property="tipo_atracao_id", type="integer", example=1),
 *     @OA\Property(property="link_foto", type="string", example="https://example.com/banda.jpg"),
 *     @OA\Property(property="link_instagram", type="string", example="https://instagram.com/banda"),
 *     @OA\Property(property="link_perfil", type="string", example="https://example.com/perfil"),
 *     @OA\Property(property="descricao", type="string", example="Banda especializada em covers de animes")
 * )
 * 
 * @OA\Schema(
 *     schema="Concurso",
 *     type="object",
 *     title="Concurso",
 *     required={"nome", "tipo_concurso_id"},
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="nome", type="string", example="Concurso de Cosplay"),
 *     @OA\Property(property="tipo_concurso_id", type="integer", example=1),
 *     @OA\Property(property="link_foto", type="string", example="https://example.com/concurso.jpg"),
 *     @OA\Property(property="descricao", type="string", example="Melhor cosplay do evento"),
 *     @OA\Property(property="datas_horas", type="string", example="15/06/2026 às 14h - Palco Principal"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 *
 * @OA\Schema(
 *     schema="ConcursoRequest",
 *     type="object",
 *     required={"nome", "tipo_concurso_id"},
 *     @OA\Property(property="nome", type="string", example="Concurso de Cosplay"),
 *     @OA\Property(property="tipo_concurso_id", type="integer", example=1),
 *     @OA\Property(property="link_foto", type="string", example="https://example.com/concurso.jpg"),
 *     @OA\Property(property="descricao", type="string", example="Melhor cosplay do evento"),
 *     @OA\Property(property="datas_horas", type="string", example="15/06/2026 às 14h - Palco Principal")
 * )
 * 
 * @OA\Schema(
 *     schema="Evento",
 *     type="object",
 *     title="Evento",
 *     required={"nome", "descricao"},
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="nome", type="string", example="Anime Festival 2026"),
 *     @OA\Property(property="descricao", type="string", example="O maior evento de anime do Brasil"),
 *     @OA\Property(property="realizacao", type="string", format="date-time", example="2026-06-15 10:00:00"),
 *     @OA\Property(property="link_foto", type="string", example="https://example.com/foto.jpg"),
 *     @OA\Property(property="link_logo", type="string", example="https://example.com/logo.png"),
 *     @OA\Property(property="link_site", type="string", example="https://animefestival.com"),
 *     @OA\Property(property="link_instagram", type="string", example="https://instagram.com/animefestival"),
 *     @OA\Property(property="link_video", type="string", example="https://youtube.com/watch?v=xxx"),
 *     @OA\Property(property="link_x", type="string", example="https://x.com/animefestival"),
 *     @OA\Property(property="link_tiktok", type="string", example="https://tiktok.com/@animefestival"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 *
 * @OA\Schema(
 *     schema="EventoRequest",
 *     type="object",
 *     required={"nome", "descricao"},
 *     @OA\Property(property="nome", type="string", example="Anime Festival 2026"),
 *     @OA\Property(property="descricao", type="string", example="O maior evento de anime do Brasil"),
 *     @OA\Property(property="realizacao", type="string", format="date-time", example="2026-06-15 10:00:00"),
 *     @OA\Property(property="link_foto", type="string", example="https://example.com/foto.jpg"),
 *     @OA\Property(property="link_logo", type="string", example="https://example.com/logo.png"),
 *     @OA\Property(property="link_site", type="string", example="https://animefestival.com"),
 *     @OA\Property(property="link_instagram", type="string", example="https://instagram.com/animefestival"),
 *     @OA\Property(property="link_video", type="string", example="https://youtube.com/watch?v=xxx"),
 *     @OA\Property(property="link_x", type="string", example="https://x.com/animefestival"),
 *     @OA\Property(property="link_tiktok", type="string", example="https://tiktok.com/@animefestival"),
 *     @OA\Property(
 *         property="agendas",
 *         type="array",
 *         @OA\Items(
 *             type="object",
 *             @OA\Property(property="data", type="string", format="date", example="2026-06-15"),
 *             @OA\Property(property="endereco", type="string", example="Av. Paulista, 1000"),
 *             @OA\Property(property="cidade", type="string", example="São Paulo")
 *         )
 *     ),
 *     @OA\Property(
 *         property="atracoes",
 *         type="array",
 *         @OA\Items(type="integer", example=1)
 *     ),
 *     @OA\Property(
 *         property="concursos",
 *         type="array",
 *         @OA\Items(type="integer", example=1)
 *     )
 * )
 */
class EventoSchemas
{
    // Esta classe apenas contém as definições de schemas para o Swagger
}
