<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ebook extends Model
{
    use HasFactory;
    public function getCreatedAtAttribute($date)
    {
        return Carbon::parse($date)->format('d-M-y');
    }

    public function getUpdatedAtAttribute($date)
    {
        return Carbon::parse($date)->format('d-M-y');
    }
    protected $fillable = [
        'name',
    ];
    public function chapters()
    {
        return $this->hasMany(Chapter::class);
    }

    public function scopeEbookName($query, $name)
    {
        $query->where('name', $name);
    }
    public function scopeEbookSlug($query, $name)
    {
        $query->where('slug', $name);
    }
}
