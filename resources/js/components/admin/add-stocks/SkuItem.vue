<template>
    <div class="sku-item-container py-2 px-3 mb-2 rounded border">
        <div class="mb-2">
            <div class="row">
                <div class="col-md-3">
                    <p class="mb-1">{{ sku.data }}</p>
                    <small>current stock - <span class="btn btn-sm btn-outline-primary">{{ form.current_stock }}</span></small>
                </div>
                <div class="col-md-2">
                    <div>
                        <label>Qty</label>
                        <input type="text" class="form-control form-control-sm qty-input" placeholder="Qty" v-model="form.qty" @change="onChangeQty">
                    </div>
                </div>
                <div class="col-md-2">
                    <div>
                        <label>Buy Price</label>
                        <input type="text" class="form-control form-control-sm qty-input" placeholder="Amount" v-model="form.amount" @change="onChangeQty">
                    </div>
                </div>
            </div>
            
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