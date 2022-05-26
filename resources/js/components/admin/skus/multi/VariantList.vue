<template>
    <div class="variant_list-container mb-2 d-flex">
        <variant-item v-for="variant in variants" :key="variant.id" :variant="variant" @on-delete-variant="onDeleteVariant"></variant-item>
    </div>
</template>

<script>
import VariantItem from './VariantItem.vue';
export default {
    components: {
        'variant-item' : VariantItem,
    },
    props: {
        variants : {required: true}
    },
    methods: {
        onDeleteVariant(data) {
            this.$emit('on-delete-variant', data);
        }
    }
}
</script>