<?php

namespace App\Http\Controllers;

use App\Models\Board;
use Illuminate\Http\Request;

class BoardController extends Controller
{
    private CONST ERROR_MESSAGE = ['status' => 'failed'];

    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function get()
    {
        return Board::with('tasks')->orderBy('sort')->get();
    }

    public function create()
    {
        $boardData = request(['title', 'sort']);

        if (Board::create($boardData)) {
            return $this->get();
        }

        return self::ERROR_MESSAGE;
    }

    public function update($id)
    {
        $board = Board::find($id);

        $boardData = request(['title', 'sort']);

        if ($board) {
            foreach ($boardData as $key => $data) {
                $board->$key = $data;
            }

            $board->save();

            return $this->get();
        }

        return self::ERROR_MESSAGE;
    }

    public function delete($id)
    {
        $board = Board::find($id);

        if ($board) {
            $board->delete();

            return $this->get();
        }

        return self::ERROR_MESSAGE;
    }
}
