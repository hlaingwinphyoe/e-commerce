<template>
    <div>
        <inventory-item v-for="inventory in inventories" :key="inventory.id" :inventory="inventory" @on-update-inventory="onUpdateInventory"></inventory-item>
    </div>
</template>

<script>
import InventoryItem from "./InventoryItem.vue";

export default {
    components: {
        "inventory-item" : InventoryItem,
    },
    props: {
        inventories: { required: true, default: () => [] }
    },
    methods: {
        onUpdateInventory(data) {
            this.$emit('on-update-inventory', data);
        }
    }
};
</script>
