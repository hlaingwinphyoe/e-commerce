<template>
    <div class="web-order-index">
        <div class="row">
            <div class="col-md-6 order-md-last">
                <customer :order="data_order"></customer>
            </div>
            <div class="col-md-6">                
                <!-- Order Lists -->
                <div class="bg-white border rounded mb-4 px-2 py-3">
                    <h6 class="text-primary">Item Lists</h6>
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th v-if="data_order.status_id ==1">Stock</th>
                                    <th>Qty</th>
                                    <th class="text-end">Total</th>
                                    <th class="text-end"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <sku-item v-for="sku in data_skus" :key="sku.id" :sku="sku" :order="data_order" @on-update-sku="onUpdateSku" @on-delete-sku="onDeleteSku"></sku-item>
                            </tbody>
                           <thead>
                            <tr>
                                <th :colspan="data_order.status_id ==1 ? 3 : 2" class="text-end">Total</th>
                                <th class="text-end">{{ data_order.price }}</th>
                                <th></th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
                <!-- Add Items -->
                <add-item v-if="order.status_id == 1 || order.status_id != 3" :order="order" @on-add-sku="onAddSku"></add-item>
                <!-- confirm -->
                <div class="mb-4">
                    <button v-if="order.status_id ==1" class="btn btn-sm btn-primary me-2" @click.prevent="onConfirmOrder">Confirm Order</button>
                    <a href="#" data-toggle="modal" class="btn btn-sm btn-secondary" @click.prevent="modal.show()">Make Payment</a>
                    <payment-form :order="data_order"></payment-form>
                </div>
            </div>
        </div>        
    </div>
</template>

<script>
import Customer from './Customer.vue';
import SkuItem from './SkuItem.vue';
import AddItem from './AddItem.vue';
export default {
    components: {
        'customer' : Customer,
        'sku-item' : SkuItem,
        'add-item' : AddItem,
    },
    props: {
        order: {required: true},
        skus: {required: true}
    },
    data() {
        return {
            data_order : this.order,
            data_skus: this.skus,
            modal : ''
        }
    },
    mounted() {
        this.modal = new bootstrap.Modal(`#payment-modal-${this.order.id}`);
    },
    methods: {
        onAddSku(data) {
            this.data_skus = data;
        },
        onUpdateSku(data) {
            this.data_skus = data;
        },
        onDeleteSku(data) {
            this.data_skus = data;
        },
        onConfirmOrder() {
            axios.post(`/wapi/orders-confirm/${this.order.id}`).then(resp => {
                this.data_order = resp.data;
                this.modal.show();                
            })
        },
        onOpenModal() {
            this.modal.show();
        }
    }
}
</script>