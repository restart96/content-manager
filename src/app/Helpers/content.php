<?php
/**
 * @author SJ
 * @date   2019.12.24
 */

use Restart\ContentManager\App\Models\Group;
use Restart\ContentManager\App\Models\Category;
use Restart\ContentManager\App\Models\Item;

if (!function_exists('contentItems')) {
    /**
     * Get items from group & category code
     * 
     * @param  string  $group
     * @param  string  $category
     * @param  string|null  $enable
     */
    function contentItems($group, $category, $enable = 'Y')
    {
        $group = Group::whereCode($group)->first();
        if ($group) {
            $category = $group->categories()->whereCode($category)->first();
            if ($category) {
                $items = $category->items()->date()->time();

                if ($enable) {
                    $items = $items->where('enable', $enable);
                }

                return $items->orderBy('enable', 'desc')
                             ->orderBy('ord')
                             ->orderBy('created_at')
                             ->orderBy('id');
            }
        }

        // Return nothing model
        return (new Item)->whereRaw('1 != 1');
    }
}

if (!function_exists('contentItem')) {
    /**
     * Get item from group & category & id
     * 
     * @param  string  $group
     * @param  string  $category
     * @param  int  $id
     * @param  string|null  $enable
     */
    function contentItem($group, $category, $id, $enable = 'Y')
    {
        $group = Group::whereCode($group)->first();
        if ($group) {
            $category = $group->categories()->whereCode($category)->first();
            if ($category) {
                $items = $category->items()->where('id', $id)->date()->time();

                if ($enable) {
                    $items = $items->where('enable', $enable);
                }

                return $items->first();
            }
        }

        return null;
    }
}

if (!function_exists('getContentManagerUrl')) {
    /**
     * Get content-manager URL
     * 
     * @param  string  $group
     * @param  string  $category
     * @param  bool  $absolute
     */
    function getContentManagerUrl($group, $category, $absolute = true)
    {
        return route('content-manager.index', [
            'group' => strtolower($group),
            'category' => strtolower($category),
        ], $absolute);
    }
}
