<template>
    <tr>
        <td>
            <span class="no-overflow">{{ sku.item_name }} {{ sku.data ? '('+ sku.data +')' : '' }}</span>
            <p class="mb-0 small">{{ Number(sku.price).toLocaleString() }}</p>
        </td>
        <td class="">
            <div class="d-flex align-items-center">
                <a href="#" class="me-1 btn btn-sm count-buttons btn-outline-primary" @click.prevent="onDecreaseQty"><span><i class="fa-solid fa-minus"></i></span></a>
                <input type="text" class="form-control form-control-sm me-1 text-center qty-input" ref="qty" :value="sku.pivot.qty" @change="onChangeQty">
                <a href="#" class="me-1 btn btn-sm count-buttons btn-outline-primary" @click.prevent="onIncreaseQty"><span><i class="fa-solid fa-plus"></i></span></a>
            </div>
            <p class="small text-danger alert-message fw-bold" v-show="out_of_stock">Out of stock</p>
        </td>
        <td class="text-end">{{ Number(sku.pivot.qty * sku.pivot.price).toLocaleString() }}</td>
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
