<?php

namespace App\Http\Controllers\API;

use App\Models\Task;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\IndexTasksRequest;

class TaskController extends Controller
{
    /**
     * Display a paginated listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(IndexTasksRequest $request)
    {
        $dateFrom = "2000-01-01";
        $dateTo = "2050-01-01";
        if ($request->has('date_from')) {
            $dateFrom = $request->date_from;
        }
        if ($request->has('date_to')) {
            $dateTo = $request->date_to;
        }
        
        return response()->json(Task::getPaginatedUserTasks($dateFrom, $dateTo));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function export(IndexTasksRequest $request)
    {
        $dateFrom = "2000-01-01";
        $dateTo = "2050-01-01";
        if ($request->has('date_from')) {
            $dateFrom = $request->date_from;
        }
        if ($request->has('date_to')) {
            $dateTo = $request->date_to;
        }
        
        return response()->json(Task::getUserTasksExport($dateFrom, $dateTo));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreTaskRequest $request)
    {
        return response()->json(Task::createTask($request->all()), 201);
    }
}
