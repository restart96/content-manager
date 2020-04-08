<template>
    <v-card class="link">
        <v-card-text class="pa-3">
            <v-row dense>
                <v-col>
                    <v-row dense>
                        <v-col class="device">
                            <v-select
                                :readonly="isDefault"
                                v-model="item.device"
                                :items="devices"
                                :item-disabled="v => isDefault ? '' : v.default"
                                :rules="[v => v !== null && v !== undefined]"
                                label="디바이스"
                                hide-details
                            ></v-select>
                        </v-col>
                        <v-col class="target">
                            <v-select
                                v-model="item.target"
                                :items="targets"
                                label="새창"
                                hide-details
                            ></v-select>
                        </v-col>
                        <v-col cols="12" class="url">
                            <v-text-field
                                v-model="item.url"
                                label="링크"
                                :placeholder="defaultUrl"
                                hide-details
                            ></v-text-field>
                        </v-col>
                    </v-row>
                </v-col>
                <v-col class="action d-flex align-center">
                    <v-btn
                        :class="isDefault ? 'hidden' : ''"
                        icon
                        color="error"
                        @click="deleteItem"
                    >
                        <v-icon>mdi-delete</v-icon>
                    </v-btn>
                </v-col>
            </v-row>
        </v-card-text>
    </v-card>
</template>

<script>
    export default {
        props: {
            item: {
                type: Object
            },
            isDefault: {
                type: Boolean,
                default: false
            }
        },
        data: () => ({
            devices: [
                {value: '', text: 'Default', default: true},
                {value: 'mobile', text: 'Mobile'},
                {value: 'tablet', text: 'Tablet'},
                {value: 'desktop', text: 'Desktop'},
            ],
            targets: [
                {value: '', text: '일반'},
                {value: '_blank', text: '새창'}
            ]
        }),
        computed: {
            defaultUrl() {
                return window.location.origin
            }
        },
        watch: {
            item: {
                handler(val) {
                    if (this.isDefault && !val.device) {
                        val.device = ''
                    }
                    if (!val.target) {
                        val.target = ''
                    }
                },
                deep: true,
                immediate: true
            }
        },
        methods: {
            deleteItem() {
                this.$emit('delete', this.item)
            }
        }
    }
</script>

<style scoped>
    .link >>> .action {flex:0;}
    .link >>> .action .hidden {visibility:hidden;}
    @media screen and (min-width: 600px) {
        .link >>> .device {flex:0 0 102px;}
        .link >>> .target {flex:0 0 78px;}
        .link >>> .url {flex:1 0;}
        .link >>> .action .hidden {display:none;}
    }
</style>
