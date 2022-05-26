<template>
    <tr>
        <td>
            <span>{{ sku.item.name }} {{ sku.data ? '('+ sku.data +')' : '' }}</span>
        </td>
        <td>
            <div class="d-flex">
                <a
                    href="#"
                    class="btn btn-sm btn-outline-secondary"
                    :class="inventory.is_published ? 'disabled' : ''"
                    @click.prevent="onDecreaseQty"
                    ><small><i class="fa fa-minus"></i></small
                ></a>
                <input
                    type="text"
                    class="form-control form-control-sm mx-2 text-center qty-input border-0"
                    ref="qty"
                    disabled
                    :value="form.qty"
                />
                <a
                    href="#"
                    class="btn btn-sm btn-outline-secondary"
                    :class="inventory.is_published ? 'disabled' : ''"
                    @click.prevent="onIncreaseQty"
                    ><small><i class="fa fa-plus"></i></small
                ></a>
            </div>
        </td>
        <td>
            <div class="form-group">
                <input
                    type="text"
                    class="form-control form-control-sm"
                    placeholder="Amount"
                    v-model="form.amount"
                />
            </div>
        </td>
        <td>
            <div class="form-group">
                <textarea
                    class="form-control"
                    rows="2"
                    v-model="form.remark"
                ></textarea>
            </div>
        </td>
        <td>
            <div>
                <button
                    class="btn btn-sm btn-outline-dark mr-2"
                    @click.prevent="onUpdateSku"
                >
                    <small><i class="fa fa-check"></i></small>
                </button>
                <button
                v-if="!inventory.is_published"
                    class="btn btn-sm btn-danger mr-2"
                    @click.prevent="onDeleteSku"
                >
                    <small><i class="fa fa-trash"></i></small>
                </button>
            </div>
        </td>
    </tr>
</template>

<script>
export default {
    props: {
        sku: { required: true },
        inventory: { required: true }
    },
    data() {
        return {
            inven: this.sku,
            item: "",
            name: this.sku.name,
            results: [],
            timeout: "",
            form: {
                sku: this.sku.id ? this.sku.id : "",
                amount: this.sku.pivot.amount
                    ? this.sku.pivot.amount
                    : "",
                qty: this.sku.pivot.qty ? this.sku.pivot.qty : "",
                remark: this.sku.pivot.remark
                    ? this.sku.pivot.remark
                    : ""
            }
        };
    },
    created() {
        axios.get(`/wapi/get-item/${this.sku.id}`).then(resp => {
            this.item = resp.data;
            this.name = resp.data.name + ` (${this.sku.data})`;
        });
    },
    methods: {
        onDecreaseQty() {
            let quantity = parseInt(this.form.qty);
            this.form.qty = quantity <= 1 ? 1 : quantity - 1;
        },
        onIncreaseQty() {
            this.form.qty = parseInt(this.form.qty) + 1;
        },
        onInputChange() {
            clearTimeout(this.timeout);
            if (this.name) {
                this.timeout = setTimeout(() => {
                    axios
                        .get(`/wapi/get-data`, {
                            params: { name: this.name, order: null }
                        })
                        .then(resp => {
                            this.results = resp.data;
                        });
                }, 300);
            } else {
                this.results = [];
            }
        },
        onSelectData(data, item_name) {
            this.form.sku = data.id;
            this.name = data.data
                ? item_name + " (" + data.data + ")"
                : item_name;
            this.results = [];
        },
        onUpdateSku() {
            axios
                .patch(
                    `/wapi/sku-inventory/${this.inventory.id}/update`,
                    this.form
                )
                .then(resp => {
                    // this.inven = resp.data;
                });
        },
        onDeleteSku() {
            axios
                .delete(
                    `/wapi/sku-inventory/${this.inventory.id}/${this.sku.id}`
                )
                .then(resp => {
                    this.$emit("on-delete-sku", resp.data);
                });
        }
    }
};
</script>

<style></style>
