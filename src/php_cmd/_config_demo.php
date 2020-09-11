<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2020/4/3
 * Time: 15:17
 */

/**
 * filed_type => input,select,textarea,single_image,multi_image
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
                        'title' => ['key' => 'title', 'type' => 'input', 'value_type'=>'text', 'chs_title'=>'标题', 'rule'=>'require'],
                        'title2' => ['key' => 'title2', 'type' => 'input', 'value_type'=>'number', 'chs_title'=>'标题2', 'rule'=>'require'],
                        'other1' => ['key' => 'other1', 'type' => 'other', 'chs_title'=>'额外'],
                        'ue1' => ['key' => 'ue1', 'type' => 'ue', 'chs_title'=>'富文本', 'rule'=>'require'],
                        'switch1' => ['key' => 'switch1', 'type' => 'switch', 'chs_title'=>'开关', 'default_value'=>'1', 'rule'=>'require'],
                        'date1' => ['key' => 'date1', 'type' => 'datepicker', 'date_type'=>'datetime', 'value_format'=>'yyyy-MM-dd HH:mm:ss', 'chs_title'=>'日期', 'rule'=>'require'],
                        'desc'=>['key' => 'desc', 'type' => 'textarea', 'chs_title'=>'内容','rule'=>'require'],
                        'category'=>['key' => 'category', 'type' => 'select', 'chs_title'=>'分类',   'rule'=>'require','options'=>[
                            'items' => 'category/all_list',//[] or 'http://'数组或者url
                            'items_vue_ins' => 'categories',//当items为url时,vue中异步请求获得的items变量名
                            'key' => 'id',
                            'value' => 'id',// or 空
                            'label' => 'name',
                        ]],
                        'pic'=>['key' => 'pic', 'type' => 'single_image', 'chs_title'=>'单图','tips'=>'建议尺寸600*400', 'max_size'=>2],
                        'pics'=>['key' => 'pics', 'type' => 'multi_image', 'chs_title'=>'多图', 'rule'=>'require', 'value_type'=>'array', 'max_num'=>5, 'tips'=>'建议尺寸600*400', 'max_size'=>2],
                    ]
                ],
                'list' => [
                    'search' => [
                        'id' => ['key' => 'id', 'type' => 'input', 'chs_title'=>'ID', 'rule'=>'require'],
                        'date1' => ['key' => 'date1', 'type' => 'datepicker', 'date_type'=>'datetime', 'value_format'=>'yyyy-MM-dd HH:mm:ss', 'chs_title'=>'日期', 'rule'=>'require'],
                        'title' => ['key' => 'title', 'type' => 'input', 'chs_title'=>'标题', 'rule'=>'require'],
                        'category'=>['key' => 'category', 'type' => 'select', 'chs_title'=>'分类', 'rule'=>'require','options'=>[
                            'items' => 'category/all_list',//[] or 'http://'数组或者url
                            'items_vue_ins' => 'categories',//当items为url时,vue中异步请求获得的items变量名
                            'key' => 'id',
                            'value' => 'id',// or 空
                            'label' => 'name',
                        ]],
                    ],
                    'add_link_name' => '新增商品',
                    'fields' => ['ID' => 'id','标题' => 'title', '内容' => 'desc', 'img'=>'pic'],
                    'actions' => ['del', 'edit', 'sort', 'verify']
                ]
            ]
        ]
    ]
];


