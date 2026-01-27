<?php

namespace App\Http\Controllers;

/**
 * @OA\Info(
 *     title="AERANERD API",
 *     version="1.0.0",
 *     description="API para gerenciamento de áreas, matérias e escritores do sistema AERANERD",
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
 */
class ApiDocumentation
{
    //
}