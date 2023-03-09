<template>
    <tr class="align-middle">
        <td>{{ sku.item_name }} {{ sku.data ? '('+ sku.data + ')' : '' }}</td>
        <td>
            <input type="text" class="form-control form-control-sm" v-model="form.qty"
            @change="onUpdateSkuInventory"
            />
        </td>
        <td>
            <button type="button" class="btn btn-sm btn-outline-danger" @click.prevent="onDeleteSkuInventory"><i class="fa fa-trash"></i></button>
        </td>
    </tr>
</template>

<script>
export default {
    props: {
        sku: {required: true},
        inventory: {required: true}
    },
    data() {
        return {
            form: {
                qty: this.sku.pivot.qty,
                amount: this.sku.pivot.amount,
                sku: this.sku.id
            }
        }
    },
    methods: {
        showAlert(message) {
            toastr.options.closeButton = true;
            toastr.success(message);
        },
        onUpdateSkuInventory() {
            axios.post(`/wapi/sku-inventories/${this.sku.pivot.inventory_id}`, this.form).then(resp => {
                this.$emit('on-update-sku', resp.data);
                this.showAlert('Successfully Updated')
            });
        },
        onDeleteSkuInventory() {
            axios.delete(`/wapi/sku-inventories/${this.sku.pivot.inventory_id}/${this.sku.id}`).then(resp => {
                this.$emit('on-delete-sku', resp.data);
                this.showAlert('Successfully Deleted')
            });
        },
    }
}
</script>
