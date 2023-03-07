<template>
    <tr>
        <td>
            <span class="no-overflow">{{ sku.item_name }} {{ sku.data ? '('+ sku.data +')' : '' }}</span>
            <p class="mb-0 small">{{ Number(sku.price).toLocaleString() }}</p>
        </td>
        <td class="">
            <div class="d-flex align-items-center">
                <div class="btn-group btn-group-sm" role="group" aria-label="Basic outlined example">
                    <button @click.prevent="onDecreaseQty" class="btn btn-outline-primary count-buttons"><i class="fa-solid fa-minus"></i></button>
                    <input type="text" class="form-control rounded-0 form-control-sm text-center qty-input" ref="qty" :value="sku.pivot.qty" @change="onChangeQty">
                    <button @click.prevent="onIncreaseQty" class="btn btn-outline-primary count-buttons"><i class="fa-solid fa-plus"></i></button>
                </div>
            </div>
            <p class="small text-danger alert-message fw-bold" v-show="out_of_stock">Out of stock</p>
        </td>
        <td class="text-end">{{ Number(sku.pivot.qty * sku.price).toLocaleString() }}</td>
        <td>
            <a href="#" @click.prevent="onDeleteSku"><small class="btn btn-sm btn-outline-danger"><i class="fa fa-times"></i></small></a>
        </td>
    </tr>
</template>

<script>
export default {
    props: {
        sku: {required: true},
    },
    data() {
        return {
            data_stock : this.sku.stock,
            out_of_stock: false,
            // form: {
            //     qty: this.sku.pivot.qty,
            // }
        }
    },
    methods: {
        onDecreaseQty() {
            let quantity = parseInt(this.$refs.qty.value);
            this.$refs.qty.value = quantity <=1 ? 1 : quantity - 1;
            this.onUpdateSku();
        },
        onIncreaseQty() {
            let quantity = parseInt(this.$refs.qty.value);
            this.$refs.qty.value = quantity >= this.data_stock ? this.data_stock : quantity + 1;
            this.onUpdateSku();
        },
        onChangeQty() {
            let quantity = parseInt(this.$refs.qty.value);
            if(quantity <= this.data_stock) {
                this.$refs.qty.value = quantity <= this.data_stock ? quantity : this.data_stock;
                this.onUpdateSku();
            }else{
                this.out_of_stock = true;
            }
        },
        onUpdateSku() {
            var form = {
                'qty' : this.$refs.qty.value
            };
            axios.patch(`/wapi/order-skus/${this.sku.pivot.order_id}/${this.sku.id}`, form).then(resp => {
                this.$emit('on-update-sku', resp.data);
            });
        },
        onDeleteSku() {
            axios.delete(`/wapi/order-skus/${this.sku.pivot.order_id}/${this.sku.id}`).then(resp => {
                this.$emit('on-delete-sku', resp.data);
            });
        }

    },

}
</script>

<style>
.qty-input  {
    width: 35px;
}
.count-buttons {
    padding: 0 5px;
}
.no-overflow {
    display: block;
    height: 25px;
    line-height: 25px;
    overflow: hidden;
}
</style>
