<template>
    <tr>
        <td>
            {{ sku.item_name }}{{ sku.data ? '(' + sku.data + ')' : '' }}
        </td>
        <td><input type="text" class="form-control form-control-sm" v-model="form.qty"></td>
        <td><input type="text" class="form-control form-control-sm" v-model="form.price"></td>
        <td class="text-end">
            <button class="btn btn-sm btn-outline-primary me-2" @click.prevent="onUpdateSku"><i class="fa fa-check"></i></button>
            <button class="btn btn-sm btn-outline-danger me-2" @click.prevent="onDeleteSku"><i class="fa fa-trash"></i></button>
        </td>
    </tr>
</template>

<script>
export default {
    props: {
        sku: {required: true},
        p_return: {required: true}
    },
    data() {
        return {
            form: {
                'sku': this.sku.id,
                'qty' : this.sku.pivot.qty,
                'price' : this.sku.pivot.price,
            }
        }
    },
    methods: {
        onUpdateSku() {
            axios.post(`/wapi/return-skus/${this.p_return.id}`, this.form).then(resp => {
                // this.$emit('on-add-return-sku', resp.data);
            });
        },
        onDeleteSku() {
            axios.delete(`/wapi/return-skus/${this.p_return.id}/${this.sku.id}`).then(resp => {
                this.$emit('on-delete-sku', resp.data);
            });
        }
    }
}
</script>