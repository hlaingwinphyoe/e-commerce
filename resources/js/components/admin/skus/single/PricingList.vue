<template>
  <div class="pricing_list-container mb-2">
    <pricing-item v-for="pricing in pricings" :key="pricing.id" :pricing="pricing" @on-update-pricing="onUpdatePricing" @on-delete-pricing="onDeletePricing"></pricing-item>
  </div>
</template>

<script>
import PricingItem from './PricingItem.vue';
export default {
    components: {
        "pricing-item" : PricingItem
    },
  props: {
    pricings: { required: true, default: () => [] },
  },
};
</script>
