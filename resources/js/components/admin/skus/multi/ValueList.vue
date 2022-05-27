<template>
    <div class="value_list-container d-flex">
        <value-item v-for="value in values" :key="value.id" :attr_value="value" @on-delete-value="onDeleteValue"></value-item>
    </div>
</template>

<script>
import ValueItem from './ValueItem.vue';
export default {
    components: {
        'value-item' : ValueItem
    },
    props: {
        values : {required: true, default: () => []}
    },
    methods: {
        onDeleteValue(data) {
            this.$emit('on-delete-value', data)
        }
    }
}
</script>