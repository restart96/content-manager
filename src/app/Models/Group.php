<?php
/**
 * @author SJ
 * @date   2019.12.24
 */

namespace Restart\ContentManager\App\Models;

use Illuminate\Database\Eloquent\Model;
use Kalnoy\Nestedset\NodeTrait;

class Group extends Model
{
    use NodeTrait;

    protected $table;

    /**
     * 대량 할당할 수 있는 속성들을 정의합니다.
     *
     * @var array
     */
    protected $fillable = [
        'parent_id',
        'left_id',
        'right_id',
        'code',
        'name',
        'url',
    ];

    /**
     * Category constructor.
     * 
     * @param  array  $attributes
     */
    public function __construct($attributes = [])
    {
        $this->table = config('content-manager.database.groups_table');

        parent::__construct($attributes);
    }

    /**
     * Nested set model left column name
     * 
     * @return string
     */
    public function getLftName()
    {
        return 'left_id';
    }

    /**
     * Nested set model right column name
     * 
     * @return string
     */
    public function getRgtName()
    {
        return 'right_id';
    }

    /**
     * Nested set model parent column name
     * 
     * @return string
     */
    public function getParentIdName()
    {
        return 'parent_id';
    }

    /**
     * Get the categories for the group.
     */
    public function categories()
    {
        return $this->hasMany(Category::class, 'group_id', 'id');
    }
}
