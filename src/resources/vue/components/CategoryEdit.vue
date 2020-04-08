<template>
    <v-data-table
        :headers="headers"
        :items="items"
        :mobile-breakpoint="0"
        disable-sort
        disable-pagination
        hide-default-footer
        dense
    >
        <template v-slot:body="{ items }">
            <template v-if="items && items.length">
                <v-draggable
                    :value="items"
                    animation="150"
                    tag="tbody"
                    handle=".handle"
                    @input="sortItems"
                >
                    <tr v-for="(item, index) in items" :key="index">
                        <td class="pa-2 text-center">
                            <v-icon class="handle" @click="">mdi-reorder-horizontal</v-icon>
                        </td>
                        <td class="pa-2 text-center">
                            <v-text-field
                                class="subtitle-2 font-weight-regular"
                                placeholder="카테고리명"
                                v-model="item.name"
                                :rules="[
                                    v => v === '' || v === undefined || v.length <= 40 || '최대 40자까지 입력 가능합니다',
                                ]"
                                required
                                single-line
                                hide-details
                                outlined
                                dense
                            />
                        </td>
                        <td class="pa-2 text-center">
                            <v-text-field
                                class="subtitle-2 font-weight-regular code-name"
                                placeholder="코드"
                                v-model="item.code"
                                :rules="[
                                    v => v === '' || v === undefined || /^[A-Za-z0-9\-]+$/.test(v) || '영문과 숫자, - 만 입력 가능합니다',
                                    v => v === '' || v === undefined || v.length <= 64 || '최대 64자까지 입력 가능합니다',
                                    validateCode(item.code),
                                ]"
                                required
                                single-line
                                hide-details
                                outlined
                                dense
                            />
                        </td>
                        <td class="pa-2 text-center">
                            <v-btn icon color="error" @click="deleteItem(index)">
                                <v-icon>mdi-delete</v-icon>
                            </v-btn>
                        </td>
                    </tr>
                </v-draggable>
            </template>
            <template v-else>
                <tr class="v-data-table__empty-wrapper">
                    <td :colspan="headers.length">
                        <div class="text-xs-center my-12">
                            설정된 카테고리가 없습니다.
                        </div>
                    </td>
                </tr>
            </template>
        </template>
    </v-data-table>
</template>

<script>
    import VDraggable from 'vuedraggable'

    export default {
        components: {
            VDraggable
        },
        props: {
            items: {
                type: Array,
                default: () => {
                    return []
                }
            }
        },
        data: () => ({
            headers: [
                {text: 'SORT', align: 'center', width: '70px'},
                {text: 'NAME', align: 'center', width: '50%'},
                {text: 'CODE', align: 'center', width: '50%'},
                {text: 'ACTIONS', align: 'center', width: '90px'}
            ]
        }),
        methods: {
            sortItems(val) {
                this.$emit('update:items', val)
            },
            deleteItem(index) {
                this.items.splice(index, 1)
            },
            validateCode(val) {
                if (val === '' || val === undefined) {
                    return true
                }

                let regexp = new RegExp('^' + val.toUpperCase() + '$', 'i')
                let count = 0
                for (let item of this.items) {
                    if (item.code && item.code.match(regexp)) {
                        count++
                    }
                }

                if (count > 1) {
                    return '동일한 코드가 존재합니다'
                }
                return true
            }
        }
    }
</script>

<style scoped>
    .code-name >>> input {text-transform:uppercase;}
    .v-data-table >>> table {table-layout:fixed;}
    .v-data-table >>> tbody tr:hover:not(.v-data-table__expanded__content) {background:#f6f6f6;}
    .v-data-table >>> tbody tr.sortable-chosen {background:#eee !important;}
</style>
