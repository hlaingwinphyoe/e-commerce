<template>
    <div class="stock-add-container me-2">

        <div class="modal fade" :id="`add-stock-modal-${item.id}`">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ item.name }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <sku-list v-if="data_skus.length" :skus="data_skus" :inventory="data_inventory"></sku-list>
                    </div>
                    <div class="modal-footer flex-column justify-content-start align-items-start">
                        <h6>Supplier Information</h6>
                        <div class="d-flex">
                            <div class="form-group me-2">
                                <select class="form-select form-select-sm" v-model="supplier_form.supplier">
                                    <option value="">Choose Supplier</option>
                                    <option v-for="supplier in suppliers" :key="supplier.id" :value="supplier.id">{{ supplier.name }}</option>
                                </select>
                            </div>
                            <div class="form-group me-2">
                                <input type="date" class="form-control form-control-sm" v-model="supplier_form.date">
                            </div>
                            <div class="form-group">
                                <button type="button" class="btn btn-sm btn-primary" @click.prevent="onSaveSupplier"><i class="fa fa-save me-2"></i> Save</button>
                            </div>
                        </div>
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
        item: {required: true},
        suppliers: {required: true, default: ()=>[]}
    },
    data() {
        return {
            data_skus: [],
            data_inventory: '',
            supplier_form: {
                'supplier' : '',
                'date' : ''
            }
        }
    },
    mounted() {
        axios.get(`/wapi/item-skus/${this.item.id}`).then(resp=> {
            //console.log(resp.data)
            this.data_skus = resp.data ? resp.data : [];
        });
        axios.get(`/wapi/inventories/create`).then(resp => {
            this.data_inventory = resp.data ? resp.data : '';
            this.supplier_form.supplier = resp.data.supplier_id;
            this.supplier_form.date = resp.data.date;
        });
    },
    methods: {
        onSaveSupplier() {
            axios.patch(`/wapi/inventories/${this.data_inventory.id}`, this.supplier_form).then(resp => {
                location.reload();
            });
        }
    }
}
</script>
