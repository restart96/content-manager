<?php
/**
 * @author SJ
 * @date   2019.12.24
 */

namespace Restart\ContentManager\App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table;

    /**
     * 대량 할당할 수 있는 속성들을 정의합니다.
     *
     * @var array
     */
    protected $fillable = [
        'group_id',
        'code',
        'name',
        'ord',
    ];

    /**
     * Category constructor.
     * 
     * @param  array  $attributes
     */
    public function __construct($attributes = [])
    {
        $this->table = config('content-manager.database.categories_table');

        parent::__construct($attributes);
    }

    /**
     * Get the items for the category.
     */
    public function items()
    {
        return $this->hasMany(Item::class, 'category_id', 'id');
    }

    /**
     * Get the group that owns the category.
     */
    public function group()
    {
        return $this->belongsTo(Group::class, 'group_id', 'id');
    }
}
