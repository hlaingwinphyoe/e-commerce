<template>
    <div class="sku-item-container py-2 px-3 mb-2 rounded border">
        <div class="mb-2">

            <div class="row g-3 align-items-center">
                <div class="col-md-3">
                    <p class="mb-1 fw-bold">{{ sku.item_name }} {{ sku.data ? '('+ sku.data +')' : '' }}</p>
                    <small class="text-muted">current stock - <span class="btn btn-sm btn-danger">{{ form.current_stock }}</span></small>
                </div>
                <div class="col-md-3">
                    <label for="qty" class="">Qty</label>
                    <input type="number" class="form-control" id="qty" placeholder="Qty" v-model="form.qty" @change="onChangeQty">
                </div>
                <div class="col-md-3">
                    <label for="amount" class="">Amount</label>
                    <input type="number" class="form-control" id="amount" placeholder="amount" v-model="form.amount">
                </div>
                <div class="col-md-3">
                    <div class="mt-3">
                        <!-- <a href="#" @click.prevent="onChangeQty" class="btn btn-primary me-1"><i class="fa-solid fa-check"></i></a> -->
                        <a :href="`/admin/skus/${sku.id}`" class="btn btn-outline-primary">Stock Details</a>
                    </div> 
                </div>
            </div>

            <!-- <div class="row">
                <div class="col-md-6">
                    <p class="mb-1 fw-bold">{{ sku.item_name }} {{ sku.data ? '('+ sku.data +')' : '' }}</p>
                    <small class="text-muted">current stock - <span class="btn btn-sm btn-danger">{{ form.current_stock }}</span></small>
                </div>
                <div class="col-md-2">
                    <div>
                        <label class="text-muted">Qty</label>
                        <input type="text" class="form-control form-control-sm qty-input" placeholder="Qty" v-model="form.qty" @change="onChangeQty">
                    </div>
                </div>
                <div class="col-md-2">
                    <div>
                        <label class="text-muted">Buy Price</label>
                        <input type="text" class="form-control form-control-sm qty-input" placeholder="Amount" v-model="form.amount" @change="onChangeQty">
                    </div>
                </div>
                <div class="col-md-2 align-self-end">
                    <a :href="`/admin/skus/${sku.id}`" class="btn btn-sm btn-outline-dark">Stock Details</a>
                </div>
            </div> -->
            
        </div>
    </div>
</template>

<script>
export default {
    props: {
        sku: {required: true},
        inventory: {required: true}
    },
    data() {
        return {
            form : {
                'qty' : 0,
                'amount' : this.sku.price,
                'current_stock' : this.sku.stock,
                'sku' : this.sku.id
            }
        }
    },
    methods: {
        onChangeQty() {
            axios.post(`/wapi/sku-inventories/${this.inventory.id}`, this.form).then(resp => {
                this.form.current_stock = resp.data && resp.data.sku ? resp.data.sku.stock : this.sku.stock;
            });
        }
    }
}
</script>