<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\todolist;
use App\Rules\AlphaOrAlphanumeric;
use Illuminate\Http\Request;

class TodoListController extends Controller
{
    public function index()
    {
        $todo = todolist::orderBy("updated_at", "desc")->paginate(5);
        return view("todolist.show", compact("todo"));
    }

    public function store(Request $request)
    {
        try {
            $validate = $request->validate([
                "task" => ["required", new AlphaOrAlphanumeric],
            ]);

            todolist::create([
                "task" => $validate["task"],
            ]);

            $todo = todolist::orderBy("updated_at", "desc")->paginate(5);
            return view("todolist.itemlist", compact("todo"));
        } catch (\Throwable $th) {
            return response()->json(["message" => "Something Went Wrong", "error" => $th->getCode()], 500);
        }

    }

    public function destroy($task)
    {
        try {
            todolist::where("task", $task)->forceDelete();
            return response()->json(["message" => "success"], 200);
        } catch (\Throwable $th) {
            return response()->json(["message" => "Something Went Wrong", "error" => $th->getMessage()], 500);
        }
    }

    public function update($task)
    {
        try {
            $taskitem = todolist::where("task", $task)->first();
            $taskitem->update([
                "status" => "1",
            ]);
            $taskitem->save();
            $todo = todolist::orderBy("updated_at", "desc")->paginate(5);
            return view("todolist.itemlist", compact("todo"));

        } catch (\Throwable $th) {
            return response()->json(["message" => "Something Went Wrong", "error" => $th->getMessage()], 500);
        }
    }
}
