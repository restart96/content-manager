<template>
    <v-card outlined>
        <v-card-title class="pb-0">
            <span class="subtitle-1 font-weight-medium grey--text text--darken-1">이미지</span>
            <v-spacer></v-spacer>
            <v-btn text small color="primary" @click="detail = !detail">상세 설정</v-btn>
        </v-card-title>
        <v-card-text class="mt-1">
            <v-row dense v-if="items && items.length">
                <v-col>
                    <v-item-image
                        title="모든 해상도"
                        :item="items[0]"
                        :detail="detail"
                    ></v-item-image>
                </v-col>
                <v-col>
                    <v-item-image
                        title="태블릿 이상 해상도"
                        :item="items[1]"
                        :detail="detail"
                    ></v-item-image>
                </v-col>
            </v-row>
        </v-card-text>
    </v-card>
</template>

<script>
    import VItemImage from './ItemImage'

    export default {
        components: {
            VItemImage
        },
        props: {
            items: {
                type: Array
            }
        },
        data: () => ({
            detail: false
        })
    }
</script>
