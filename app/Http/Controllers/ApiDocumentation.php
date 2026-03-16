<?php

namespace App\Http\Controllers;

/**
 * @OA\Info(
 *     title="AERANERD API",
 *     version="1.0.0",
 *     description="API para gerenciamento de áreas, matérias, escritores e eventos GEEK do sistema AERANERD",
 *     @OA\Contact(
 *         email="contato@aeranerd.com"
 *     )
 * )
 * @OA\Server(
 *     url="/api",
 *     description="API Server"
 * )
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT"
 * )
 * @OA\Schema(
 *     schema="MateriaLinksSitemap",
 *     type="object",
 *     title="MateriaLinksSitemap",
 *     description="Modelo simplificado de Matéria para Sitemap",
 *     @OA\Property(property="vchr_LinkTitulo", type="string", description="Título do link da matéria"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", description="Data de última atualização")
 * )
 */
class ApiDocumentation
{
    //
}