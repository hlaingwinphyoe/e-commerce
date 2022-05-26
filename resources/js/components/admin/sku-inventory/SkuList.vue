<template>
    <tbody>
        <template v-for="sku in skus" >
            <sku-item :key="sku.id"
                :sku="sku"
                :inventory="inventory"
                @on-delete-sku="onDeleteSku"
            ></sku-item>
        </template>
    </tbody>
</template>

<script>
import SkuItem from "./SkuItem.vue";

export default {
    props: {
        skus: { required: true },
        inventory: { required: true }
    },
    components: {
        'sku-item' : SkuItem
    },
    methods:{
        onDeleteSku(data){
              this.$emit("on-delete-sku", data);
        }
    }
};
</script>

<style></style>
