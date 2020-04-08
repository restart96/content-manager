<?php
/**
 * @author SJ
 * @date   2019.12.24
 */

namespace Restart\ContentManager\App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class Category extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'code' => $this->code,
            'active' => $this->code == strtoupper($request->input('category_code')),
            'name' => $this->name,
        ];
    }
}
