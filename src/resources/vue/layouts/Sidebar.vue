<template>
    <div>
        <v-navigation-drawer
            v-model="drawer"
            app
            fixed
            clipped
            mobile-break-point="960px"
        >
            <v-sidebar-edit class="mt-3" v-if="editing" :items.sync="_items"></v-sidebar-edit>
            <v-sidebar-view class="mt-3" v-else :items.sync="_items"></v-sidebar-view>

            <template v-slot:append>
                <div v-if="editing" class="mx-2 my-3">
                    <v-btn color="success" outlined block @click="createGroup">메뉴 생성</v-btn>
                </div>
            </template>
        </v-navigation-drawer>
    </div>
</template>

<script>
    import {EventBus} from '../event_bus'
    import VSidebarView from '../components/SidebarView'
    import VSidebarEdit from '../components/SidebarEdit'

    export default {
        components: {
            VSidebarView,
            VSidebarEdit
        },
        model: {
            prop: 'value',
            event: 'update:value'
        },
        props: {
            value: {
                type: Boolean,
                default: false
            },
            items: {
                type: Array,
                default() {
                    return []
                }
            }
        },
        computed: {
            drawer: {
                get() {
                    return this.value
                },
                set(val) {
                    this.$emit('update:value', val)
                }
            },
            _items: {
                get() {
                    return this.items
                },
                set(val) {
                    this.$emit('update:items', val)
                }
            },
            editing() {
                return this.$store.getters.editing
            }
        },
        methods: {
            createGroup() {
                EventBus.$emit('create-group')
            }
        },
        created() {
            EventBus.$on('created-group', (group) => {
                this.$set(this._items, this._items.length, group)
            })
        }
    }
</script>
