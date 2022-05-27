<template>
    <div class="attr_list-container">
        <add-attribute-item v-for="attribute in attributes" :key="attribute.id" :attribute="attribute" @on-add-value="onAddValue"></add-attribute-item>
    </div>
</template>

<script>
import AddAttributeItem from '../add-attributes/AttributeItem.vue';
export default {
    components : {
        'add-attribute-item': AddAttributeItem,
    },
    props: {
        attributes: { required: true, default: () => [] },
    },
    data() {
        return {
            //
        };
    },
    methods: {
        onAddValue(data) {
            this.$emit('on-add-value', data);
        }
    },
    computed: {
        //
    }
};
</script>


