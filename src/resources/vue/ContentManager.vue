<template>
    <v-app v-cloak>
        <v-header :drawer.sync="drawer" :items.sync="items"></v-header>
        <v-sidebar v-model="drawer" :items.sync="items"></v-sidebar>
        <v-content>
            <v-contents :items.sync="items"></v-contents>
        </v-content>

        <v-overlay :value="loading" z-index="1000">
            <v-progress-circular indeterminate size="96" width="6"></v-progress-circular>
        </v-overlay>
    </v-app>
</template>

<script>
    import VHeader from './layouts/Header'
    import VSidebar from './layouts/Sidebar'
    import VContents from './layouts/Contents'

    export default {
        components: {
            VHeader,
            VSidebar,
            VContents
        },
        data: () => ({
            loading: false,
            drawer: null,
            items: []
        }),
        created() {
            this.$store.watch(
                (state, getters) => getters.loading,
                (val) => {
                    this.loading = val
                }
            )

            this.$store.watch(
                (state, getters) => getters.groups,
                (val) => {
                    this.items = [...val]
                }
            )
        }
    }
</script>
