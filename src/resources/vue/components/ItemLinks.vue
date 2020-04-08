<template>
    <div>
        <template v-for="(item, index) in items">
            <div class="links py-1">
                <v-item-link
                    :item="item"
                    :is-default="index < 1"
                    @delete="deleteItem"
                ></v-item-link>
            </div>
        </template>
    </div>
</template>

<<script>
    import VItemLink from './ItemLink'

    export default {
        components: {
            VItemLink
        },
        props: {
            items: {
                type: Array
            }
        },
        methods: {
            deleteItem(item) {
                this.items.splice(this.items.indexOf(item), 1)
            }
        },
    }
</script>
