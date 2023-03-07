<template>
    <div class="costs-container mb-3">
        <cost-item
                v-for="(cost,index) in data_costs" :key="cost.id"
                :cost="cost"
                :index="index"
                @on-update-cost="onUpdateCost"
                @on-destroy-cost="onDestroyCost"
            ></cost-item>

        <add-cost-form
            :exchange_rates="exchange_rates"
            @on-add-cost="onAddCost"
        ></add-cost-form>
    </div>
</template>

<script>
import CostList from "./CostList.vue";
import CostItem from "./CostItem.vue";
import AddCostForm from "./AddCostForm.vue";

export default {
    props: {
        costs: { required: true, default: () => [] },
        exchange_rates: { required: true, default: () => [] },
        // item_id: { required: true }
    },
    components: {
        "cost-list": CostList,
        "cost-item": CostItem,
        "add-cost-form": AddCostForm,
    },
    data() {
        return {
            data_costs: this.costs,
        };
    },
    methods: {
        onAddCost(data) {
            this.data_costs.push(data);
            this.$emit("on-update-cost", this.data_costs);
        },
        onUpdateCost(data) {
            this.data_costs = this.data_costs.map((x) => {
                return x.id === data.id ? data : x;
            });
            this.$emit("on-update-cost", this.data_costs);
        },
        onDestroyCost(data) {
            this.data_costs = this.data_costs.filter((x) => x.id !== data.id);
            this.$emit("on-update-cost", this.data_costs);
        },
    },
};
</script>
