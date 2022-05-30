<template>
    <tbody class="">
        <sku-item
            v-for="sku in skus"
            :key="sku.id"
            :sku="sku"
            @on-update-sku="onUpdateSku"
            @on-delete-sku="onDeleteSku"
        ></sku-item>
    </tbody>
</template>

<script>
import SkuItem from "./SkuItem.vue";
export default {
    components: {
        "sku-item": SkuItem,
    },
    props: {
        skus: { required: true, default: () => [] },
    },
    methods: {
        onUpdateSku(data) {
            this.$emit('on-update-sku', data);
        },
        onDeleteSku(data) {
            this.$emit('on-delete-sku', data);
        }
    }
};
</script>
