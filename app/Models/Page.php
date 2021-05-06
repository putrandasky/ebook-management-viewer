<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    public $timestamps = false;
    use HasFactory;
    protected $fillable = ['name', 'order', 'original_name', 'is_marked', 'chapter_id'];

    /**
     * Get all of the hotspotlinks for the Page
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function hotspotlinks()
    {
        return $this->hasMany(Hotspotlink::class);
    }
    /**
     * Get the chapter that owns the Page
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function chapter()
    {
        return $this->belongsTo(Chapter::class);
    }
    public function scopeChapterId($query, $chapter_id)
    {
        return $query->where('chapter_id', $chapter_id);
    }
}
