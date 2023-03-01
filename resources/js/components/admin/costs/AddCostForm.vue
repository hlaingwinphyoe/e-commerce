<template>
    <div class="add_cost_form-container">
        <label for>Add Costs</label>
        <div class="row align-items-center">
            <div class="col-md-4">
                <input
                    type="text"
                    placeholder="Amount"
                    class="form-control form-control-sm"
                    v-model="form.amt"
                />
            </div>
            <div class="col-md-4">
                <select
                    class="form-select"
                    v-model="form.currency_id"
                >
                    <option
                        v-for="exchange_rate in exchange_rates"
                        :key="exchange_rate.id"
                        :value="exchange_rate.currency_id"
                    >
                        {{ exchange_rate.currency.name }}
                    </option>
                </select>
            </div>
            <div class="col-md-4">
                <button
                    class="btn btn-sm btn-primary"
                    :disabled="form.amt === '' || form.currency_id === ''"
                    @click.prevent="onAddCost"
                >
                    Add Cost
                </button>
            </div>
        </div>
    </div>
</template>


<script>
export default {
    props: {
        exchange_rates: { required: true, default: () => [] },
    },
    data() {
        return {
            //   currencies: [],
            form: {
                amt: "",
                currency_id: this.exchange_rates[this.exchange_rates.length - 1]
                    .currency_id,
            },
        };
    },
    created() {
        // axios.get(`/wapi/currencies`).then(resp => {
        //   this.currencies = resp.data;
        // });
    },
    methods: {
        onAddCost() {
            axios.post(`/wapi/costs`, this.form).then((resp) => {
                this.form.amt = "";
                this.$emit("on-add-cost", resp.data);
            });
        },
    },
};
</script>
