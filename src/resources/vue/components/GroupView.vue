<template>
    <div>
        <v-categories v-model="activeCategory" :items="group.categories"></v-categories>
        <v-items v-if="!!category" :group="group" :category="category"></v-items>
    </div>
</template>

<script>
    import VCategories from './Categories'
    import VItems from './Items'

    export default {
        components: {
            VCategories,
            VItems
        },
        props: {
            items: {
                type: Array,
                default() {
                    return []
                }
            },
            group: {
                type: Object,
                default() {
                    return {}
                }
            },
            category: {
                type: Object,
                default() {
                    return {}
                }
            }
        },
        computed: {
            activeCategory: {
                get() {
                    return this.category
                },
                set(val) {
                    this.$emit('update:category', val)
                }
            }
        },
        watch: {
            category: {
                handler() {
                    this.updateUrl()
                },
                deep: true,
                immediate: true
            }
        },
        methods: {
            updateUrl() {
                let url = APP_URL
                if (this.group) {
                    url += '/' + this.group.code.toLowerCase()
                    if (this.category) {
                        url += '/' + this.category.code.toLowerCase()
                    }
                }
                window.history.pushState({path: url}, '', url)
            }
        }
    }
</script>
