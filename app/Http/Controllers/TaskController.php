<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    private CONST ERROR_MESSAGE = ['status' => 'failed'];
    private $board;

    public function __construct(BoardController $board)
    {
        $this->middleware('auth:api');
        $this->board = $board;
    }

    public function create()
    {
        $taskData = request(['title', 'sort', 'board_id']);

        if (Task::create($taskData)) {
            return $this->board->get();
        }

        return self::ERROR_MESSAGE;
    }

    public function update($id)
    {
        $task = Task::find($id);

        $taskData = request(['title', 'sort', 'board_id']);

        if ($task) {
            foreach ($taskData as $key => $data) {
                $task->$key = $data;
            }

            $task->save();

            return $this->board->get();
        }

        return self::ERROR_MESSAGE;
    }

    public function delete($id)
    {
        $task = Task::find($id);

        if ($task) {
            $task->delete();

            return $this->board->get();
        }

        return self::ERROR_MESSAGE;
    }
}
