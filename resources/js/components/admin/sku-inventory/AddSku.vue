<template>
    <div>
        <div class="row" v-if="this.data_skus.length != 0">
            <div class="col mb-2">
                <div class="py-2 mb-3">
                    <h5 class="mb-3">Item Lists</h5>
                    <div class="table-responsive">
                        <table class="table table-borderless">
                            <thead class="">
                                <tr>
                                    <th>Item Name</th>
                                    <th>Quantity</th>
                                    <th>Amount</th>
                                    <th>Remark</th>
                                    <th><i class="fas fa-border-style"></i></th>
                                </tr>
                            </thead>
                            <sku-list :skus="data_skus" :inventory="inventory"  @on-delete-sku="onDeleteSku" >
                            
                            </sku-list>
                        </table>
                    </div>
                </div>

                <div class="text-end">
                    
                    <a
                        href="/admin/inventories"
                        class="btn btn-sm btn-outline-info d-none"
                        >မှာယူပါမည်</a
                    >
                </div>
            </div>
        </div>
        <div class="row" v-if="inventory.is_published == 0">
            <div class="col mb-2">
                <add-form
                    :inventory="inventory"
                    @on-add-sku="onAddSku"
                ></add-form>
            </div>
        </div>
    </div>
</template>

<script>
import AddForm from "./Form.vue";
import SkuList from "./SkuList.vue";
import SkuItem from "./SkuItem.vue";

export default {
    components: {
        "add-form": AddForm,
        "sku-list": SkuList,
        "sku-item": SkuItem
    },
    props: {
        inventory: { required: true },
        skus: { required: true, default: () => [] }
    },
    data() {
        return {
            data_skus: this.skus
        };
    },
    methods: {
        onAddSku(data) {
            this.data_skus = data;
        },
        onDeleteSku(data) {
            this.data_skus = data;
        }
    }
};
</script>

<style></style>
