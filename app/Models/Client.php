<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $fillable = ['name','email','phone','company'];

    public function contracts() { return $this->hasMany(Contract::class); }
    public function projects() { return $this->hasMany(Project::class); }
}