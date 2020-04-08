<?php
/**
 * @author SJ
 * @date   2020.01.31
 */

namespace Restart\ContentManager\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Restart\ContentManager\App\Models\Group;
use Restart\ContentManager\App\Models\Category;
use Restart\ContentManager\App\Models\Item;
use Restart\ContentManager\App\Http\Requests\CreateItemRequest;
use Restart\ContentManager\App\Http\Requests\UpdateItemRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Exception;

class ItemsController extends Controller
{
    /**
     * 아이템 목록을 반환합니다.
     * 
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $group
     * @param  string  $category
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request, $group, $category)
    {
        $category = $this->getCategoryByCode($group, $category);
        $items = $category->items()->orderBy('ord')->get();

        return response()->json(['items' => $items]);
    }

    /**
     * 아이템을 생성합니다.
     * 
     * @param  \Restart\ContentManager\App\Http\Requests\CreateItemRequest  $request
     * @param  string  $group
     * @param  string  $category
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(CreateItemRequest $request, $group, $category)
    {
        $category = $this->getCategoryByCode($group, $category);

        $data = $request->input();
        $images = $request->file('images');
        if ($images) {
            foreach ($images as $idx => $image) {
                if ($image['file']) {
                    $data['images'][$idx]['url'] = $this->uploadFile($category, $image['file']);
                }
                unset($data['images'][$idx]['file']);
            }
        }
        $data['ord'] = 1;

        $category->items()->update([
            'ord' => DB::raw('ord + 1'),
        ]);
        $item = $category->items()->create($data);

        $items = $category->items()->orderBy('ord')->get();

        return response()->json(['items' => $items], 201);
    }

    /**
     * 전체 아이템을 업데이트합니다.
     * 
     * @param  \Restart\ContentManager\App\Http\Requests\CreateItemRequest  $request
     * @param  string  $group
     * @param  string  $category
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateAll(Request $request, $group, $category)
    {
        $items = $request->input('items');
        if ($items) {
            foreach ($items as $idx => $item) {
                Item::find($item['id'])
                    ->update([
                        'ord' => $idx + 1,
                        'enable' => $item['enable'],
                    ]);
            }
        }

        $category = $this->getCategoryByCode($group, $category);
        $items = $category->items()->orderBy('ord')->get();

        return response()->json(['items' => $items]);
    }

    /**
     * 아이템을 업데이트합니다.
     * 
     * @param  \Restart\ContentManager\App\Http\Requests\UpdateItemRequest  $request
     * @param  string  $group
     * @param  string  $category
     * @param  int  $item
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateItemRequest $request, $group, $category, $item)
    {
        $category = $this->getCategoryByCode($group, $category);
        $item = $category->items()->find($item);
        if (!$item) {
            abort(404, '콘텐츠를 찾을 수 없습니다.');
        }

        $data = $request->input();
        $images = $request->file('images');
        if ($images) {
            foreach ($images as $idx => $image) {
                if ($image['file']) {
                    $data['images'][$idx]['url'] = $this->uploadFile($category, $image['file']);
                }
                unset($data['images'][$idx]['file']);
            }
        }
        $item->update($data);

        $items = $category->items()->orderBy('ord')->get();

        return response()->json(['items' => $items]);
    }

    /**
     * 아이템을 삭제합니다.
     * 
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $group
     * @param  string  $category
     * @param  int  $item
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request, $group, $category, $item)
    {
        $category = $this->getCategoryByCode($group, $category);
        $item = $category->items()->find($item);
        if (!$item) {
            abort(404, '콘텐츠를 찾을 수 없습니다.');
        }

        $item->delete();

        $items = $category->items()->orderBy('ord')->get();
        foreach ($items as $idx => $item) {
            $item->update([
                'ord' => $idx + 1,
            ]);
        }

        return response()->json(['items' => $items]);
    }

    /**
     * 코드로 카테고리 리소스를 반환합니다.
     * 
     * @param  string  $group
     * @param  string  $category
     * @return \Restart\ContentManager\App\Models\Category
     */
    private function getCategoryByCode($group, $category)
    {
        try {

            $group = Group::whereCode($group)->first();
            if (!$group) {
                throw new Exception('메뉴를 찾을 수 없습니다.');
            }

            $category = $group->categories()->whereCode($category)->first();
            if (!$category) {
                throw new Exception('카테고리를 찾을 수 없습니다.');
            }

        } catch (Exception $e) {
            abort(404, $e->getMessage());
        }

        return $category;
    }

    /**
     * 파일을 스토리지에 업로드합니다.
     * 
     * @param  \Restart\ContentManager\App\Models\Category  $category
     * @return string
     */
    private function uploadFile(Category $category, $file)
    {
        if ($file) {
            $storage = Storage::disk(config('content-manager.filesystems.disk'));
            $ext = $file->getClientOriginalExtension();

            $path = sprintf('%s/groups/%d/categories/%d',
                        config('content-manager.filesystems.path'),
                        $category->group_id,
                        $category->id);
            $hash = sprintf('%s_%s.%s', date('YmdHi'), md5(time().$file->getClientOriginalName()), $ext);

            $path = $storage->putFileAs($path, $file, $hash);
            return $storage->url($path);
        }

        return null;
    }
}
