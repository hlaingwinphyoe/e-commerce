<template>
    <div class="wastes-container mb-2">
        <waste-list :wastes="data_wastes">
            <waste-item
                slot-scope="{ waste, index }"
                :waste="waste"
                :index="index"
                :statuses="statuses"
                @on-update-waste="onUpdateWaste"
                @on-destroy-waste="onDestroyWaste"
            ></waste-item>
        </waste-list>

        <add-waste-form
            :statuses="statuses"
            @on-add-waste="onAddWaste"
        ></add-waste-form>
    </div>
</template>

<script>
import WasteList from "./WasteList.vue";
import WasteItem from "./WasteItem.vue";
import AddWasteForm from "./AddWasteForm.vue";

export default {
    props: {
        wastes: {required: true, default: () => []},
        statuses: {required: true, default: () => []},
        // item_id: { required: true }
    },
    components: {
        "waste-list": WasteList,
        "waste-item": WasteItem,
        "add-waste-form": AddWasteForm,
    },
    data() {
        return {
            data_wastes: this.wastes,
        };
    },
    methods: {
        onAddWaste(data) {
            this.data_wastes.push(data);
            this.$emit("on-update-waste", this.data_wastes);
        },
        onUpdateWaste(data) {
            this.data_wastes = this.data_wastes.map((x) => {
                return x.id == data.id ? data : x;
            });
            this.$emit("on-update-waste", this.data_wastes);
        },
        onDestroyWaste(data) {
            this.data_wastes = this.data_wastes.filter((x) => x.id !== data.id);
            this.$emit("on-update-waste", this.data_wastes);
        },
    },
};
</script>
