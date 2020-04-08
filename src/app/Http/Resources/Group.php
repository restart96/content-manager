<?php
/**
 * @author SJ
 * @date   2019.12.24
 */

namespace Restart\ContentManager\App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;
use Restart\ContentManager\App\Http\Resources\Category as CategoryResource;

class Group extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $resource = [
            'id' => $this->id,
            'code' => $this->code,
            'active' => $this->code == strtoupper($request->input('group_code')),
            'name' => $this->name,
            'categories' => CategoryResource::collection($this->categories)
                                            ->toArray($request),
        ];

        if ($this->children->isNotEmpty()) {
            $resource['children'] = static::collection($this->children)
                                          ->toArray($request);
        } else {
            $resource['children'] = [];
        }

        return $resource;
    }
}
