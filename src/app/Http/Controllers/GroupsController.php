<?php
/**
 * @author SJ
 * @date   2019.12.23
 */

namespace Restart\ContentManager\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Restart\ContentManager\App\Models\Group;
use Restart\ContentManager\App\Models\Category;
use Restart\ContentManager\App\Http\Resources\Group as GroupResource;
use Exception;

class GroupsController extends Controller
{
    /**
     * 그룹을 생성합니다.
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        
        $data = $request->input('group');

        try {

            return DB::transaction(function() use ($data) {

                $group = Group::create([
                    'code' => strtoupper($data['code']),
                    'name' => $data['name'],
                ]);

                $data['id'] = $group->id;
                $this->updateCategoriesOfGroup($data);

                $group = (new GroupResource($group))->toArray(new Request);;

                return response()->json(['group' => $group]);
            });

        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * 그룹을 업데이트합니다.
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request)
    {
        $data = $this->setCodeToUpperCase($request->input('groups'));

        try {

            DB::transaction(function() use ($data) {

                // 그룹 업데이트
                Group::rebuildTree($data, true);
                
                // 카테고리 업데이트
                foreach ($data as $group) {
                    $this->updateCategoriesOfGroup($group);
                }
            });
            
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ], 400);
        }

        return response()->json([]);
    }

    /**
     * 그룹의 코드를 대문자로 치환합니다.
     * 
     * @param  array  $groups
     * @return array
     */
    private function setCodeToUpperCase($groups)
    {
        foreach ($groups as $key => $group) {
            $groups[$key]['code'] = strtoupper($group['code']);
            if ($groups[$key]['children']) {
                $groups[$key]['children'] = $this->setCodeToUpperCase($group['children']);
            }
        }

        return $groups;
    }

    /**
     * 그룹의 카테고리를 업데이트합니다.
     * 
     * @param  \Illuminate\Database\Eloquent\Model  $group
     * @return void
     */
    private function updateCategoriesOfGroup($group)
    {
        $group_id = $group['id'];

        if (isset($group['categories']) && is_array($group['categories'])) {

            // Delete categories
            $category_ids = array_column($group['categories'], 'id');
            Category::where('group_id', $group_id)
                    ->whereNotIn('id', $category_ids)
                    ->delete();

            // Insert & Update
            $index = 1;
            foreach ($group['categories'] as $category) {
                if (!isset($category['code']) || $category['code'] === '' || $category['code'] === null) {
                    continue;
                }

                $data = [
                    'group_id' => $group_id,
                    'code' => strtoupper($category['code']),
                    'name' => isset($category['name']) ? $category['name'] : '',
                    'ord' => $index++,
                ];

                if (isset($category['id']) && $category['id']) {
                    Category::where('id', $category['id'])->update($data);
                } else {
                    Category::create($data);
                }
            }

            if (isset($group['children']) && is_array($group['children'])) {
                foreach ($group['children'] as $child) {
                    $this->updateCategoriesOfGroup($child);
                }
            }
        }
    }
}
