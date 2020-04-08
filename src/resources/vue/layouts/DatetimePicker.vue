<template>
    <div class="d-flex">
        <v-dialog
            v-model="dialog.date"
            max-width="290px"
        >
            <template v-slot:activator="{ on }">
                <v-text-field
                    :value="dateFormat(computedDate)"
                    v-on="on"
                    class="date-text-field"
                    :label="label"
                    readonly
                    hide-details
                ></v-text-field>
            </template>
            <v-date-picker
                v-if="dialog.date"
                v-model="computedDate"
                :day-format="dayFormat"
                locale="ko-KR"
                @input="dialog.date = false"
            >
                <v-spacer></v-spacer>
                <v-btn text color="primary" @click="resetPicker('date')">초기화</v-btn>
            </v-date-picker>
        </v-dialog>
        <v-dialog
            v-if="useTime"
            v-model="dialog.time"
            max-width="290px"
        >
            <template v-slot:activator="{ on }">
                <v-text-field
                    v-model="computedTime"
                    v-on="on"
                    class="time-text-field"
                    label="시간"
                    readonly
                    hide-details
                ></v-text-field>
            </template>
            <v-time-picker
                v-if="dialog.time"
                v-model="computedTime"
                :allowed-minutes="v => v % 10 === 0"
                format="24hr"
                @click:minute="dialog.time = false"
            >
                <v-spacer></v-spacer>
                <v-btn text color="primary" @click="resetPicker('time')">초기화</v-btn>
            </v-time-picker>
        </v-dialog>
    </div>
</template>

<script>
    export default {
        props: {
            date: {
                type: String,
                default: null
            },
            time: {
                type: String,
                default: null
            },
            label: {
                type: String,
                default: null
            },
            useTime: {
                type: Boolean,
                default: false
            }
        },
        data: () => ({
            dialog: {
                date: false,
                time: false
            }
        }),
        computed: {
            computedDate: {
                get() {
                    return this.date
                },
                set(val) {
                    this.$emit('update:date', val)
                }
            },
            computedTime: {
                get() {
                    if (this.time !== null && this.time !== undefined) {
                        let time = this.time.split(':').concat(Array(2).fill('00')).slice(0, 2)
                        return time.join(':')
                    }
                    return this.time
                },
                set(val) {
                    this.$emit('update:time', val)
                }
            }
        },
        methods: {
            dateFormat(val) {
                return val ? val.replace(/-/g, '.') : val
            },
            dayFormat(val) {
                return Number(val.split(/-/).pop())
            },
            resetPicker(prop) {
                this.$emit('update:' + prop, null)
                this.dialog[prop] = false
            }
        }
    }
</script>

<style scoped>
    .date-text-field >>> input {width:96px;}
</style>
