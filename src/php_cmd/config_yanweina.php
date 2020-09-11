<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2020/4/3
 * Time: 15:17
 */

/**
`status` tinyint(3) DEFAULT '0' COMMENT '0=无效 1=有效',
`type` tinyint(3) NOT NULL DEFAULT '1' COMMENT '1=专场 2=限时低价',
`logo` varchar(100) NOT NULL DEFAULT '',
`tag` varchar(20) NOT NULL DEFAULT '',
`title` varchar(30) NOT NULL DEFAULT '',
`start_time` int(11) unsigned NOT NULL DEFAULT '0',
`end_time` int(11) unsigned NOT NULL DEFAULT '0',
 */
$config = [
    [
        'name'=>'shangpin',
        'name_chs'=>'商品',
        'sub_modules' => [
            [
                'name' => 'goods',
                'name_chs' => '商品管理',
                'edit' => [
                    'fields' => [
                        'title' => ['key' => 'title', 'type' => 'input', 'value_type'=>'text', 'chs_title'=>'商品标题', 'rule'=>'require'],
                        'desc'=>['key' => 'desc', 'type' => 'textarea', 'chs_title'=>'商品描述'],
                        'price' => ['key' => 'price', 'type' => 'input', 'value_type'=>'number', 'chs_title'=>'售价', 'rule'=>'require'],
                        'origin_price' => ['key' => 'origin_price', 'type' => 'input', 'value_type'=>'number', 'chs_title'=>'原价', 'rule'=>'require'],
                        'pics'=>['key' => 'pics', 'type' => 'multi_image', 'chs_title'=>'商品图片', 'rule'=>'require', 'value_type'=>'array', 'max_num'=>5, 'tips'=>'要求尺寸600*400，第一张为主图', 'max_size'=>2],
                        '_key1' => ['key' => '_key1', 'type' => 'other', 'chs_title'=>'基本信息'],
                        'content' => ['key' => 'content', 'type' => 'ue', 'chs_title'=>'商品详情', 'rule'=>'require'],
                        'send_daley_hours' => ['key' => 'send_daley_hours', 'type' => 'input', 'value_type'=>'number', 'chs_title'=>'多少小时内发货', 'default_value'=>'24','rule'=>'require'],
                        'weight' => ['key' => 'weight', 'type' => 'input', 'value_type'=>'number', 'chs_title'=>'重量（kg）', 'default_value'=>'0.5', 'rule'=>'require'],
                        'category'=>['key' => 'category', 'type' => 'select', 'chs_title'=>'所属分类',   'rule'=>'require','options'=>[
                            'items' => 'category/all_list',//[] or 'http://'数组或者url
                            'items_vue_ins' => 'categories',//当items为url时,vue中异步请求获得的items变量名
                            'key' => 'id',
                            'value' => 'id',// or 空
                            'label' => 'name',
                        ]],
                        'is_shipping_fee_free' => ['key' => 'is_shipping_fee_free', 'type' => 'switch', 'chs_title'=>'是否包邮', 'default_value'=>'0'],
                        '_key2' => ['key' => '_key2', 'type' => 'other', 'chs_title'=>'运费模板'],
                        'status' => ['key' => 'status', 'type' => 'switch', 'chs_title'=>'启用/禁用', 'default_value'=>'1'],
                    ]
                ],
                'list' => [
                    'search' => [
                        'id' => ['key' => 'id', 'type' => 'input', 'chs_title'=>'商品ID', 'rule'=>'require'],
                        'title' => ['key' => 'title', 'type' => 'input', 'chs_title'=>'商品标题', 'rule'=>'require'],
                        'category'=>['key' => 'category', 'type' => 'select', 'chs_title'=>'所属分类', 'rule'=>'require','options'=>[
                            'items' => 'category/all_list',//[] or 'http://'数组或者url
                            'items_vue_ins' => 'categories',//当items为url时,vue中异步请求获得的items变量名
                            'key' => 'id',
                            'value' => 'id',// or 空
                            'label' => 'name',
                        ]],
                        'status'=>['key' => 'status', 'type' => 'select', 'chs_title'=>'状态', 'rule'=>'require','options'=>[
                            'items' => [['id'=>'1','name'=>'启用'], ['id'=>'0','name'=>'禁用']],//[] or 'http://'数组或者url
                            'key' => 'id',
                            'value' => 'id',// or 空
                            'label' => 'name',
                        ]],
                    ],
                    'add_link_name' => '新增商品',
                    'fields' => ['商品ID' => 'id', 'img'=>'pic','商品标题' => 'title','售价' => 'price','重量(kg)' => 'weight','商品分类' => 'cate_name','多少小时内发货' => 'send_daley_hours','是否包邮' => 'is_shipping_fee_free_chs','启用/禁用' => 'status_chs' ],
                    'actions' =>['verify','edit','del']
                ]
            ],
            [
                'name' => 'category',
                'name_chs' => '分类管理',
                'edit' => [
                    'fields' => [
                        'name' => ['key' => 'name', 'type' => 'input', 'value_type'=>'text', 'chs_title'=>'分类名称', 'rule'=>'require'],
                        'pid'=>['key' => 'pid', 'type' => 'select', 'chs_title'=>'父级分类',   'rule'=>'require','options'=>[
                            'items' => 'category/all_list',//[] or 'http://'数组或者url
                            'items_vue_ins' => 'categories',//当items为url时,vue中异步请求获得的items变量名
                            'key' => 'id',
                            'value' => 'id',// or 空
                            'label' => 'name',
                        ]],
                        'sort' => ['key' => 'sort', 'type' => 'input', 'value_type'=>'number', 'chs_title'=>'排序值（越大越靠前）', 'default_value'=>'1', 'rule'=>'require']
                    ]
                ],
                'list' => [
                    'search' => [

                    ],
                    'add_link_name' => '新增分类',
                    'fields' => [],
                    'actions' => []
                ]
            ]

        ]
    ],
    [
        'name'=>'yingxiaohuodong',
        'name_chs'=>'营销',
        'sub_modules' => [
            [
                'name' => 'activityzc',
                'name_chs' => '商品专场',
                'edit' => [
                    'fields' => [
                        'title' => ['key' => 'title', 'type' => 'input', 'value_type'=>'text', 'chs_title'=>'专场标题', 'rule'=>'require'],
                        'logo'=>['key' => 'logo', 'type' => 'single_image', 'chs_title'=>'专场首页logo', 'tips'=>'要求尺寸200*200', 'max_size'=>2],
                        '_key1' => ['key' => '_key1', 'type' => 'other', 'chs_title'=>'选取商品'],
                        'start_end' => ['key' => 'start_end', 'type' => 'datepicker', 'date_type'=>'datetimerange', 'value_format'=>'yyyy-MM-dd HH:mm:ss', 'chs_title'=>'专场时间', 'rule'=>'require'],
                        'index_show' => ['key' => 'index_show', 'type' => 'switch', 'chs_title'=>'是否首页展示', 'default_value'=>'0'],
                        'sort' => ['key' => 'sort', 'type' => 'input', 'value_type'=>'number', 'chs_title'=>'排序值（越大越靠前）', 'default_value'=>'1', 'rule'=>'require'],
                        'status' => ['key' => 'status', 'type' => 'switch', 'chs_title'=>'启用/禁用', 'default_value'=>'1'],
                    ]
                ],
                'list' => [
                    'search' => [
                        'id' => ['key' => 'id', 'type' => 'input', 'chs_title'=>'专场ID', 'rule'=>'require'],
                        'title' => ['key' => 'title', 'type' => 'input', 'chs_title'=>'专场标题', 'rule'=>'require'],
                        'start_end' => ['key' => 'start_end', 'type' => 'datepicker', 'date_type'=>'datetimerange', 'value_format'=>'yyyy-MM-dd HH:mm:ss', 'chs_title'=>'专场时间', 'rule'=>'require'],
                        'status'=>['key' => 'status', 'type' => 'select', 'chs_title'=>'状态', 'rule'=>'require','options'=>[
                            'items' => [['id'=>'1','name'=>'启用'], ['id'=>'0','name'=>'禁用']],//[] or 'http://'数组或者url
                            'key' => 'id',
                            'value' => 'id',// or 空
                            'label' => 'name',
                        ]],
                        'index_show'=>['key' => 'index_show', 'type' => 'select', 'chs_title'=>'是否首页展示', 'rule'=>'require','options'=>[
                            'items' => [['id'=>'1','name'=>'是'], ['id'=>'0','name'=>'否']],//[] or 'http://'数组或者url
                            'key' => 'id',
                            'value' => 'id',// or 空
                            'label' => 'name',
                        ]],
                    ],
                    'add_link_name' => '新增商品专场',
                    'fields' => ['专场ID' => 'id', 'img'=>'logo', '是否首页展示' => 'index_show_chs', '开始时间' => 'start_time_chs','结束时间' => 'end_time_chs', '启用/禁用' => 'status_chs' ],
                    'actions' => ['sort','verify','edit','del']
                ]
            ],
            [
                'name' => 'activityxsdj',
                'name_chs' => '限时低价',
                'edit' => [
                    'fields' => [
                        'title' => ['key' => 'title', 'type' => 'input', 'value_type'=>'text', 'chs_title'=>'活动标题', 'rule'=>'require'],
                        '_key1' => ['key' => '_key1', 'type' => 'other', 'chs_title'=>'选取商品'],
                        'start_end' => ['key' => 'start_end', 'type' => 'datepicker', 'date_type'=>'datetimerange', 'value_format'=>'yyyy-MM-dd HH:mm:ss', 'chs_title'=>'活动时间', 'rule'=>'require'],
                        'status' => ['key' => 'status', 'type' => 'switch', 'chs_title'=>'启用/禁用', 'default_value'=>'1'],
                    ]
                ],
                'list' => [
                    'search' => [
                        'id' => ['key' => 'id', 'type' => 'input', 'chs_title'=>'活动ID', 'rule'=>'require'],
                        'title' => ['key' => 'title', 'type' => 'input', 'chs_title'=>'活动标题', 'rule'=>'require'],
                        'start_end' => ['key' => 'start_end', 'type' => 'datepicker', 'date_type'=>'datetimerange', 'value_format'=>'yyyy-MM-dd HH:mm:ss', 'chs_title'=>'专场时间', 'rule'=>'require'],
                        'status'=>['key' => 'status', 'type' => 'select', 'chs_title'=>'状态', 'rule'=>'require','options'=>[
                            'items' => [['id'=>'1','name'=>'启用'], ['id'=>'0','name'=>'禁用']],//[] or 'http://'数组或者url
                            'key' => 'id',
                            'value' => 'id',// or 空
                            'label' => 'name',
                        ]],
                    ],
                    'add_link_name' => '新增活动',
                    'fields' => ['活动ID' => 'id', '开始时间' => 'start_time_chs','结束时间' => 'end_time_chs', '启用/禁用' => 'status_chs' ],
                    'actions' => ['verify','edit','del']
                ]
            ]

        ]
    ]
];


