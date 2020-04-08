<template>
    <div>
        <template v-if="loading">
            <div class="my-12 py-12 text-center">
                <v-progress-circular indeterminate color="grey lighten-2"/>
            </div>
        </template>
        <template v-else>
            <v-alert dense text type="warning" class="my-4" v-if="!!errorMessage">
                {{ errorMessage }}
            </v-alert>
            <div v-else>
                <div class="d-flex">
                    <v-btn class="mb-2" color="success" v-show="editing" @click.stop.prevent="updateItems">업데이트</v-btn>
                    <div class="flex-grow-1"></div>
                    <v-btn class="mb-2" color="success" outlined @click.stop.prevent="createItem">콘텐츠 등록</v-btn>
                </div>
                <v-data-table
                    ref="items"
                    :headers="headers"
                    :items="items"
                    class="elevation-1"
                    :mobile-breakpoint="0"
                    disable-sort
                    disable-pagination
                    hide-default-footer
                    calculate-widths
                >
                    <template v-slot:body="{ headers, items }">
                        <template v-if="!!items && items.length">
                            <v-draggable
                                :value="items"
                                animation="150"
                                tag="tbody"
                                handle=".handle"
                                @input="sortItems"
                            >
                                <tr v-for="(item, index) in items" :key="index">
                                    <td>
                                        <v-icon @click="" class="handle">mdi-reorder-horizontal</v-icon>
                                    </td>
                                    <td class="d-none d-md-table-cell">
                                        {{ item.ord }}
                                    </td>
                                    <td class="d-none d-sm-table-cell">
                                        <v-checkbox
                                            v-model="item.enable"
                                            class="justify-center"
                                            color="primary"
                                            true-value="Y"
                                            false-value="N"
                                            hide-details
                                            @change="editingItem"
                                        ></v-checkbox>
                                    </td>
                                    <td>
                                        <template v-if="item.link.url">
                                            <a :href="item.link.url" target="_blank">
                                                <div class="text-left text-truncate">{{ item.name }}</div>
                                            </a>
                                        </template>
                                        <template v-else>
                                            <div class="text-left text-truncate">{{ item.name }}</div>
                                        </template>
                                    </td>
                                    <td class="d-none d-sm-table-cell">
                                        <div class="image">
                                            <img v-if="hasImage(item)" :src="getImage(item)"/>
                                        </div>
                                    </td>
                                    <td>
                                        <v-btn outlined fab x-small color="success" @click="editItem(item)">
                                            <v-icon>mdi-pencil</v-icon>
                                        </v-btn>
                                    </td>
                                </tr>
                            </v-draggable>
                        </template>
                        <template v-else>
                            <tbody>
                                <tr class="no-data">
                                    <td :colspan="headers.length">
                                        <div class="my-12 py-12 text-center">
                                            <p class="ma-0 grey--text text--darken-1">등록된 콘텐츠가 없습니다.</p>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </template>
                    </template>
                </v-data-table>
            </div>
        </template>

        <v-dialog-item-create
            v-model="dialog"
            :group="group"
            :category="category"
            :item="item"
        ></v-dialog-item-create>
    </div>
</template>

<script>
    import {EventBus} from '../event_bus'
    import VDraggable from 'vuedraggable'
    import VDialogItemCreate from './dialogs/ItemCreate'

    export default {
        components: {
            VDraggable,
            VDialogItemCreate
        },
        props: {
            group: {
                type: Object
            },
            category: {
                type: Object
            }
        },
        data: () => ({
            loading: false,
            errorMessage: null,
            editing: false,
            dialog: false,
            headers: [
                {text: 'SORT', align: 'center', width: '64'},
                {text: 'NO', align: 'center', width: '64', class: ['d-none', 'd-md-table-cell']},
                {text: 'ENABLE', align: 'center', width: '60', class: ['d-none', 'd-sm-table-cell']},
                {text: 'TITLE', align: 'center', width: '100%'},
                {text: 'IMAGE', align: 'center', width: '40%', class: ['d-none', 'd-sm-table-cell']},
                {text: 'ACTIONS', align: 'center', width: '90'},
            ],
            items: [],
            item: null
        }),
        watch: {
            category: {
                handler(val) {
                    this.getItems()
                },
                deep: true,
                immediate: true
            }
        },
        methods: {
            createItem() {
                this.item = null
                this.dialog = true
            },
            editItem(item) {
                this.item = item
                this.dialog = true
            },
            editingItem() {
                this.editing = true
            },
            sortItems(val) {
                this.editing = true
                this.items = [...val]
            },
            updateItems() {
                this.$store.commit('setLoading', true)

                let url = APP_URL + '/' + this.group.code.toLowerCase() + '/' + this.category.code.toLowerCase() + '/items'

                this.$axios
                    .patch(url, {
                        items: this.items
                    })
                    .then(response => {
                        this.items = response.data.items
                        this.editing = false
                        this.errorMessage = null
                    })
                    .catch(error => {
                        if (error.response && error.response.data) {
                            this.errorMessage = error.response.data.message
                        } else {
                            this.errorMessage = '업데이트에 실패했습니다.'
                        }
                    })
                    .finally(() => {
                        this.$store.commit('setLoading', false)
                    })
            },
            async getItems() {

                if (this.$axios.source) {
                    this.$axios.source.cancel();
                    this.$axios.source = null
                }

                if (!this.category) {
                    this.loading = false
                    return
                }

                this.loading = true
                this.$axios.source = this.$axios.CancelToken.source()

                let url = APP_URL + '/' + this.group.code.toLowerCase() + '/' + this.category.code.toLowerCase() + '/items'

                this.$axios
                    .get(url, {
                        cancelToken: this.$axios.source.token
                    })
                    .then(response => {
                        this.items = response.data.items
                        this.errorMessage = null
                        this.loading = false
                    })
                    .catch(error => {
                        if (!this.$axios.isCancel(error)) {
                            if (error.response && error.response.data) {
                                this.errorMessage = error.response.data.message
                            } else {
                                this.errorMessage = '요청 처리에 실패했습니다.'
                            }
                            this.loading = false
                        }
                    })
            },
            hasImage(item) {
                return !!this.getImage(item)
            },
            getImage(item) {
                if (item.images) {
                    for (let image of item.images) {
                        return image.url
                    }
                }
                return null
            }
        },
        mounted() {
            EventBus.$on('created-item', (items) => {
                this.items = items
            })
            EventBus.$on('updated-item', (items) => {
                this.items = items
            })
            EventBus.$on('deleted-item', (items) => {
                this.items = items
            })
        }
    }
</script>

<style scoped>
    .v-data-table >>> table {table-layout:fixed;}
    .v-data-table >>> table tr td {padding-top:4px;padding-bottom:4px;text-align:center;}
    .v-data-table >>> table tr td .image {width:100%;height:7em;}
    .v-data-table >>> table tr td .image img {display:block;width:100%;height:100%;object-fit:cover;}
    .v-data-table >>> table tbody tr:hover {background:#f6f6f6 !important;}
    .v-data-table >>> table tbody tr.sortable-ghost {background:#f6f6f6 !important;}
    .v-data-table >>> table tbody tr.no-data:hover {background:#fff !important;}
    .v-data-table >>> .v-input--checkbox {margin:0;padding:0;}
    .v-data-table >>> .v-input--selection-controls__input {margin:0 auto;}

    @media screen and (min-width: 600px) {
        .v-data-table >>> table tbody tr td:nth-child(4) div {text-align:center !important;}
    }
</style>
