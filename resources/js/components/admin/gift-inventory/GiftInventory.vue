<template>
    <div class="gift-inventory-box">
        <inventory-list :inventories="data_inventories" @on-update-inventory="onUpdateInventory">
            <!-- <inventory-item v-for="inventory in inventories" :key="inventory.id" :inventory="inventory" @on-update-inventory="onUpdateInventory"></inventory-item> -->
        </inventory-list>
        <add-form :gift_id="gift_id" @on-add-inventory="onAddInventory"></add-form>
    </div>
</template>

<script>
import AddForm from "./AddForm.vue";
import InventoryList from "./InventoryList.vue";
// import InventoryItem from "./InventoryItem.vue";
export default {
    components: {
        "add-form" : AddForm,
        "inventory-list" : InventoryList,
        // "inventory-item" : InventoryItem,
    },
    props: {
        gift_id: {default: () => ''},
        inventories: {required: true, default: () => []}
    },
    data(){
        return {
            data_inventories : this.inventories
        }
    },
    methods: {
        onAddInventory(data) {
            console.log(data);
            // this.data_inventories = data;
            this.data_inventories.push(data);
        },
        onUpdateInventory(data) {
            this.data_inventories = data;
            // this.data_inventories.push(data);
        }
    }
}
</script>