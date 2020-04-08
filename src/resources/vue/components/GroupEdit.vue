<template>
    <v-form ref="form" lazy-validation @submit.stop.prevent="storeGroup">
        <v-card class="elevation-2">
            <v-card-text>
                <v-row>
                    <v-col v-if="!!error" cols="12">
                        <v-alert outlined type="warning" class="ma-0">
                            {{ error }}
                        </v-alert>
                    </v-col>
                    <v-col cols="12">
                        <v-card outlined>
                            <v-card-title class="pb-0">
                                <span class="subtitle-1 font-weight-medium grey--text text--darken-1">메뉴명</span>
                            </v-card-title>
                            <v-card-text>
                                <v-text-field
                                    counter="40"
                                    v-model="group.name"
                                    :rules="[
                                        v => !!v || '메뉴명을 입력해 주세요',
                                        v => v === '' || v === undefined || v.length <= 40 || '최대 40자까지 입력 가능합니다.'
                                    ]"
                                    required
                                    persistent-hint
                                    single-line
                                >
                                </v-text-field>
                            </v-card-text>
                        </v-card>
                    </v-col>
                    <v-col cols="12">
                        <v-card outlined>
                            <v-card-title class="pb-0">
                                <span class="subtitle-1 font-weight-medium grey--text text--darken-1">코드</span>
                            </v-card-title>
                            <v-card-text>
                                <v-text-field
                                    class="code-name"
                                    hint="* 영문과 숫자, - 만 입력 가능합니다"
                                    counter="64"
                                    v-model="group.code"
                                    :rules="[
                                        v => !!v || '코드를 입력해 주세요',
                                        v => v === '' || v === undefined || /^[A-Za-z0-9\-]+$/.test(v) || '영문과 숫자, - 만 입력 가능합니다',
                                        v => v === '' || v === undefined || v.length <= 64 || '최대 64자까지 입력 가능합니다.',
                                        validateCode
                                    ]"
                                    required
                                    persistent-hint
                                    single-line
                                >
                                </v-text-field>
                            </v-card-text>
                        </v-card>
                    </v-col>
                    <v-col cols="12">
                        <v-card outlined>
                            <v-card-title class="pb-0">
                                <span class="subtitle-1 font-weight-medium grey--text text--darken-1">카테고리</span>
                                <v-spacer></v-spacer>
                                <v-btn text color="primary" @click="addCategory">추가</v-btn>
                            </v-card-title>
                            <v-card-text class="mt-4">
                                <v-category-edit :items.sync="group.categories"></v-category-edit>
                            </v-card-text>
                        </v-card>
                    </v-col>
                </v-row>
            </v-card-text>
            <v-card-actions v-if="!group.id" class="pa-4 text-center">
                <v-btn block type="submit" color="success">생성</v-btn>
            </v-card-actions>
        </v-card>
    </v-form>
</template>

<script>
    import {EventBus} from '../event_bus'
    import VCategoryEdit from './CategoryEdit'

    export default {
        components: {
            VCategoryEdit
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
            }
        },
        data: () => ({
            error: null
        }),
        watch: {
            group(val) {
                this.validate()
            }
        },
        methods: {
            validate() {
                if (!this.group.id) {
                    this.$refs.form.resetValidation()
                }
            },
            storeGroup() {
                if (!this.$refs.form.validate()) {
                    return false
                }

                this.$store.commit('setLoading', true)

                this.$axios
                    .post(APP_URL, {
                        group: this.group
                    })
                    .then(response => {
                        EventBus.$emit('created-group', response.data.group)
                        this.$emit('update:group', null)
                    })
                    .catch(error => {
                        if (error.response && error.response.data && error.response.data.message) {
                            this.error = error.response.data.message
                        } else {
                            this.error = '생성에 실패했습니다.'
                        }
                    })
                    .finally(() => {
                        this.$store.commit('setLoading', false)
                    })
            },
            validateCode(val) {
                if (val === '' || val === undefined) {
                    return true
                }

                try {
                    return this.isValidCode(this.items, val)
                } catch (e) {
                    return e.message
                }
            },
            isValidCode(items, val) {
                let regexp = new RegExp('^' + val.toUpperCase() + '$', 'i')

                for (let item of items) {
                    if (this.group.id !== item.id && item.code.match(regexp)) {
                        throw new Error('동일한 코드가 존재합니다') 
                    }
                    if (item.children) {
                        this.isValidCode(item.children, val)
                    }
                }

                return true
            },
            addCategory() {
                if (!this.group.categories) {
                    this.$set(this.group, 'categories', [])
                }
                this.$set(this.group.categories, this.group.categories.length, {})
            }
        }
    }
</script>

<style scoped>
    .code-name >>> input {text-transform:uppercase;}
</style>
