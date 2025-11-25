<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Rekomendacija extends Model
{
    use HasFactory;

    protected $table = 'rekomendacijos';
    protected $fillable = ['knyga_id', 'naudotojas', 'komentaras', 'ivertinimas'];

    public function knyga()
    {
        return $this->belongsTo(Knyga::class);
    }
}
