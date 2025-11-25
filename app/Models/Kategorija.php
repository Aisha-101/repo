<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Kategorija extends Model
{
     use HasFactory;
    protected $table = 'kategorijas';
    protected $fillable = ['pavadinimas'];

    public function knygos()
    {
        return $this->hasMany(Knyga::class);
    }
}
