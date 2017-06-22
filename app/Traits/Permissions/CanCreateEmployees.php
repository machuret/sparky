<?php 

namespace App\Traits\Permissions;

trait CanCreateEmployees
{
    // This Trait Needs to Be Added to User and Employee
    // Since a User as Tenant Can Create a Employees
    // and Employee Can Also Create Other Employees
    public function employees()
    {
        return $this->morphMany('App\Employee', 'employable');
    }
}