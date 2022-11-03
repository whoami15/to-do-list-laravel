<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TasksController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $tasks = Task::all();

        if (isset($_GET['query'])) {
            $search = $_GET['query'];
            $tasks = DB::table('tasks')->where('name', 'like', '%' . $search . '%')->paginate(4);
            $tasks->appends($request->all());
            return view('templates.tasks-list', compact('tasks'));
        } else {
            $tasks = Task::paginate(4);
            $tasks->appends($request->all());
            return view('templates.tasks-list', compact('tasks'));
        }
    }

    public function create()
    {
        return view('templates.create-task');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $task  = Task::create([
            'name' => $request->name,
            'label' => $request->label
        ]);
        return redirect()->route('tasks', ['task' => $task]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $task = Task::find($id);

        if (empty($task)) return response(['message' => 'Task not found', 404]);

        return response($task, 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $task = Task::find($id);

        if (empty($task)) return response(['message' => 'Task not found'], 404);

        return view('templates.edit-task', ['task' => $task]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $task = Task::find($id);

        if (empty($task)) return response(['message' => 'Task not found'], 404);

        $task->update($request->all());

        return redirect()->route('tasks', ['task' => $task]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
        $task = Task::find($id);

        Task::destroy($id);

        if (empty($task)) return response(['message' => 'Task not found', 404]);

        return redirect()->route('tasks', ['task' => $task])->with('message', 'Task deleted successfully');
    }

    public function search(Request $request)
    {
        $tasks = Task::all();
        if (isset($_GET['query'])) {
            $search = $_GET['query'];
            $tasks = DB::table('tasks')->where('name', 'like', '%' . $search . '%')->paginate(4);
            $tasks->appends($request->all());
            return view('templates.tasks-list', compact('tasks'));
        } else {
            $tasks = Task::paginate(4);
            $tasks->appends($request->all());
            return view('templates.tasks-list', compact('tasks'));
        }
    }
}
