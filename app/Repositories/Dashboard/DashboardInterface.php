<?php

namespace App\Repositories\Dashboard;

use App\Repositories\BaseRepositoryInterface;

interface DashboardInterface
{
    public function getStats() : array;
}
