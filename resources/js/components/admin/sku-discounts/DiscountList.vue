<template>
    <div class="discount_list-container">
        <discount-item v-for="discount in discounts" :key="discount.id" :discount="discount" @on-update-discount="onUpdateDiscount" @on-destroy-discount="onDestroyDiscount"></discount-item>
    </div>
</template>

<script>
import DiscountItem from './DiscountItem.vue';
export default {
    components: {
        'discount-item' : DiscountItem
    },
    props: {
        discounts: {required: true}
    },
    methods: {
        onUpdateDiscount(data) {
            this.$emit('on-update-discount', data)
        },
        onDestroyDiscount(data) {
            this.$emit('on-destroy-discount', data);
        }
    }
}
</script>