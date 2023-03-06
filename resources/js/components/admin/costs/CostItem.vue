<template>
    <div class="cost_item-container mb-2">
        <input type="hidden" name="costs[]" :value="cost.id" />
        <div>
            <span class="text-capitalize">{{ cost.type }}-{{ index }}</span>
        </div>
        <div class="row">
            <div class="col-md-4">
                <input
                    type="text"
                    class="form-control form-control-sm"
                    placeholder="Amount"
                    v-model="form.amt"
                />
            </div>
            <div class="col-md-4">
                <select
                    class="form-select"
                    v-model="form.currency_id"
                >
                    <option
                        v-for="currency in currencies"
                        :key="currency.id"
                        :value="currency.id"
                    >
                        {{ currency.name }}
                    </option>
                </select>
            </div>
            <div class="col-md-4">
                <button class="btn btn-sm btn-secondary" @click.prevent="onUpdateCost">
                    <small>
                        <i class="fa fa-check"></i>
                    </small>
                </button>
                <button class="btn btn-sm btn-danger" @click.prevent="onDeleteCost">
                    <small>
                        <i class="fa fa-trash"></i>
                    </small>
                </button>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        cost: { required: true, default: () => [] },
        index: { required: true, default: () => [] },
    },
    data() {
        return {
            currencies: [],
            form: {
                amt: this.cost.amt,
                currency_id: this.cost.currency_id,
            },
        };
    },
    created() {
        axios.get(`/wapi/currencies`).then((resp) => {
            this.currencies = resp.data;
        });
    },
    methods: {
        onUpdateCost() {
            if (this.form.amt) {
                axios.put(`/wapi/costs/${this.cost.id}`, this.form).then((resp) => {
                    this.$emit("on-update-cost", resp.data);
                });
            } else {
                axios.delete(`/wapi/costs/${this.cost.id}`).then((resp) => {
                    this.$emit("on-destroy-cost", resp.data);
                });
            }
        },
        onDeleteCost() {
            axios.delete(`/wapi/costs/${this.cost.id}`).then((resp) => {
                this.$emit("on-destroy-cost", resp.data);
            });
        },
    },
};
</script>
