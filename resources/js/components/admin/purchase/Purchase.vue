<template>
    <div class="puchase-container row">
        <!-- Add Form -->
        <div class="col-md-6 mb-3">
            <!-- inventory-update-form -->
            <div class="bg-white shadow rounded py-3 px-2 mb-4">
                <h5 class="text-primary mb-3">Supplier Information</h5>
                <div class="d-flex flex-wrap pb-3 align-items-center">
                    <div class="form-group me-2 w-sm-100">
                        <label for="">Supplier</label>
                        <select
                            v-model="supplier_form.supplier"
                            class="form-select form-select-sm"
                        >
                            <option value="">Choose Supplier</option>
                            <option
                                v-for="supplier in suppliers"
                                :key="supplier.id"
                                :value="supplier.id"
                            >
                                {{ supplier.name }}
                            </option>
                        </select>
                    </div>
                    <div class="form-group me-2 w-sm-100">
                        <label for="">Date</label>
                        <input
                            type="date"
                            class="form-control form-control-sm"
                            v-model="supplier_form.date"
                        />
                    </div>
                    <div class="form-group me-2 w-sm-100 align-self-end">
                        <button
                            type="button"
                            class="btn btn-sm btn-primary"
                            @click.prevent="onUpdateInventory"
                        >
                            <small class="me-2"
                                ><i class="fa fa-save"></i
                            ></small>
                            <span>Save</span>
                        </button>
                    </div>
                </div>
            </div>

            <add-form :inventory="inventory" :skus="data_skus" @on-add-sku="onAddSku"></add-form>
        </div>

        <!-- Sku Lists -->
        <div class="col-md-6">
            <div class="bg-white shadow rounded px-2 py-3">
               <p class="mb-2">Form No. {{ inventory.inventory_no }}</p>
                <h5 class="text-primary mb-3">Item Lists</h5>
                <purchase-list :skus="data_skus" @on-update-sku="onUpdateSku" @on-delete-sku="onDeleteSku"></purchase-list>
            </div>
        </div>
    </div>
</template>

<script>
import AddForm from "./AddForm.vue";
import PurchaseList from "./PurchaseList.vue";
export default {
    components: {
        "add-form": AddForm,
        "purchase-list": PurchaseList,
    },
    props: {
        inventory: { required: true },
        suppliers: { required: true, default: () => [] },
        skus: { required: true, default: () => [] },
    },
    data() {
        return {
            data_skus: this.skus,
            supplier_form: {
                supplier: this.inventory.supplier_id,
                date: this.inventory.date,
            },
        };
    },
    methods: {
        onUpdateInventory() {
            axios
                .patch(
                    `/wapi/inventories/${this.inventory.id}`,
                    this.supplier_form
                )
                .then((resp) => {
                    //console.log("here");
                });
        },
        onAddSku(data) {
            this.data_skus = data.skus;
        },
        onUpdateSku(data) {
            this.data_skus = data.skus;
        },
        onDeleteSku(data) {
            this.data_skus = data.skus;
        }
    },
};
</script>
