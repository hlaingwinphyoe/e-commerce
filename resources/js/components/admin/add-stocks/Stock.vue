<template>
    <div class="stock-add-container me-2">
        
        <div class="modal" :id="`add-stock-modal-${item.id}`">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ item.name }}</h5>
                        <button type="button" class="btn-close bg-danger" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <sku-list v-if="data_skus.length" :skus="data_skus"></sku-list>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary">Save changes</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import SkuList from './SkuList.vue';
export default {
    components: {
        'sku-list' : SkuList
    },
    props: {
        item: {required: true}
    },
    data() {
        return {
            data_skus: []
        }
    },
    mounted() {
        axios.get(`/wapi/item-skus/${this.item.id}`).then(resp=> {
            this.data_skus = resp.data ? resp.data : [];
        });
    }   
}
</script>