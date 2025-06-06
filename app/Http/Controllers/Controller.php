<?php

namespace App\Http\Controllers;

/**
 * @OA\OpenApi(
 *     openapi="3.0.1"
 * )
 * @OA\Info(
 *     title="App",
 *     version="0.1"
 * )
 * @OA\PathItem(
 *     path="/api/doc"
 * )
 * @OA\SecurityScheme(
 *      securityScheme="bearerAuth",
 *      type="http",
 *      scheme="bearer",
 *      bearerFormat="JWT"
 * )
 */
abstract class Controller
{
    //
}
