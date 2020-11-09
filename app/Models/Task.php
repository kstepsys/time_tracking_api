<?php

namespace App\Models;

use App\Http\Resources\TaskResource;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Services\JwtTokenService;

class Task extends Model
{
    use SoftDeletes, HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'comment',
        'date',
        'time_spent',
        'user_id'
    ];

    /**
     * gets paginated user tasks list
     * @param String $dateFrom
     * @param String $dateTo
     * @return Array
     */
    public static function getPaginatedUserTasks(string $dateFrom, string $dateTo): array
    {
        $tasks = Task::where([
            ['user_id', JwtTokenService::getUserId()],
            ['date', '>=', $dateFrom],
            ['date', '<=', $dateTo]
        ])
        ->orderByDesc('created_at')
        ->paginate(10);

        return [
            'tasks' => TaskResource::collection($tasks),
            'pages' => $tasks->lastPage()
        ];
    }

    /**
     * gets complete user tasks list within range
     * @param String $dateFrom
     * @param String $dateTo
     * @return Array
     */
    public static function getUserTasksExport(string $dateFrom, string $dateTo): Object
    {
        $tasks = Task::where([
            ['user_id', JwtTokenService::getUserId()],
            ['date', '>=', $dateFrom],
            ['date', '<=', $dateTo]
        ])
        ->orderByDesc('created_at')
        ->get();

        return TaskResource::collection($tasks);
    }

    /**
     * stores a new task in the database
     * @param Array $input
     * @return App\Task
     */
    public static function createTask(array $taskInput): Task
    {
        $taskInput['user_id'] = JwtTokenService::getUserId();

        return Task::create($taskInput);
    }
}
