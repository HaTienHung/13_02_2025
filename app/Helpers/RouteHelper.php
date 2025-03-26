<?php

namespace App\Helpers;

use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

class RouteHelper
{
    public static function includeRouteFiles(string $folder)
    {
        $dirIterator = new RecursiveDirectoryIterator($folder);

        /** @var RecursiveDirectoryIterator | RecursiveIteratorIterator $it */
        $it = new RecursiveIteratorIterator($dirIterator);

        while ($it->valid()) {
            if (
                !$it->isDot() // Không phải thư mục "." hay ".."
                && $it->isFile() // Chỉ lấy file, không lấy thư mục
                && $it->isReadable() // Chỉ lấy file có thể đọc được
                && $it->current()->getExtension() === 'php'
            ) // Chỉ lấy file có đuôi .php
            {
                require $it->key(); // Import file vào hệ thống
            }
            $it->next(); // Chuyển đến file tiếp theo
        }
    }
}
