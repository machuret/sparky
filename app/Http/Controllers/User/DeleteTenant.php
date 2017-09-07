<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller as BaseController;
use Laravel\Spark\Notification;
use DB;

class DeleteTenant extends BaseController
{
    
    protected $message = 'Tenant Account Deleted';

    protected $code = '200';
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    /**
     * Receive Project Id
     *
     * @return \Illuminate\Http\Response
     */
    public function __invoke($tenant)
    {
        if($tenant->isSuperAdmin()){
            $this->message = 'Opps Cant Delete Super Admin';
            $this->code = 403;
            return response()->json(['message' => $this->message], $this->code);
        }
        if($this->getAuth()->id === $this->getTenant()->id){
            
            $this->deleteProjects($tenant);
            $this->deleteEmployees($tenant);
            $this->deleteClients($tenant);
            $this->deleteNotifications($tenant);
            $this->deleteAnnouncements($tenant);
            $this->deleteFiles($tenant);
            // $this->deleteComments($tenant); // client // employee
            $tenant->delete();
            return response()->json(['message' => $this->message, 'user' => $tenant], $this->code);
        }
        $this->code = 401;
        $this->message = 'UnAuthorized Action';
        return response()->json(['message' => $this->message], $this->code);
    }

    private function deleteProjects($tenant){
        if($tenant->projects){
            $tenant->projects()->each(function ($project) {
                if($project->campaigns){
                    $project->campaigns()->each(function($campaign){
                        if($campaign->tasks){
                            $campaign->tasks()->each(function($task){
                                if($task->subtasks){
                                    $task->subtasks()->each(function($subtask){
                                        $subtask->employees()->sync([]);
                                        $subtask->delete();
                                    });
                                }
                                
                                $task->delete();
                            });
                        }
                        
                        $campaign->delete();
                    });
                }
                
                $project->delete();
             });
        }
    }

    private function deleteEmployees($tenant){
        if($tenant->employees){
            $tenant->employees()->each(function ($employee) {
                $employee->delete();
             });
        }
    }

    private function deleteClients($tenant){
        if($tenant->clients){
            $tenant->clients()->each(function ($client) {
                $client->delete();
             });    
        }
    }

    private function deleteNotifications($tenant){
        DB::table('notifications')->where('user_id',$tenant->id)->delete();
        
    }

    private function deleteAnnouncements($tenant){
        DB::table('announcements')->where('user_id',$tenant->id)->delete();
    }

    private function deleteFiles($user){
        if($user->files){
            $user->files()->each(function ($file) {
                $file->delete();
             });
        }
    }

}