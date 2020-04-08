<template>
    <div>
        <v-draggable-treeview
            :items.sync="treeItems"
            :active.sync="activeItem"
            item-key="id"
            item-text="name"
            dense
            color="warning"
            expand-icon="mdi-chevron-down"
        >
            <template v-slot:prepend="item">
                <v-icon class="handle" @click="">mdi-reorder-horizontal</v-icon>
            </template>
            <template v-slot:label="item">
                <span color="grey darken-3">{{ item.name }}</span>
            </template>
            <template v-slot:append="item">
                <v-icon color="error" @click.stop="deleteGroup(item, treeItems)">mdi-delete</v-icon>
            </template>
        </v-draggable-treeview>
    </div>
</template>

<script>
    import {EventBus} from '../event_bus'
    import VDraggableTreeview from '../layouts/DraggableTreeview'

    export default {
        components: {
            VDraggableTreeview
        },
        props: {
            items: {
                type: Array,
                default() {
                    return []
                }
            }
        },
        data: () => ({
            activeItem: null,
        }),
        computed: {
            treeItems: {
                get() {
                    return this.items
                },
                set(val) {
                    this.$emit('update:items', val)
                }
            }
        },
        watch: {
            activeItem: {
                handler(val) {
                    EventBus.$emit('edit-group', val)
                },
                deep: true
            }
        },
        methods: {
            onCreateGroup() {
                this.activeItem = {}
            },
            deleteGroup(item, items) {
                for (let index in items) {
                    if (items[index].id === item.id) {
                        items.splice(index, 1)
                        if (this.activeItem && this.activeItem.id === item.id) {
                            this.activeItem = null
                        }
                        return true
                    }
                    if (items[index].children) {
                        if (this.deleteGroup(item, items[index].children)) {
                            return true
                        }
                    }
                }
            }
        },
        created() {
            EventBus.$on('create-group', this.onCreateGroup)
        },
        beforeDestroy() {
            EventBus.$off('create-group', this.onCreateGroup)
        }
    }
</script>
