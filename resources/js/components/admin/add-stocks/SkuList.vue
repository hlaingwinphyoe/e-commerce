<template>
    <div class="sku-list-container">
        <sku-item v-for="sku in skus" :key="sku.id" :sku="sku" :inventory="inventory"></sku-item>
    </div>
</template>

<script>
import SkuItem from './SkuItem.vue';
export default {
    components: {
        'sku-item' : SkuItem,
    },
    props: {
        skus: {required: true},
        inventory: {required: true}
    }
}
</script>