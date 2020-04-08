<?php
/**
 * @author SJ
 * @date   2019.12.23
 */

namespace Restart\ContentManager\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Restart\ContentManager\App\Models\Group;
use Restart\ContentManager\App\Models\Category;
use Restart\ContentManager\App\Http\Resources\Group as GroupResource;

class ContentManagerController extends Controller
{
    /**
     * 콘텐츠 목록 페이지입니다.
     * 
     * @param  string|null  $group
     * @param  string|null  $category
     * @return \Illuminate\View\View
     */
    public function index($group = null, $category = null)
    {
        // Set request
        $request = new Request([
            'group_code' => $group,
            'category_code' => $category,
        ]);

        // Get groups
        $groups = Group::with(['categories' => function($query) {
                      $query->orderBy('ord');
                  }])
                  ->orderBy('left_id')
                  ->get()
                  ->toTree();

        $groups = GroupResource::collection($groups)->toArray($request);

        return view('content-manager::index', compact('groups'));
    }
}
