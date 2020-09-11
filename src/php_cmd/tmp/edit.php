<template>
    <div class="fillcontain">

        <div class="table_container" style="padding:20px">
            <el-card  v-loading="$store.state.loading.<?php echo $module['name'];?>info">
            <el-form label-suffix=":" ref="form" :rules="rules" label-position="left" :model="form" label-width="120px">
                <?php
                foreach($module['edit']['fields'] as $_key => $field){
                    if ($field['type'] == 'input') {?>
                        <el-form-item label="<?php echo $field['chs_title'];?>"  prop="<?php echo $field['key'];?>">
                            <el-input type="<?php echo $field['value_type'];?>" style="max-width: 500px"  clearable placeholder="请输入" v-model="form.<?php echo $field['key'];?>"></el-input>
                        </el-form-item>
                        <?php
                    }

                    if ($field['type'] == 'ue') {?>
                        <el-form-item label="<?php echo $field['chs_title'];?>"  prop="<?php echo $field['key'];?>">
                            <UE id="<?php echo $module['edit'];?>" :initcontent="form.<?php echo $field['key'];?>"  ref="<?php echo $field['key'];?>"></UE>
                        </el-form-item>
                        <?php
                    }

                    if ($field['type'] == 'other') {?>
                        <el-form-item label="<?php echo $field['chs_title'];?>"  prop="<?php echo $field['key'];?>">

                        </el-form-item>
                        <?php
                    }

                    if ($field['type'] == 'switch') {?>
                        <el-form-item label="<?php echo $field['chs_title'];?>"  prop="<?php echo $field['key'];?>">
                            <el-switch
                                    v-model="form.<?php echo $field['key'];?>"
                                    active-color="#13ce66"
                                    inactive-color="#ff4949"
                                    active-value="1"
                                    inactive-value="0"
                            >
                            </el-switch>
                        </el-form-item>
                        <?php
                    }

                    if ($field['type'] == 'datepicker') {?>
                        <el-form-item label="<?php echo $field['chs_title'];?>"  prop="<?php echo $field['key'];?>">

                            <el-date-picker
                                    v-model="form.<?php echo $field['key'];?>"
                                    type="<?php echo $field['date_type'];?>"
                                    value-format="<?php echo $field['value_format'];?>"
                                    placeholder="请选择"
                                    range-separator="至"
                                    start-placeholder="开始"
                                    end-placeholder="结束">
                            </el-date-picker>

                        </el-form-item>
                        <?php
                    }

                    if ($field['type'] == 'textarea') {?>
                        <el-form-item label="<?php echo $field['chs_title'];?>"  prop="<?php echo $field['key'];?>">
                            <el-input
                                    style="max-width: 500px"
                                    type="textarea"
                                    :autosize="{ minRows: 4, maxRows: 7}"
                                    placeholder="请输入"
                                    v-model="form.<?php echo $field['key'];?>">
                            </el-input>
                        </el-form-item>
                        <?php
                    }

                    if ($field['type'] == 'select') {?>
                        <el-form-item label="<?php echo $field['chs_title'];?>"  prop="<?php echo $field['key'];?>">
                            <el-select clearable :multiple="<?php echo !empty($field['multi']) ? 'true':'false';?>" v-model="form.<?php echo $field['key'];?>" placeholder="请选择" >
                                <?php if (is_array($field['options']['items'])) { ?>
                                    <el-option
                                            v-for='item in <?php echo json_encode($field['options']['items'], JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES); ?>'
                                            :key="item.<?php echo $field['options']['key']; ?>"
                                            :label="item.<?php echo $field['options']['label']; ?>"
                                            :value="item<?php echo !empty($field['options']['value']) ? '.'.$field['options']['value'] : ''; ?>">
                                    </el-option>
                                <?php } ?>

                                <?php if (!is_array($field['options']['items'])) { ?>
                                    <el-option
                                            v-for="item in select_options.<?php echo $field['options']['items_vue_ins']; ?>"
                                            :key="item.<?php echo $field['options']['key']; ?>"
                                            :label="item.<?php echo $field['options']['label']; ?>"
                                            :value="item<?php echo !empty($field['options']['value']) ? '.'.$field['options']['value'] : ''; ?>">
                                    </el-option>
                                <?php } ?>

                            </el-select>
                        </el-form-item>
                        <?php
                    }

                    if ($field['type'] == 'single_image') {?>
                        <el-form-item label="<?php echo $field['chs_title'];?>"  prop="<?php echo $field['key'];?>">
                            <el-upload
                                    class="avatar-uploader"
                                    :action="$store.state.constant.upload_url"
                                    :on-remove="(file, fileList) => {return handleRemove(file, fileList, 'single_image', '<?php echo $field['key'];?>')}"
                                    :show-file-list="false"
                                    :on-exceed="(files, fileList) => {return handleExceed(files, fileList, 'single_image', '<?php echo $field['key'];?>')}"
                                    :on-success="(res, file, fileList) => {return handleSuccess(res, file, fileList, 'single_image', '<?php echo $field['key'];?>')}"
                                    :before-upload="(file) => {return beforeUpload(file, 'single_image', '<?php echo $field['key'];?>',fields_obj)}">
                                <img v-if="form.<?php echo $field['key'];?>" :src="form.<?php echo $field['key'];?>" class="avatar">
                                <i v-else class="el-icon-plus avatar-uploader-icon"  ></i>
                                <!--<el-button size="small" type="primary">点击上传</el-button>-->
                                <div slot="tip" class="el-upload__tip"><?php echo $field['tips'];?>;且大小不超过<?php echo $field['max_size'];?>M</div>
                            </el-upload>

                        </el-form-item>
                        <?php
                    }

                    if ($field['type'] == 'multi_image') {?>
                        <el-form-item label="<?php echo $field['chs_title'];?>"  prop="<?php echo $field['key'];?>">
                            <el-upload
                                    class="avatar-uploader"
                                    :action="$store.state.constant.upload_url"
                                    multiple
                                    :limit="<?php echo $field['max_num'];?>"
                                    :file-list="form.<?php echo $field['key'];?>"
                                    list-type="picture-card"
                                    :on-remove="(file, fileList) => {return handleRemove(file, fileList, 'multi_image', '<?php echo $field['key'];?>')}"
                                    :on-exceed="(files, fileList) => {return handleExceed(files, fileList, 'multi_image', '<?php echo $field['key'];?>')}"
                                    :on-success="(res, file, fileList) => {return handleSuccess(res, file, fileList, 'multi_image', '<?php echo $field['key'];?>')}"
                                    :before-upload="(file) => {return beforeUpload(file, 'multi_image', '<?php echo $field['key'];?>',fields_obj)}">
                                <i class="el-icon-plus avatar-uploader-icon"  ></i>
                                <div slot="tip" class="el-upload__tip"><?php echo $field['tips'];?>;最多上传<?php echo $field['max_num'];?>张;每张大小不超过<?php echo $field['max_size'];?>M</div>
                            </el-upload>

                        </el-form-item>
                        <?php
                    }

                }
                ?>

            </el-form>

            <el-button type="success" style="margin-top: 20px;width: 100%;height: 50px;" v-on:click="submit" :loading="$store.state.loading.<?php echo $module['name'];?>edit">提交</el-button>

            </el-card>
        </div>

    </div>
