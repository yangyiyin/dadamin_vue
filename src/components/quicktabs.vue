<template>
    <div>
        <el-tabs v-model="$store.state.editableTabsValue" type="card" @tab-remove="removeTab" @tab-click="clickTab">
            <el-tab-pane
                    v-for="(item, index) in $store.state.editableTabs"
                    :key="item.name"
                    :label="item.title"
                    :name="item.name"
                    :url="item.url"
                    :closable = "item.closable"
            >
            </el-tab-pane>
        </el-tabs>
    </div>
</template>
<script>

    export default {
        name: 'quicktabs',
        props: [],
        data () {
            return {
            }
        },
        created(){

        },
        mounted(){

        },
        methods:{
            removeTab(name) {
                let tabs = this.$store.state.editableTabs;
                let activeName = this.$store.state.editableTabsValue;
                if (activeName === name) {
                    tabs.forEach((tab, index) => {
                        if (tab.name === name) {
                            let nextTab = tabs[index + 1] || tabs[index - 1];
                            if (nextTab) {
                                activeName = nextTab.name;
                            }
                        }
                    });
                }

                this.$store.state.editableTabsValue = activeName;
                this.$store.state.editableTabs = tabs.filter(tab => tab.name !== name);
                this.$store.commit('set_keepalive_include', this.$store.state.keepalive_include.filter(item => item !== name));
                // console.log(this.$store.state.keepalive_include)
                // console.log(activeName)
                var activeTab = tabs.filter(tab => tab.name == this.$store.state.editableTabsValue);
                console.log(activeTab)
                this.clickTab({name:activeName,$attrs:{url:activeTab[0] ? activeTab[0].url : ''}});
            },
            clickTab(tab, event) {
                if (('/'+tab.$attrs.url ) != this.$route.fullPath) {
                    this.$router.push('/'+tab.$attrs.url);
                }
            }
        }
    }
</script>
