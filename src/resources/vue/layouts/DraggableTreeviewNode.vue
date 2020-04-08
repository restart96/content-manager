<template>
    <div class="v-treeview-node v-treeview-node--click">
        <div class="v-treeview-node__root" :class="activeClass" @click="setActiveItem">
            <div class="v-treeview-node__content">
                <div class="v-treeview-node__prepend" v-if="!!$scopedSlots['prepend']">
                    <slot name="prepend" v-bind="item"/>
                </div>
                <div class="v-treeview-node__label">
                    <slot name="label" v-bind="item">
                        {{ item[itemText] }}
                    </slot>
                </div>
                <div class="v-treeview-node__append" v-if="!!$scopedSlots['append']">
                    <slot name="append" v-bind="item"/>
                </div>
            </div>
        </div>
        <div class="v-treeview-node__children v-treeview-node__children__draggable">
            <v-draggable
                :value="item[itemChildren]"
                :group="group"
                :handle="handle"
                animation="150"
                @input="updateItems"
            >
                <v-draggable-treeview-node
                    v-for="child in item[itemChildren]"
                    :key="child[itemKey]"
                    :item="child"
                    :itemKey="itemKey"
                    :itemText="itemText"
                    :itemChildren="itemChildren"
                    :active.sync="nodeActive"
                    :group="group"
                    :dense="dense"
                    :color="color"
                    :expandIcon="expandIcon"
                    @input="updateItem"
                >
                    <template v-slot:prepend="child">
                        <slot name="prepend" v-bind="child"/>
                    </template>
                    <template v-slot:label="child">
                        <slot name="label" v-bind="child"/>
                    </template>
                    <template v-slot:append="child">
                        <slot name="append" v-bind="child"/>
                    </template>
                </v-draggable-treeview-node>
            </v-draggable>
        </div>
    </div>
</template>

<script>
    import VDraggable from 'vuedraggable'

    export default {
        name: 'VDraggableTreeviewNode',
        components: {
            VDraggable
        },
        props: {
            item: {
                type: Object,
                default() {
                    return {}
                }
            },
            itemKey: {
                type: String
            },
            itemText: {
                type: String
            },
            itemChildren: {
                type: String
            },
            active: {
                type: Object,
                default() {
                    return {}
                }
            },
            group: {
                type: String
            },
            handle: {
                type: String
            },
            dense: {
                type: Boolean
            },
            color: {
                type: String
            },
            expandIcon: {
                type: String
            }
        },
        computed: {
            isDark() {
                return this.$vuetify.theme.isDark
            },
            activeClass() {
                if (this.active && this.active[this.itemKey] === this.item[this.itemKey]) {
                    return 'v-treeview-node--active ' + this.color + '--text'
                }
                return ''
            },
            nodeActive: {
                get() {
                    return this.active
                },
                set(val) {
                    this.$emit('update:active', val)
                }
            }
        },
        methods: {
            setActiveItem() {
                const equal = this.active && this.active[this.itemKey] === this.item[this.itemKey]
                this.$emit('update:active', equal ? null : this.item)
            },
            updateItems(val) {
                this.$set(this.item, this.itemChildren, val)
                this.$emit('input', this.item)
            },
            updateItem(val) {
                this.$emit('input', this.item)
            }
        }
    }
</script>

<style scoped>
    .v-treeview-node .v-treeview-node__root {padding-left:8px;}
    .v-treeview-node .v-treeview-node__children .v-treeview-node__content {padding-left:24px;}
    .v-treeview-node .v-treeview-node__children .v-treeview-node__children .v-treeview-node__content {padding-left:48px;}
    .v-treeview-node .v-treeview-node__children .v-treeview-node__children .v-treeview-node__children .v-treeview-node__content {padding-left:72px;}
    .v-treeview-node .v-treeview-node__children .v-treeview-node__children .v-treeview-node__children .v-treeview-node__children .v-treeview-node__content {padding-left:96px;}
</style>
