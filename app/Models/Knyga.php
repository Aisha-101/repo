<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Knyga extends Model
{
    use HasFactory;

    protected $table = 'knygos';
    protected $fillable = ['pavadinimas', 'autorius', 'aprasymas', 'isbn', 'kategorija_id',
    'user_id'];

    public function kategorija()
    {
        return $this->belongsTo(Kategorija::class);
    }

    public function rekomendacijos()
    {
        return $this->hasMany(Rekomendacija::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
