<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class question extends Model
{
    use HasFactory;
    protected $fillable = ['Qst','situation','QstVote','time','type'];
    protected $table="questions";
    public function responses(){
        return $this->hasMany(reponse::class);
    }
}
