<?php
/**
 * @author SJ
 * @date   2019.12.23
 */

return [
    /**
     * Database table name where your manager data will be stored
     */
    'database' => [
        'groups_table' => 'content_groups',
        'categories_table' => 'content_categories',
        'items_table' => 'content_items',
    ],

    /**
     * Route from which your Dashboard will be available
     */
    'route' => 'content-manager',

    /**
     * Middleware array for dashboard
     * to prevent unauthorized users visit the manager
     */
    'middleware' => [
        //
    ],

    /**
     * FileUpload Disk
     */
    'filesystems' => [
        'disk' => 's3',
        'path' => '',
    ],
];
