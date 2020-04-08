<template>
    <v-treeview
        :items="items"
        :active.sync="activeItems"
        :open.sync="openItems"
        item-key="code"
        item-text="name"
        transition
        dense
        activatable
        open-on-click
        return-object
        color="warning"
        expand-icon="mdi-chevron-down"
        class="mt-3"
        @update:active="updateActiveItems"
    >
        <template v-slot:label="item">
            <span color="grey darken-3">{{ item.item.name }}</span>
        </template>
    </v-treeview>
</template>

<script>
    import {EventBus} from '../event_bus'

    export default {
        props: {
            items: {
                type: Array,
                default() {
                    return []
                }
            }
        },
        data: () => ({
            activeItems: [],
            openItems: []
        }),
        watch: {
            items: {
                handler(val) {
                    this.setActiveItems(this.items)
                },
                deep: true
            }
        },
        methods: {
            setActiveItems(items) {
                for (let item of items) {
                    let active = item.active
                    delete item.active

                    if (active) {
                        this.activeItems.push(item)
                        if (item.children) {
                            this.openItems.push(item)
                        }
                        return item
                    }

                    if (item.children) {
                        let child_item = this.setActiveItems(item.children)
                        if (child_item) {
                            this.openItems.push(item)
                            return child_item
                        }
                    }
                }

                return false
            },
            updateActiveItems() {
                EventBus.$emit('select-group', this.activeItems.length ? this.activeItems[0] : null)
            }
        }
    }
</script>
