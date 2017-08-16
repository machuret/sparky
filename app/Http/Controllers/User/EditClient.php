<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller as BaseController;
use App\Client;
use App\Project;

class EditClient extends BaseController
{
    protected $request;
    
    protected $message = 'Failed to Update Client';

    protected $code = '200';
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->middleware('auth:web');
        $this->request = $request;
    }

    /**
     * Receive Project Id
     *
     * @return \Illuminate\Http\Response
     */
    public function __invoke($client)
    {
        $validator = $this->sanitize($client);
        if($validator->fails()){
            $this->code = 422;
            return response()->json(['message' => $this->message, 'errors' => $validator->errors()], $this->code);
        }
        if($this->getAuth()->id === $this->getTenant()->id){
            // update name , email , website
            $this->updateClient($client);
            // update password if updated by user
            $this->updatePasswordIfPresent($client);
            // assign projects and remove projects unselected by the user.
            $this->syncProjectsIfAny($client);
            $this->createNewProjectIfAny($client);
            $this->message = 'Client Updated!';
            // append projects to client oject as assignedProjects
            $client->fresh();
            $client->projects;
            // return updated project list without any project assignement to clients
            $projectlist = Project::where('tenant_id',$this->getTenant()->id)->where('client_id', null)->get();
            return response()->json(['message' => $this->message, 'client' => $client, 'projectlist' => $projectlist], $this->code);
        }
        $this->code = 401;
        $this->message = 'UnAuthorized Action!';
        return response()->json(['message' => $this->message], $this->code);
    }

    private function sanitize($client)
    {
       return $validator = \Validator::make($this->request->all(), $this->rules($client), $this->messages());
    }

    private function rules($client)
    {
        return 
        [
        'name' => 'required|max:30',
        'email' => [
            'required',
            'email',
             Rule::unique('clients')->ignore($client->id),
        ],
        'password' => 'sometimes|required|min:6|max:60|confirmed',
        'new_project' => 'boolean',
        'projects.*.name' => 'sometimes|required|max:60',
        'website' => 'regex:/^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/',
        ];
    }

    private function messages()
    {
        return [
            'name.required' => 'Name Field is Required',
            'name.max' => 'Name is Too Long (60) Max',
            'email.required' => 'Email is Required',
            'email.email' => 'Email Format Is Invalid',
            'email.unique' => 'Email is Already Taken',
            'password.min' => 'Password Needs to Be At Least (6) Characters',
            'password.max' => 'Password Exceeds 60 Characters',
            'password.required' => 'Password is Required',
            'password.confirmed' => 'Password Confirmation Does Not Match',
            'new_project.boolean' => 'Create Project Must Be Boolean',
            'website.regex' => 'Enter Valid Url',
        ];
    }

    private function updateClient($client)
    {
        $client->forceFill([
            'name' => $this->request->name,
            'email' => $this->request->email,
            'website' => $this->request->website,
        ])->save();
    }

    private function updatePasswordIfPresent($client)
    {
        if($this->request->has('password')){
            $client->forceFill([
                'password' => $this->request->password
            ])->save();
        }
    }

    private function createNewProjectIfAny($client){
        if($this->request->new_project){
        // get Projects Input
        $projects_input = $this->request->projects;
        // we unset unfilled input  
        for ($i=0; $i < count($projects_input); $i++) { 
            if(!$projects_input[$i]['name']){
                unset($projects_input[$i]);
            }
        }
        // Get Allowed Project Counts
        $count = $this->limitProjectCount();
        // limit project creation
        if($count > 0){
            for ($i=0; $i < $count; $i++) { 
                $project = Project::create($projects_input[$i]);
                // attach project to client
                $client->projects()->save($project);
                // morph projectable
                $this->getAuth()->projects()->save($project);
                // manageProjects ByTenant
                $this->getTenant()->manageProjects()->save($project);
            }
        }
            
        }
    }

    private function limitProjectCount(){
        // get current project count
        $current_count = $this->getAuth()->projects()->count();
        // get the plan
        $plan = $this->getAuth()->sparkPlan();
        // if plan is free
        if($plan->name === 'Free'){
            // return the remaining count
            $max = 3;
            $remaining = (int)($max - $current_count);
            if($remaining < 0) {
                return 0;
            }
            return $remaining;
        }
        // No Limit For VIP
        return count($this->request->projects);
        
    }
    
    private function hasAssignedProjects(){
        $projects = $this->request->assignedProjects;
        $projects_ids = array();
        $selected = array();
        if($projects){
            for ($i=0; $i < count($projects); $i++) { 
                array_push($projects_ids,$projects[$i]['id']);
            }
            $project_list = $this->getTenant()->projects->pluck('id')->toArray();
            $selected = array_intersect($project_list,$projects_ids);
        }
        return $selected;
    }

    private function syncProjectsIfAny($client){
        $selected = $this->hasAssignedProjects();
        if(count($selected))
        {   
            foreach($selected as $id){
            $project = Project::find($id);
            // attach the projects
            $client->projects()->save($project);
            }
        }
        // detach projects that are not selected
        $old_list = Project::where('client_id', $client->id)->where('tenant_id',$this->getTenant()->id)->pluck('id')->toArray();
        $unselected = array_diff($old_list,$selected);
        if(count($unselected))
        {
            foreach($unselected as $id){
                $project = Project::find($id);
                // detach client id
                $project->byClient()->dissociate();
                $project->save();
                }
        }

    }
}