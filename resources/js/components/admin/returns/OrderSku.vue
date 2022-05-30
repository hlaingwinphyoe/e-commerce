<template>
    <tr>
        <td>
            {{ order_sku.item_name }}{{ order_sku.data ? '(' + order_sku.data + ')' : '' }}
        </td>
        <td><input type="text" class="form-control form-control-sm" v-model="form.qty"></td>
        <td><input type="text" class="form-control form-control-sm" v-model="form.price"></td>
        <td class="text-end"><button class="btn btn-sm btn-outline-primary" @click.prevent="onAddReturnSku"><i class="fa fa-check"></i></button></td>
    </tr>
</template>

<script>
export default {
    props: {
        order_sku: {required: true},
        p_return: {required: true}
    },
    data() {
        return {
            form: {
                'sku': this.order_sku.id,
                'qty' : this.order_sku.pivot.qty,
                'price' : this.order_sku.pivot.price,
            }
        }
    },
    methods: {
        onAddReturnSku() {
            axios.post(`/wapi/return-skus/${this.p_return.id}`, this.form).then(resp => {
                this.$emit('on-add-return-sku', resp.data);
            });
        }
    }
}
</script>