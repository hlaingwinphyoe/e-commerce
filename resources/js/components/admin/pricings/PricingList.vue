<template>
  <div class="pricing_list-container">
    <template v-for="pricing in pricings">
      <slot :pricing="pricing"></slot>
    </template>
  </div>
</template>

<script>
export default {
  props: {
    pricings: { required: true, default: () => [] },
  },
};
</script>
