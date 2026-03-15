<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contract extends Model
{
    protected $fillable = ['client_id','draft_pdf_path','signed_pdf_path','signed_at','created_by'];
    protected $casts = ['signed_at' => 'datetime'];

    public function client() { return $this->belongsTo(Client::class); }
    public function creator() { return $this->belongsTo(User::class, 'created_by'); }
}