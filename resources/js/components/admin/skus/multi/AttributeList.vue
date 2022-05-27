<template>
    <div class="attribute_list-container">
        <attribute-item v-for="attribute in attributes" :key="attribute.id" :attribute="attribute" @on-delete-attribute="onDeleteAttribute"></attribute-item>
    </div>
</template>

<script>
import AttributeItem from './AttributeItem.vue';
export default {
    components: {
        'attribute-item' : AttributeItem
    },
    props: {
        attributes : {required: true, default: () => []}
    },
    methods: {
        onDeleteAttribute(data) {
            this.$emit('on-delete-attribute', data);
        }
    }
}
</script>