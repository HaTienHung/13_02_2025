<?php

namespace App\Http\Controllers;

/**
 * @OA\Info(title="App", version="3.0.1")
 * @OA\PathItem (
 *     path="/api/doc",
 *     ),
 * @OA\SecurityScheme(
 *      securityScheme="bearerAuth",
 *      type="http",
 *      scheme="bearer",
 *      bearerFormat="JWT",
 * ),
 */
abstract class Controller
{
    //
}
