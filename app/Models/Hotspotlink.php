<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hotspotlink extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'address',
        'area_height',
        'area_left',
        'area_top',
        'area_width',
        'parent_height',
        'parent_width',
        'page_id',
    ];

    use HasFactory;
    /**
     * Get the page that owns the Hotspotlink
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function page()
    {
        return $this->belongsTo(Page::class);
    }

}