</template>

<script>
    import {common_api} from '@/api/getDataEarth'
    import {mixin_edit} from '@/config/mixin'
    <?php if($module['edit']['use_ue']){?>import UE from '@/components/ue'<?php }?>

    export default {
        name:'<?php echo $module['name'];?>_edit',
        mixins: [mixin_edit],
        data(){
            return {
                module_name:"<?php echo $module['name'];?>",
                fields_obj:<?php echo $module['edit']['fields_obj'];?>,
                //表单数据
                form: <?php echo $module['edit']['form_init_obj'];?>,
                form_init: <?php echo $module['edit']['form_init_obj'];?>,
                //rules
                rules: <?php echo $module['edit']['form_rules'];?>,
            }

        },
        components: {
            <?php if($module['edit']['use_ue']){?>UE<?php }?>
        },
        created(){

        },
        mounted(){

        },

        beforeRouteEnter (to, from, next) {
            next(async vm => {
                // 通过 `vm` 访问组件实例
//                 vm.id = to.query.id ? to.query.id : 0;
// //                console.log(vm.id )
//                 //初始化一些select选项
//                 await vm.init_select_options();
//                 //初始化数据
//                 if (vm.id && vm.id > 0) {
//                     vm.get_info();
//                 } else {
//                     vm.init();
//                 }
                vm.init(to.query.id)
            })
        },
        methods: {
            async init_select_options() {
                <?php
                foreach($module['edit']['fields'] as $_key => $field){
                if ($field['type'] == 'select' && !is_array($field['options']['items'])) {
                ?>
                await common_api('<?php echo $field['options']['items'];?>', {}).then((res) => {
                    if (res.code == this.$store.state.constant.status_success) {

                        this.$set(this.select_options, '<?php echo $field['options']['items_vue_ins'];?>', res.data);
                    } else {
                        this.$message({
                            message: res.msg,
                            type: 'warning'
                        });
                    }
                });
                <?php
                }
                }?>
            },

            <?php if($module['edit']['use_ue']){?>
            getUeContent(){
                <?php foreach($module['edit']['fields'] as $field){?>
                <?php if($field['type'] == 'ue'){?>
                this.form.<?php echo $field['key'];?> = this.$refs.<?php echo $field['key'];?>.getUEContent();
                <?php }}?>
            }

            <?php }?>

        }
    }
</script>

<style lang="less">
    @import '../style/mixin';
    .search_item{
        margin-top: 10px;
    }
    .avatar-uploader .el-upload {
        border: 1px dashed #d9d9d9;
        border-radius: 6px;
        cursor: pointer;
        position: relative;
        overflow: hidden;
    }
    .avatar-uploader .el-upload:hover {
        border-color: #409EFF;
    }
    .avatar-uploader-icon {
        font-size: 28px;
        color: #8c939d;
        width: 150px;
        height: 150px;
        line-height: 150px;
        text-align: center;
    }
    .avatar {
        width: 150px;
        height: 150px;
        display: block;
    }
    .el-upload--picture-card {
        width: 150px;
        height: 150px;
    }
</style>
