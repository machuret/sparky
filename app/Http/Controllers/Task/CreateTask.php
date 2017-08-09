<?php

namespace App\Http\Controllers\Task;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller as BaseController;
use App\Task;
use App\Campaign;

class CreateTask extends BaseController
{
    protected $task;

    protected $request;

    protected $message = 'Task Created!';

    protected $code = '200';

    public function __construct(Task $task, Request $request)
    {
        $this->middleware('auth');
        $this->task = $task;
        $this->request = $request;
    }

    /**
     * Receive Project Id
     *
     * @return \Illuminate\Http\Response
     */
    public function __invoke($campaign)
    {
        $validator = $this->sanitize();
        if($validator->fails()){
        $this->message = 'Failed To Create Task';
        $this->code = 400;
        return response()->json(['message' => $this->message, 'errors' => $validator->errors()], $this->code);
        }
        $this->createTask();
        $this->save($campaign);
        $task = $this->task->find($this->task->id);
        return response()->json(['message' => $this->message, 'task' => $task], $this->code);
    }
    private function sanitize()
    {
       return $validator = \Validator::make($this->request->all(), $this->rules(), $this->messages());
    }
    private function rules(){
        return 
        [
        'task_name' => 'required|max:30',
        'task_link' => 'regex:/^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/',
        'task_description' => 'max:200',
        'task_recurring' => 'boolean',
        'task_interval' => 'integer|min:0|max:999'
        ];
    }

    private function messages(){
        return [
            'task_name.required' => 'Name Your Task',
            'task_name.max' => 'Task Name Too Long',
            'task_description.max' => 'Description Too Long',
            'task_link.regex' => 'Enter Valid Url',
            'task_recurring.boolean' => 'Recurring Value Must Be Either True or False',
            'task_interval.integer' => 'Task Interval Provided is Not Integer',
            'task_interval.min' => 'Task Interval Lowest Value: 0',
            'task_interval.max' => 'Task Interval Highest Value: 999'
        ];
    }

    private function createTask()
    {
        $this->addName();
        $this->addLink();
        $this->addRecurring();
        $this->addInterval();
        $this->addDescription();
    }

    private function addName()
    {
        $this->task->name = $this->request->task_name;
    }

    private function addLink()
    {
        if(isset($this->request->task_link)){
        $this->task->link = $this->request->task_link;
        }
    }

    private function addRecurring(){
        if(isset($this->request->task_recurring)){
            $this->task->recurring = $this->request->task_recurring;
            }
    }

    private function addInterval(){
        if(isset($this->request->task_interval)){
            $this->task->interval = $this->request->task_interval;
            }
    }

    private function addDescription()
    {
        if(isset($this->request->task_description)){
        $this->task->description = $this->request->task_description;
        }
    }

    private function save($campaign)
    {
        $save = $campaign->tasks()->save($this->task);
        if(!$save){
        $this->message = 'Task Creation Failed!';
        $this->code = 404;
        }
    }
}