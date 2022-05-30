<template>
    <div class="return-sku-container">
        <div class="row">
            <div class="col-md-6">
                <!-- Return Form -->
                <div class="bg-white shadow rounded py-3 px-2 mb-4">
                    <h5 class="text-primary mb-3">Return Form</h5>

                    <div class="d-flex">
                        <div class="form-group me-2 w-sm-100">
                            <label for="">Order</label>
                            <input type="text" placeholder="Order" class="form-control form-control-sm disabled" v-model="return_form.order_id">
                        </div>

                        <div class="form-group me-2 w-sm-100">
                            <label for="">Date</label>
                            <input type="date" placeholder="Date" class="form-control form-control-sm" v-model="return_form.date">
                        </div>

                        <div class="form-group me-2 w-sm-100 align-self-end">
                            <button type="button" class="btn btn-sm btn-primary" @click.prevent="onUpdateReturn">
                                <small class="me-2"><i class="fas fa-save"></i></small>
                                <span>Update</span>
                            </button>
                        </div>
                    </div>
                </div>


                <!-- Order Lists -->
                <div class="bg-white shadow rounded py-3 px-2 mb-4">
                    <h5 class="text-primary mb-4">Ordered Lists</h5>

                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th width="100px">Qty</th>
                                    <th width="100px">Amount</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <order-sku v-for="order_sku in data_order_skus" :key="order_sku.id" :order_sku="order_sku" :p_return="p_return" @on-add-return-sku="onAddReturnSku"></order-sku>
                            </tbody>
                        </table>
                    </div>                    
                </div>
            </div>

            <!-- Return Sku List -->
            <div class="col-md-6">
                <div class="bg-white shadow rounded py-3 px-2 mb-4">
                    <h5 class="text-primary mb-4">Return Lists</h5>

                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th width="100px">Qty</th>
                                    <th width="100px">Amount</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <sku-item v-for="sku in data_skus" :key="sku.id" :sku="sku" :p_return="p_return" @on-update-sku="onUpdateSku" @on-delete-sku="onDeleteSku"></sku-item>
                            </tbody>
                        </table>
                    </div>                    
                </div>
            </div>
        </div>    
        
    </div>
</template>

<script>
import OrderSku from './OrderSku.vue';
import SkuItem from './SkuItem.vue';
export default {
    components: {
        'order-sku' : OrderSku,
        'sku-item' : SkuItem
    },
    props: {
        order_skus: {required: true},
        skus: {required: true},
        p_return: {required: true}
    },
    data() {
        return {
            data_order_skus: this.order_skus,
            data_skus: this.skus,
            return_form : {
                date: this.p_return.date,
                order_id: this.p_return.order_id,
            }
        }
    },
    mounted() {
        this.updateOrderSku();
    },
    methods: {
        onUpdateReturn() {
            axios.patch(`/wapi/returns/${this.p_return.id}`, this.return_form).then(resp => {
                //
            });
        },
        onAddReturnSku(data) {
            this.data_skus = data.skus;
            this.updateOrderSku();
        },
        onUpdateSku(data) {
            //
        },
        onDeleteSku(data) {
            this.data_skus = data.skus;
            this.updateOrderSku();
        },
        updateOrderSku() {
            this.data_order_skus = this.order_skus.filter(s => {
                let sku = this.data_skus.some(d => d.id === s.id);
                return sku ? '' : s;
            });
        }
    }
}
</script>