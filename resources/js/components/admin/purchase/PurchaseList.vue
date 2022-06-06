<template>
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th width="100px">Qty</th>
                    <th width="100px">Buy Price</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <purchase-item v-for="sku in skus" :key="sku.id" :sku="sku" @on-update-sku="onUpdateSku" @on-delete-sku="onDeleteSku"></purchase-item>
            </tbody>
        </table>
    </div>    
</template>

<script>
import PurchaseItem from './PurchaseItem.vue';
export default {
    components: {
        'purchase-item' : PurchaseItem
    },
    props: {
        skus: {required: true, default: ()=>[]}
    },
    data() {
        return {
            //
        }
    },
    methods: {
        onUpdateSku(data){
            this.$emit('on-update-sku', data);
            // this.data_skus = data.skus;
        },
        onDeleteSku(data) {
            this.$emit('on-delete-sku', data);
            // this.data_skus = data.skus;
        }
    }
}
</script>