<template>
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th width="100px">Qty</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <general-item v-for="sku in skus" :key="sku.id" :sku="sku" :inventory="inventory" @on-update-sku="onUpdateSku" @on-delete-sku="onDeleteSku"></general-item>
            </tbody>
        </table>
    </div>
</template>

<script>
import GeneralItem from './GeneralItem.vue';
export default {
    components: {
        'general-item' : GeneralItem
    },
    props: {
        skus: {required: true, default: ()=>[]},
        inventory: { required: true, default: () => []}
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
