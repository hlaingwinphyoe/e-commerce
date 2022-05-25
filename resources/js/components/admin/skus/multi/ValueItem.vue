<template>
    <div class="value_item-container me-2 mb-2 position-relative">
        <p class="mb-0 border ps-3 pe-4">{{ attr_value.name }}</p>
        <div class="remove-button">
            <a href="#" class="text-secondary" @click.prevent="onDeleteValue"><i class="fa fa-times"></i></a>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        attr_value : {required: true}
    },
    methods: {
        onDeleteValue() {
            axios.delete(`/wapi/values/${this.attr_value.id}`).then(resp => {
                this.$emit('on-delete-value', resp.data);
            });
        }
    }
}
</script>

<style>
.remove-button {
    position: absolute;
    top: -5px;
    right: 0;
}
</style>