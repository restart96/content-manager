<template>
    <v-card>
        <v-card-title class="pa-3 pb-0">
            <span class="subtitle-2 font-weight-medium grey--text text--darken-1">{{ title }}</span>
        </v-card-title>
        <v-card-text class="pa-3">
            <v-row dense>
                <v-col cols="12" class="pt-0">
                    <div class="image" @click="clickImage">
                        <p class="placeholder subtitle-2 grey--text text--darken-1">이미지를 선택해 주세요</p>
                        <img v-if="!!item.file" :src="file"/>
                        <img v-else-if="item.url" :src="item.url"/>
                    </div>
                </v-col>
                <v-col cols="12">
                    <v-file-input
                        ref="file"
                        v-model="item.file"
                        label="이미지 검색"
                        accept="image/*"
                        prepend-icon=""
                        hide-details
                        @change="changeFile"
                    ></v-file-input>
                </v-col>
                <v-col cols="12" v-if="detail">
                    <v-text-field
                        v-model="item.class"
                        label="클래스"
                        placeholder="sample-image"
                        hide-details
                    ></v-text-field>
                </v-col>
                <v-col cols="12" v-if="detail">
                    <v-text-field
                        v-model="item.style"
                        label="스타일"
                        placeholder="background:#4caf50"
                        hide-details
                    ></v-text-field>
                </v-col>
            </v-row>
        </v-card-text>
    </v-card>
</template>

<script>
    export default {
        props: {
            title: {
                type: String
            },
            item: {
                type: Object
            },
            detail: {
                type: Boolean
            }
        },
        data: () => ({
            file: null
        }),
        methods: {
            changeFile(file) {
                if (file) {
                    const reader = new FileReader
                    reader.onload = e => {
                        this.file = e.target.result
                    }
                    reader.readAsDataURL(file)
                }
            },
            clickImage() {
                this.$refs.file.$el.querySelector('input[type="file"]').click()
            }
        }
    }
</script>

<style scoped>
    .image {position:relative;padding-bottom:56.25%;cursor:pointer;border:1px solid #eee;border-radius:4px;overflow:hidden;}
    .image >>> .placeholder {position:absolute;top:50%;left:0;width:100%;margin:0;padding:0;text-align:center;transform:translateY(-50%);}
    .image >>> img {position:absolute;top:0;left:0;width:100%;height:100%;object-fit:cover;}
</style>
