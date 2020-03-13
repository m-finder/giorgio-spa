<?php

namespace GiorgioSpa\Http\Controllers;

use GiorgioSpa\Models\AdminApiLog;
use Illuminate\Http\Request;

class AdminApiLogController extends Controller
{
    public function lists()
    {
        $page = request('limit', 20);
        $sort = request('sort', 'desc');
        $logs = AdminApiLog::user()->orderBy('id', $sort)->paginate($page);
        return $this->success($logs);
    }
}
