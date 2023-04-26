<template>
    <tr class="align-middle">
        <td>{{ sku.item_name }} {{ sku.data ? '('+ sku.data + ')' : '' }}</td>
        <td>
            <input type="text" class="form-control form-control-sm" v-model="form.qty" />
        </td>
        <td>
            <input type="text" class="form-control form-control-sm" v-model="form.amount" />
        </td>
        <td>
            <button type="button" class="btn btn-sm btn-outline-primary me-2" @click.prevent="onUpdateSkuInventory"><i class="fa fa-check"></i></button>
            <button type="button" class="btn btn-sm btn-outline-danger" @click.prevent="onDeleteSkuInventory"><i class="fa fa-trash"></i></button>
        </td>
    </tr>
</template>

<script>
export default {
    props: {
        sku: {required: true,}
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
            axios.patch(`/wapi/sku-inventories/${this.sku.pivot.inventory_id}/${this.sku.id}`, this.form).then(resp => {
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
