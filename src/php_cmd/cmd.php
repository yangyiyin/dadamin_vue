<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2020/4/3
 * Time: 15:17
 */

require_once './config_yanweina.php';

//生成form_init_obj，form_rules, fields_obj
foreach ($config as $k1 => $_module) {
    foreach ($_module['sub_modules'] as $k2 => $module) {

        if (!empty($module['edit'])) {
            if (file_exists('../page_biz/'.$module['name'].'_edit.vue')) {
                continue;
            }
            $form_init_obj = [];
            $form_rules = [];
            $fields_obj = [];
            $module['edit']['use_ue'] = false;
            $config[$k1]['sub_modules'][$k2]['edit']['use_ue'] = false;
            foreach ($module['edit']['fields'] as $k3 => $_field) {
                if (!empty($_field['value_type']) && $_field['value_type'] == 'array') {
                    $form_init_obj[$_field['key']] = [];
                } else {
                    if (isset($_field['default_value'])) {
                        $form_init_obj[$_field['key']] = $_field['default_value'];
                    } else {
                        $form_init_obj[$_field['key']] = '';
                    }
                }
                if (!empty($_field['rule']) && $_field['rule'] == 'require') {
                    if ($_field['value_type'] == 'array') {
                        $form_rules[$_field['key']]=[['required' => true, 'type'=>'array','message'=>$_field['chs_title'].'不能为空', 'trigger'=> 'blur' ]];
                    } else {
                        $form_rules[$_field['key']]=[['required' => true, 'message'=>$_field['chs_title'].'不能为空', 'trigger'=> 'blur' ]];
                    }
                }
                if ($_field['type'] == 'ue') {
                    $module['edit']['use_ue'] = true;
                    $config[$k1]['sub_modules'][$k2]['edit']['use_ue'] = true;
                }
                $fields_obj[$k3] = $_field;
            }
            $config[$k1]['sub_modules'][$k2]['edit']['form_init_obj'] = $module['edit']['form_init_obj'] = json_encode($form_init_obj, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
            $config[$k1]['sub_modules'][$k2]['edit']['form_rules'] = $module['edit']['form_rules'] = json_encode($form_rules,JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
            $config[$k1]['sub_modules'][$k2]['edit']['fields_obj'] = $module['edit']['fields_obj'] = json_encode($fields_obj,JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);

            //生成edit
            ob_start();
            include './tmp/edit.php';
            $edit_content = ob_get_contents();
            file_put_contents('../page_biz/'.$module['name'].'_edit.vue', $edit_content);
            ob_end_clean();
            //编辑router
            $router_content = file_get_contents('../router/index.js');
            $name = $module['name'].'_edit';
//            var_dump(strstr($router_content, $name));die();
            if (strstr($router_content, $name) === false) {
                $router_content = str_replace('//{#replace1#}', ' const '.$name.' = r => require.ensure([], () => r(require(\'@/page_biz/'.$name.'\')), \''.$name.'\');'."\n//{#replace1#}", $router_content);
                $router_content = str_replace('//{#replace2#}', ' { path: \'/'.$name.'\',component: '.$name.',meta: {path:[\''.$module['name_chs'].'\', \'编辑\'],_menu:\''.$name.'\'},},'."\n//{#replace2#}", $router_content);
                file_put_contents('../router/index.js', $router_content);
            }


        }

        if (!empty($module['list'])) {
            if (file_exists('../page_biz/'.$module['name'].'_list.vue')) {
                continue;
            }
            //生成list
            ob_start();
            include './tmp/list.php';
            $edit_content = ob_get_contents();
            file_put_contents('../page_biz/' . $module['name'] . '_list.vue', $edit_content);
            ob_end_clean();

            //编辑router
            $router_content = file_get_contents('../router/index.js');
            $name = $module['name'] . '_list';
            if (strstr($router_content, $name) === false) {
                $router_content = str_replace('//{#replace1#}', ' const ' . $name . ' = r => require.ensure([], () => r(require(\'@/page_biz/' . $name . '\')), \'' . $name . '\');' . "\n//{#replace1#}", $router_content);
                $router_content = str_replace('//{#replace2#}', ' { path: \'/' . $name . '\',component: ' . $name . ',meta: {path:[\'' . $module['name_chs'] . '\', \'列表\'],_menu:\'' . $name . '\'},},' . "\n//{#replace2#}", $router_content);
                file_put_contents('../router/index.js', $router_content);
            }
        }
    }
}



