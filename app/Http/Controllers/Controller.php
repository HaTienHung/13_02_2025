<?php

namespace App\Http\Controllers;

/**
 * @OA\Info(title="App", version="0.1")
 * @OA\PathItem (
 *     path="/api/doc",
 *     ),
 * @OA\SecurityScheme(
 *      securityScheme="bearerAuth",
 *      in="header",
 *      name="bearerAuth",
 *      type="http",
 *      scheme="bearer",
 *      bearerFormat="JWT",
 * ),
 */
abstract class Controller
{
    //
}
