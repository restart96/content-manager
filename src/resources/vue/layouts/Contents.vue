<template>
    <v-container fluid>
        <template v-if="group === null">
            <div class="my-12 py-12 text-center">
                <p class="ma-0 grey--text text--darken-1">항목을 선택해 주세요.</p>
            </div>
        </template>
        <template v-else>
            <v-group-edit v-if="editing" :items="items" :group.sync="group"></v-group-edit>
            <v-group-view v-else :items="items" :group.sync="group" :category.sync="category"></v-group-view>
        </template>
    </v-container>
</template>

<script>
    import {EventBus} from '../event_bus'
    import VGroupView from '../components/GroupView'
    import VGroupEdit from '../components/GroupEdit'

    export default {
        components: {
            VGroupView,
            VGroupEdit
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
            group: null,
            category: null
        }),
        computed: {
            editing() {
                return this.$store.getters.editing
            }
        },
        watch: {
            editing() {
                this.group = null
                this.category = null
            }
        },
        methods: {
            onSelectGroup(group) {
                this.group = group
                this.category = null

                if (this.group && this.group.categories && this.group.categories.length) {
                    for (let category of this.group.categories) {
                        if (category.active) {
                            this.category = category
                        }
                        delete category.active
                    }
                    if (!this.category) {
                        this.category = this.group.categories[0]
                    }
                }
            },
            onEditGroup(group) {
                this.group = group
            }
        },
        created() {
            EventBus.$on('select-group', this.onSelectGroup)
            EventBus.$on('edit-group', this.onEditGroup)
        },
        beforeDestroy() {
            EventBus.$off('select-group', this.onSelectGroup)
            EventBus.$off('edit-group', this.onEditGroup)
        }
    }
</script>
