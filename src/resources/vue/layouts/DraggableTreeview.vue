<template>
    <v-draggable
        class="v-treeview v-treeview-draggable"
        :class="{
            'v-treeview--dense': dense,
            'theme--dark': isDark,
            'theme--light': !isDark
        }"
        :value="items"
        :group="group"
        :handle="handle"
        animation="150"
        @input="updateItems"
    >
        <v-draggable-treeview-node
            v-for="item in items"
            :key="item[itemKey]"
            :item="item"
            :itemKey="itemKey"
            :itemText="itemText"
            :itemChildren="itemChildren"
            :active.sync="nodeActive"
            :group="group"
            :handle="handle"
            :dense="dense"
            :color="color"
            :expandIcon="expandIcon"
        >
            <template v-slot:prepend="item">
                <slot name="prepend" v-bind="item"/>
            </template>
            <template v-slot:label="item">
                <slot name="label" v-bind="item"/>
            </template>
            <template v-slot:append="item">
                <slot name="append" v-bind="item"/>
            </template>
        </v-draggable-treeview-node>
    </v-draggable>
</template>

<script>
    import VDraggable from 'vuedraggable'
    import VDraggableTreeviewNode from './DraggableTreeviewNode'

    export default {
        components: {
            VDraggable,
            VDraggableTreeviewNode
        },
        props: {
            items: {
                type: Array,
                default() {
                    return []
                }
            },
            itemKey: {
                type: String,
                default: 'id'
            },
            itemText: {
                type: String,
                default: 'name'
            },
            itemChildren: {
                type: String,
                default: 'children'
            },
            active: {
                type: Object,
                default() {
                    return {}
                }
            },
            handle: {
                type: String,
                default: null
            },
            dense: {
                type: Boolean,
                default: false
            },
            color: {
                type: String,
                default: 'primary'
            },
            expandIcon: {
                type: String,
                default: 'mdi-menu-down'
            }
        },
        data: () => ({
            group: 'group'
        }),
        computed: {
            isDark() {
                return this.$vuetify.theme.isDark
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
            updateItems(val) {
                this.$emit('update:items', val)
            }
        }
    }
</script>
