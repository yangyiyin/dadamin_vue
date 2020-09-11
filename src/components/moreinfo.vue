<template>
    <div>
        <div ref="info" :style="'height:'+default_height+';overflow:hidden'">
            <slot></slot>
        </div>
        <div>
            <template v-if="!hide_divider_btn && !simple">
                <el-divider @click="down" v-if="default_height != 'auto'"><span style="font-size: 12px;color: #999;margin-right: 10px;vertical-align: middle;cursor: pointer;">更多选项</span><i style="cursor: pointer;vertical-align: middle" class="iconfont">&#xe61f;</i></el-divider>
                <el-divider @click="pull" v-if="default_height == 'auto'"><i style="cursor: pointer" class="iconfont">&#xe620;</i></el-divider>
            </template>

            <template v-if="!hide_divider_btn && simple">
                <el-divider @click="down" v-if="default_height != 'auto'"><i style="cursor: pointer;vertical-align: middle" class="iconfont">&#xe61f;</i></el-divider>
                <el-divider @click="pull" v-if="default_height == 'auto'"><i style="cursor: pointer" class="iconfont">&#xe620;</i></el-divider>
            </template>

            <template v-else>
                <el-divider></el-divider>
            </template>
        </div>
    </div>
</template>
<script>

    export default {
        name: 'moreinfo',
        props: ['defaultheight', 'hide_divider_btn', 'simple'],
        data () {
            return {
                height:0,
                default_height:'auto'
            }
        },
        created(){
            this.default_height = this.defaultheight || '0px';
        },
        mounted(){
            //获取高度值
            // var height= this.$refs.info.offsetHeight;//100
            // console.log(height);
        },
        methods:{
            pull(){
                this.default_height = this.defaultheight || '0px';
            },
            down(){
                this.default_height = 'auto';
            }
        }
    }
</script>
