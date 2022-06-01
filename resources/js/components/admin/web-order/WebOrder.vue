<template>
    <div class="web-order-index">
        <p>Weborder</p>
        <!-- Order Lists -->
        <div class="table-responsive">
            <table class="table">
                <thead></thead>
                <tbody>
                    <sku-item v-for="sku in data_skus" :key="sku.id" :sku="sku"></sku-item>
                </tbody>
            </table>
        </div>
        
    </div>
</template>

<script>
import SkuItem from './SkuItem.vue';
export default {
    components: {
        'sku-item' : SkuItem
    },
    props: {
        order: {required: true},
        skus: {required: true}
    },
    data() {
        return {
            data_order : this.order,
            data_skus: this.skus,
        }
    }
}
</script>