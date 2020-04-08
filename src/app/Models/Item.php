<?php
/**
 * @author SJ
 * @date   2019.12.24
 */

namespace Restart\ContentManager\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Jenssegers\Agent\Agent;

class Item extends Model
{
    protected $table;

    protected $fillable = [
        'category_id',
        'name',
        'description',
        'start_date',
        'start_time',
        'end_date',
        'end_time',
        'images',
        'links',
        'ord',
        'enable',
    ];

    protected $casts = [
        'images' => 'array',
        'links' => 'array',
    ];

    /**
     * Append attribute
     */
    protected $appends = [
        'link',
    ];

    /**
     * Category constructor.
     */
    public function __construct(array $attributes = [])
    {
        $this->table = config('content-manager.database.items_table');

        parent::__construct($attributes);
    }

    /**
     * Get the category that owns the item.
     */
    public function category()
    {
        return $this->belongsTo(Category::class, 'content_category_id', 'id');
    }

    /**
     * Get content link attribute.
     * 
     */
    public function getLinkAttribute()
    {
        $link = [
            'url' => null,
            'target' => null,
        ];

        if ($this->links) {
            $agent = new Agent();
            $links = collect($this->links)->keyBy('device');

            if ($agent->isMobile() && isset($links['mobile'])) {
                return array_merge($link, $links['mobile']);
            } else if ($agent->isTablet() && isset($links['tablet'])) {
                return array_merge($link, $links['tablet']);
            } else if ($agent->isDesktop() && isset($links['desktop'])) {
                return array_merge($link, $links['desktop']);
            } else if (isset($links[''])) {
                return array_merge($link, $links['']);
            }
        }

        return $link;
    }

    /**
     * Verify that the date is valid.
     * 
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  \Illuminate\Database\Eloquent\Builder
     */
    public function scopeDate($query)
    {
        // Start date
        $query->whereRaw('(start_date IS NULL OR start_date<=curdate())');

        // End date
        $query->whereRaw('(end_date IS NULL OR end_date>=curdate())');

        return $query;
    }

    /**
     * Verify that the time is valid.
     * 
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  \Illuminate\Database\Eloquent\Builder
     */
    public function scopeTime($query)
    {
        // Start time
        $query->where(function($query) {
            $query->orWhereNull('start_time')
                  ->orWhere('start_date', '<', DB::raw('curdate()'))
                  ->orWhereRaw('(start_date=curdate() AND start_time<=curtime())');
        });

        // End time
        $query->where(function($query) {
            $query->orWhereNull('end_time')
                  ->orWhere('end_date', '>', DB::raw('curdate()'))
                  ->orWhereRaw('(end_date=curdate() AND end_time>=curtime())');
        });

        return $query;
    }
}
