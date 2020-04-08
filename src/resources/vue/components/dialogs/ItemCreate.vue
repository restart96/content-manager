<template>
    <v-form-dialog
        v-model="dialog"
        :result="result"
        color="success"
        max-width="600px"
        @submit="submit"
        @closed="closed"
    >
        <template v-slot:title>
            <span class="headline grey--text text--darken-2">{{ title }}</span>
            <v-spacer></v-spacer>
            <v-btn icon @click="dialog = false">
                <v-icon>mdi-close</v-icon>
            </v-btn>
        </template>

        <template v-slot:actions>
            <v-btn type="submit" color="success">확인</v-btn>
            <v-btn v-if="!!content.id" color="error" @click="deleteItem">삭제</v-btn>
        </template>

        <v-row>
            <v-col cols="12">
                <v-checkbox
                    v-model="content.enable"
                    label="공개여부"
                    color="primary"
                    true-value="Y"
                    false-value="N"
                    hide-details
                ></v-checkbox>
            </v-col>
            <v-col cols="12">
                <v-text-field
                    v-model="content.name"
                    label="콘텐츠명"
                    hide-details
                ></v-text-field>
            </v-col>
            <v-col cols="12">
                <v-textarea
                    v-model="content.description"
                    label="요약정보"
                    rows="3"
                    auto-grow
                    no-resize
                    hide-details
                ></v-textarea>
            </v-col>
            <v-col cols="12" class="d-flex">
                <v-row>
                    <v-col cols="12" sm="6" class="py-0">
                        <v-datetime-picker
                            :date.sync="content.start_date"
                            :time.sync="content.start_time"
                            :use-time="time"
                            label="시작일"
                        ></v-datetime-picker>
                    </v-col>
                    <v-col cols="12" sm="6" class="py-0">
                        <v-datetime-picker
                            :date.sync="content.end_date"
                            :time.sync="content.end_time"
                            :use-time="time"
                            label="종료일"
                        ></v-datetime-picker>
                    </v-col>
                </v-row>
                <v-btn class="btn-time" text dense color="primary" @click="toggleTime">{{ timeText }}</v-btn>
            </v-col>
            <v-col cols="12">
                <v-item-images :items.sync="content.images"></v-item-images>
            </v-col>
            <v-col cols="12">
                <v-card outlined>
                    <v-card-title class="pb-0">
                        <span class="subtitle-1 font-weight-medium grey--text text--darken-1">링크</span>
                        <v-spacer></v-spacer>
                        <v-btn :disabled="!isAvailableAddLink" text color="primary" @click="addLink">추가</v-btn>
                    </v-card-title>
                    <v-card-text class="mt-4">
                        <v-item-links :items.sync="content.links"></v-item-links>
                    </v-card-text>
                </v-card>
            </v-col>
        </v-row>
    </v-form-dialog>
</template>

<script>
    import {EventBus} from '../../event_bus'
    import VFormDialog from '../../layouts/FormDialog'
    import VDatetimePicker from '../../layouts/DatetimePicker'
    import VItemImages from '../ItemImages'
    import VItemLinks from '../ItemLinks'

    export default {
        components: {
            VFormDialog,
            VDatetimePicker,
            VItemImages,
            VItemLinks
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
            group: {
                type: Object
            },
            category: {
                type: Object
            },
            item: {
                type: Object
            }
        },
        data: () => ({
            result: {
                value: null,
                message: '',
                response: null,
                action: null
            },
            content: {},
            time: false,
            imageDetail: false
        }),
        computed: {
            title() {
                return this.item && this.item.id ? '콘텐츠 수정' : '콘텐츠 등록'
            },
            timeText() {
                return this.time ? '시간 해제' : '시간 설정'
            },
            isAvailableAddLink() {
                return !this.content.links || this.content.links.length < 4
            },
            dialog: {
                get() {
                    return this.value
                },
                set(val) {
                    this.$emit('update:value', val)
                }
            }
        },
        watch: {
            item: {
                handler(val) {
                    this.resetForm()
                },
                deep: true,
                immediate: true
            }
        },
        methods: {
            resetForm() {
                this.content = Object.assign({}, this.item)
                this.content.enable = this.content.enable === 'Y' ? 'Y' : 'N'
                this.time = !!this.content.start_time || !!this.content.end_time
                if (!this.content.images) {
                    this.$set(this.content, 'images', [])
                    this.addImage('xs')
                    this.addImage('md')
                }
                if (!this.content.links) {
                    this.$set(this.content, 'links', [])
                    this.addLink()
                }
            },
            setFormData(form, data, previousKey) {
                if (!(data instanceof File) && (data instanceof Object || Array.isArray(data))) {
                    for (let key in data) {
                        const val = data[key]
                        if (val === null || val === undefined) {
                            val = ''
                        }

                        if (previousKey !== undefined) {
                            key = `${previousKey}[${key}]`
                        }

                        this.setFormData(form, val, key)
                    }
                } else if (previousKey !== undefined) {
                    if (data === null || data === undefined) {
                        data = ''
                    }
                    form.append(previousKey, data)
                }
            },
            toggleTime() {
                this.time = !this.time
                if (!this.time) {
                    this.$nextTick(() => {
                        this.content.start_time = null
                        this.content.end_time = null
                    })
                }
            },
            addImage(breakpoint) {
                this.$set(this.content.images, this.content.images.length, {breakpoint: breakpoint})
            },
            addLink() {
                this.$set(this.content.links, this.content.links.length, {})
            },
            submit() {
                this.$store.commit('setLoading', true)

                let url = APP_URL + '/' + this.group.code.toLowerCase() + '/' + this.category.code.toLowerCase() + '/items'

                let formData = new FormData()
                for (let key in this.content) {
                    if (key !== 'id') {
                        this.setFormData(formData, this.content[key], key)
                    }
                }

                let options = {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    },
                    responseType: 'json'
                }

                if (this.content.id) {
                    formData.append('_method', 'PATCH')
                    url = url + '/' + this.content.id
                }

                this.$axios
                    .post(url, formData, options)
                    .then(response => {
                        this.result.value = true
                        if (this.content.id) {
                            this.result.response = response.data.items
                            this.result.action = 'updated-item'
                            this.result.message = '콘텐츠가 정상적으로 업데이트되었습니다.'
                        } else {
                            this.result.response = response.data.items
                            this.result.action = 'created-item'
                            this.result.message = '콘텐츠가 정상적으로 등록되었습니다.'
                        }
                    })
                    .catch(error => {
                        this.result.message = error.response.data.message
                        this.result.value = false
                    })
                    .finally(() => {
                        this.$store.commit('setLoading', false)
                    })
            },
            deleteItem() {
                if (!confirm('이 콘텐츠를 삭제하시겠습니까?')) {
                    return
                }

                this.$store.commit('setLoading', true)

                let url = APP_URL + '/' + this.group.code.toLowerCase() + '/' + this.category.code.toLowerCase() + '/items/' + this.content.id
                this.$axios
                    .delete(url)
                    .then(response => {
                        this.result.value = true
                        this.result.response = response.data.items
                        this.result.action = 'deleted-item'
                        this.result.message = '콘텐츠를 삭제 완료했습니다.'
                    })
                    .catch(error => {
                        this.result.message = error.response.data.message
                        this.result.value = false
                    })
                    .finally(() => {
                        this.$store.commit('setLoading', false)
                    })
            },
            closed() {
                this.resetForm()
                if (this.result.value) {
                    EventBus.$emit(this.result.action, this.result.response)
                }
            }
        }
    }
</script>

<style scoped>
    .btn-time {align-self:center;}
</style>
