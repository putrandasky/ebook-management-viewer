<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chapter extends Model
{
    public $timestamps = false;
    use HasFactory;
    /**
     * Get all of the pages for the Chapter
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function pages()
    {
        return $this->hasMany(Page::class);
    }
    /**
     * Get the ebook that owns the Chapter
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function ebook()
    {
        return $this->belongsTo(Ebook::class);
    }

    public function scopeReOrder($query)
    {
        return $query->orderBy('order');
    }
    public function scopeEbookId($query, $ebook_id)
    {
        return $query->where('ebook_id', $ebook_id);
    }

    public function scopeWithMarkedPages($query)
    {
        return $query->whereHas('pages', function ($query) {
            $query->where('is_marked', true);
        })->with([
            'pages' => function ($query) {
                $query->where('is_marked', true);
                $query->orderBy('order');
            },
        ]);
    }
    public function scopeWithPages($query)
    {
        return $query->with([
            'pages' => function ($query) {
                $query->orderBy('order');
            },
            'pages.chapter',
            'pages.hotspotlinks',
        ]);
    }

    protected $columns = ['id', 'pseudo', 'email'];
    public function scopeExclude($query, $value = [])
    {
        return $query->select(array_diff($this->columns, (array) $value));
    }
}
