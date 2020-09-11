<template>
    <div class="fillcontain">

        <div class="table_container" style="padding-bottom: 0">
            <moreinfo defaultheight="auto" :hide_divider_btn="true">

                <?php
                foreach($module['list']['search'] as $_key => $field){
                    if ($field['type'] == 'input') {?>
                        <div class="search-item">
                            <div class="preinfo"><?php echo $field['chs_title'];?>:</div>
                            <el-input
                                    v-model="search_data.<?php echo $field['key'];?>"
                                    clearable>
                            </el-input>
                        </div>
                        <?php
                    }

                    if ($field['type'] == 'datepicker') {?>
                        <div class="search-item">
                            <div class="preinfo"><?php echo $field['chs_title'];?>:</div>
                            <el-date-picker
                                    v-model="search_data.<?php echo $field['key'];?>"
                                    type="<?php echo $field['date_type'];?>"
                                    value-format="<?php echo $field['value_format'];?>"
                                    placeholder="请选择"
                                    range-separator="至"
                                    start-placeholder="开始"
                                    end-placeholder="结束">
                            </el-date-picker>
                        </div>
                        <?php
                    }

                    if ($field['type'] == 'select') {?>
                        <div class="search-item">
                            <div class="preinfo"><?php echo $field['chs_title'];?>:</div>
                            <el-select clearable :multiple="<?php echo !empty($field['multi']) ? 'true':'false';?>" v-model="search_data.<?php echo $field['key'];?>" placeholder="请选择" >
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
                        </div>
                        <?php
                    }

                }
                ?>
                <?php if (!empty($module['list']['add_link_name'])){?>
                    <el-button style="float: right;margin-left: 40px;" icon="el-icon-plus" type="success" @click="goto('/<?php echo $module['name']; ?>_edit',{})"><?php echo $module['list']['add_link_name']; ?></el-button>
                <?php }?>
                <el-button type="primary" style="float: right;" icon="el-icon-search" @click="search">搜索</el-button>
            </moreinfo>

        </div>
        <el-card class="table_container" v-loading="$store.state.loading.<?php echo $module['name'];?>index">
            <el-table
                    :data="tableData"
                    style="width: 100%">
                <?php foreach ($module['list']['fields'] as $k => $field){
                    if ($k == 'img') {
                    ?>
                        <el-table-column label="图片">
                            <template slot-scope="scope">
                                <el-image
                                        style="width: 40px; height: 40px"
                                        :src="scope.row.<?php echo $field; ?>"
                                        :preview-src-list="[scope.row.<?php echo $field; ?>]">
                                </el-image>
                            </template>
                        </el-table-column>
                <?php } else { ?>
                        <el-table-column label="<?php echo $k; ?>" prop="<?php echo $field; ?>"></el-table-column>
                    <?php }
                }?>

                <?php if (!empty($module['list']['actions'])){ ?>
                <el-table-column label="操作" width="300">
                    <template slot-scope="scope">
                        <?php foreach ($module['list']['actions'] as $k => $field){
                            if ($field == 'sort') {
                                ?>
                                <el-button  @click="handleSort(scope.row)">排序:{{scope.row.sort}}</el-button>
                                <?php
                            }

                            if ($field == 'verify') {
                                ?>
                                <el-button  v-if="scope.row.status == 1" @click="verify(scope, 0)" :loading="$store.state.loading.<?php echo $module['name'];?>verify&& doing_id==scope.row.id">禁用</el-button>
                                <el-button  v-if="scope.row.status == 0" @click="verify(scope, 1)" :loading="$store.state.loading.<?php echo $module['name'];?>verify&& doing_id==scope.row.id">启用</el-button>
                                <?php
                            }

                            if ($field == 'edit') {
                                ?>
                                <el-button  @click="goto('/<?php echo $module['name']; ?>_edit', {id:scope.row.id})">编辑</el-button>
                                <?php
                            }

                            if ($field == 'del') {
                                ?>
                                <el-button  @click="del(scope.row, scope.$index)">删除</el-button>
                                <?php
                            }
                        }?>

                    </template>
                </el-table-column>
                <?php } ?>
            </el-table>
            <div class="Pagination" style="text-align: left;margin-top: 10px;">
                <el-pagination
                        @current-change="handleCurrentChange"
                        :current-page="currentPage"
                        :page-size="limit"
                        layout="total, prev, pager, next"
                        :total="count"
                        background>
                </el-pagination>
            </div>
        </el-card>


        <el-dialog title="修改排序" :visible.sync="dialogFormVisible" width="30%">
            <el-form :model="current">
                <el-form-item label="排序值(越大越靠前)">
                    <el-input v-model="current.sort" auto-complete="off"></el-input>
                </el-form-item>
            </el-form>
            <div slot="footer" class="dialog-footer">
                <el-button @click="dialogFormVisible = false">取 消</el-button>
                <el-button type="primary" @click="sort">确 定</el-button>
            </div>
        </el-dialog>
    </div>
</template>

<script>
    import {common_api} from '@/api/getDataEarth'
    import moreinfo from '@/components/moreinfo'
    import {mixin_list} from '@/config/mixin'
    export default {
        name:'<?php echo $module['name'];?>_list',
        mixins: [mixin_list],
        data(){
            return {
                module_name:"<?php echo $module['name'];?>",
            }
        },
        components: {
            moreinfo,
        },
        created(){
            this.init_select_options();
            this.list();
        },
        mounted(){

        },
        beforeRouteEnter (to, from, next) {
            next(vm => {
                // 通过 `vm` 访问组件实例
                // vm.list();
                vm.refresh = to.query.refresh ? to.query.refresh : false;
                vm.list();
                history.pushState('', '', './#/'+vm.module_name+'_list');
            })
        },
        methods: {
            async init_select_options() {
                <?php
                foreach($module['list']['search'] as $_key => $field){
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
        },
    }
</script>

<style scoped lang="less">
    @import '../style/mixin';
    .table_container{
        padding: 20px;
    }
    .demo-table-expand {
        font-size: 0;
    }
    .demo-table-expand label {
        width: 90px;
        color: #99a9bf;
    }
    .demo-table-expand .el-form-item {
        margin-right: 0;
        margin-bottom: 0;
        width: 50%;
    }
</style>
