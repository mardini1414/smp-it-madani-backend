<?php

namespace Modules\Dashboard\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\API\ResponseTrait;
use Modules\Dashboard\Services\DashboardService;

class DashboardController extends BaseController
{
    use ResponseTrait;

    private $dashboardService;

    public function __construct()
    {
        $this->dashboardService = new DashboardService();
    }

    public function getAllTotal()
    {
        $data = $this->dashboardService->getAllTotal();
        return $this->respond(['data' => $data]);
    }

    public function getPieChart()
    {
        $data = $this->dashboardService->getPieChart();
        return $this->respond(['data' => $data]);
    }

    public function getLineChart()
    {
        $data = $this->dashboardService->getLineChart();
        return $this->respond(['data' => $data]);
    }
}