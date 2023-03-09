<template>
    <tr class="align-middle">
        <td>{{ index+1 }}</td>
        <td>{{ expense.name }}</td>
        <td>{{ expense.type ? expense.type.name: '' }}</td>
        <td>{{ expense.supplier ? expense.supplier.name : '' }}</td>
        <td>{{ expense.reference_id }}</td>
        <td>
            <input type="text" v-model="form.amount" class="form-control form-control-sm qty-amt-input" @change="onChangeAmount">
        </td>
        <td>
            <button type="button" class="btn btn-sm btn-outline-danger" @click.prevent="onDelete">
                <i class="fa fa-trash"></i>
            </button>
        </td>
    </tr>
</template>

<script>

export default {
    props: {
        expense: {required: true,},
        index: {required: true},
        suppliers: {required: true, default: () => []}
    },
    data() {
        return {
            form: {
                amount: this.expense.amount
            }
        }
    },
    methods: {
        showAlert() {
            toastr.options.closeButton = true;
            toastr.success("Successfully Updated");
        },
        onChangeAmount() {
           axios.patch(`/wapi/expenses/${this.expense.id}`, this.form).then(resp => {
                this.$emit('on-update', resp.data);
                this.showAlert();
           });
        },
        onDelete() {
            axios.delete(`/wapi/expenses/${this.expense.id}`).then(resp => {
                this.$emit('on-update', resp.data);
            });
        }
    }
}
</script>
