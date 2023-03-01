<template>
  <div class="pricing_box-container">
    <pricing-list :pricings="data_pricings">
      <pricing-item
        slot-scope="{ pricing }"
        :pricing="pricing"
        :statuses="statuses"
        :roles="roles"
        :pure_price="pure_price"
        @on-update-pricing="onUpdatePricing"
        @on-delete-pricing="onDeletePricing"
      ></pricing-item>
    </pricing-list>
    <add-pricing
      :statuses="statuses"
      :roles="roles"
      @on-add-pricing="onAddPricing"
    ></add-pricing>
  </div>
</template>

<script>
import PricingList from "./PricingList.vue";
import PricingItem from "./PricingItem.vue";
import AddPricing from "./AddPricing.vue";
export default {
  components: {
    "pricing-list": PricingList,
    "pricing-item": PricingItem,
    "add-pricing": AddPricing,
  },
  props: {
    pricings: { required: true, default: () => [] },
    statuses: { required: true, default: () => [] },
    roles: { required: true, default: () => [] },
    pure_price: { required: true, default: () => [] },
  },
  data() {
    return {
      data_pricings: this.pricings,
    };
  },
  methods: {
    onAddPricing(data) {
      this.data_pricings.push(data);
    },
    onUpdatePricing(data) {
      this.data_pricings = this.data_pricings.map((x) => {
        return x.id === data.id ? data : x;
      });
    },
    onDeletePricing(data) {
      this.data_pricings = this.data_pricings.filter((x) => {
        return x.id !== data.id;
      });
    },
  },
};
</script>
