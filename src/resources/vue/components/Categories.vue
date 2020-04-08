<template>
    <v-sheet v-if="items.length" class="mb-4">
        <v-tabs
            v-model="tab"
            dark
            center-active
            show-arrows
            background-color="warning"
            slider-color="orange lighten-5"
            mobile-break-point="960px"
            @change="changeItem"
        >
            <v-tab
                fixed-tabs
                v-for="(item, index) in items"
                :key="index"
            >
                {{ item.name }}
            </v-tab>
        </v-tabs>
    </v-sheet>
</template>

<script>
    export default {
        model: {
            prop: 'value',
            event: 'update:value'
        },
        props: {
            value: {
                type: Object,
                default() {
                    return {}
                }
            },
            items: {
                type: Array,
                default() {
                    return []
                }
            }
        },
        data: () => ({
            tab: 0
        }),
        methods: {
            changeItem() {
                this.$emit('update:value', this.items[this.tab])
            }
        },
        mounted() {
            for (let i in this.items) {
                if (this.value && this.value.id == this.items[i].id) {
                    this.tab = Number(i)
                    break
                }
            }
        }
    }
</script>
