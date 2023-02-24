<template>
    <div class="bg-white shadow rounded px-3 py-4 h-100">
                <div class="d-flex align-items-center mb-2">
                    <h5 class="me-2 mb-0">Sku Item Lists</h5>
                    <span class="text-primary">(Total - {{ skus.length }})</span>
                </div>
                
                <div class="table-responsive smooth-scroll" style="max-height: 800px">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th><i class="fa fa-image"></i></th>
                                <template v-if="item.sku_control == 'clothing'">
                                <th>Color</th>
                                <th>Size</th>
                                </template>
                                <template v-else>
                                <th>Name</th>
                                </template>
                                <th>Price</th>
                                <template v-if="item.is_stock_control">
                                <th>Stock</th>
                                <th><i class="fa fa-plus"></i></th>
                                </template>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <sku-item v-for="(sku, index) in skus" :key="sku.id" :item="item" :sku="sku" :index="index" @on-delete-sku="onDeleteSku"></sku-item>
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
        skus: {required: true},
        item: {required: true},
    },
    methods: {
        onDeleteSku(data) {
            this.$emit('on-delete-sku', data);
        }
    }
}
</script>