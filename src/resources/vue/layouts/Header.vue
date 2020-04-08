<template>
    <div>
        <v-app-bar color="white" app clipped-left>
            <v-app-bar-nav-icon @click.stop="toggleDrawer"></v-app-bar-nav-icon>
            <v-toolbar-title class="align-center">
                <h6 class="overline">CONTENT MANAGER</h6>
                <h3 class="title">콘텐츠 관리</h3>
            </v-toolbar-title>
            <v-spacer></v-spacer>
            <template v-if="editing">
                <v-btn class="d-none d-sm-inline-flex" color="grey lighten-1" outlined @click="unsetEditMenu">취소</v-btn>
                <v-btn class="ml-3 d-none d-sm-inline-flex" color="success" @click="updateMenu">업데이트</v-btn>
            </template>
            <v-btn class="d-none d-sm-inline-flex" color="primary" v-else @click="setEditMenu">메뉴 관리</v-btn>
        </v-app-bar>

        <v-form-dialog
            v-model="dialog"
            :result="result"
            color="success"
            max-width="400px"
            @submit="updateGroup"
            @closed="closedUpdateGroup"
        >
            <template v-slot:title>
                <div class="d-inline-block mx-auto">
                    <v-icon class="flex-grow-1" color="success" size="96">mdi-checkbox-marked-circle-outline</v-icon>
                </div>
            </template>

            <div class="text-center">
                <span class="body-1 font-weight-bold">업데이트 하시겠습니까?</span>
            </div>
        </v-form-dialog>
    </div>
</template>

<script>
    import {EventBus} from '../event_bus'
    import VFormDialog from '../layouts/FormDialog'

    export default {
        components: {
            VFormDialog
        },
        props: {
            drawer: {
                type: Boolean,
                default: null
            },
            items: {
                type: Array,
                default() {
                    return []
                }
            }
        },
        data: () => ({
            dialog: false,
            result: {
                value: null,
                message: ''
            },
            origin_items: []
        }),
        computed: {
            editing() {
                return this.$store.getters.editing
            }
        },
        methods: {
            toggleDrawer: function () {
                this.$emit('update:drawer', !this.drawer)
            },
            setEditMenu() {
                this.origin_items = JSON.parse(JSON.stringify(this.items))
                this.$store.commit('setEditing', true)
            },
            unsetEditMenu() {
                this.$emit('update:items', this.origin_items)
                this.origin_items = []
                this.$store.commit('setEditing', false)
            },
            updateMenu() {
                this.dialog = true
            },
            updateGroup() {
                this.$store.commit('setLoading', true)

                this.$axios
                    .patch(APP_URL, {
                        groups: this.items
                    })
                    .then(response => {
                        this.result.message = '성공적으로 업데이트되었습니다.'
                        this.result.value = true
                    })
                    .catch(error => {
                        this.result.message = error.response.data.message
                        this.result.value = false
                    })
                    .finally(() => {
                        this.$store.commit('setLoading', false)
                    })
            },
            closedUpdateGroup() {
                if (this.result.value) {
                    this.$store.commit('setEditing', false)
                }
            }
        },
        mounted() {
            EventBus.$on('created-group', (group) => {
                this.$set(this.origin_items, this.origin_items.length, group)
            })
        }
    }
</script>
