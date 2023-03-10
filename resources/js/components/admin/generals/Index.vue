<template>
    <div class="row">
       <div class="col-md-6">
            <add-form :inventory="data_inventory" :skus="data_skus" @on-add-sku="onAddSku" />
       </div>


        <!-- Sku Lists -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <p class="mb-2">Form No. {{ data_inventory.inventory_no }}</p>
                    <h5 class="text-primary mb-3">Item Lists</h5>

                    <GeneralList :skus="data_skus" :inventory="data_inventory" @on-update-sku="onUpdateSku" @on-delete-sku="onDeleteSku" />
                </div>
            </div>

            <!-- buttons -->
            <div v-if="data_skus.length && data_inventory && !data_inventory.is_published" class="text-end py-3">
                <a :href="url" class="btn btn-sm btn-outline-secondary me-2">Save as Draft</a>

                <button type="button" class="btn btn-sm btn-primary fw-bold" @click.prevent="onPublish">Confirm and close</button>
            </div>
        </div>
    </div>
</template>

<script>
import AddForm from '../generals/AddForm.vue'
import GeneralList from '../generals/GeneralList.vue';
export default {
  components: { AddForm,GeneralList },
  props: {
    general: {required : true , default: () => ''},
    skus: {required: true, default: () => []},
    type: {required:true}
  },
  data() {
        return {
            timeout: '',
            data_inventory: this.general ? this.general : '',
            data_skus: this.skus,
            skuList: 0,
            url: this.type == 'return' ? '/admin/returns' : '/admin/generals',
        };
    },
  methods: {
    onUpdateSku(data) {
        this.data_skus = data.skus;
        this.skuList += 1;
    },
    onDeleteSku(data) {
        this.data_skus = data.skus;
        this.skuList += 1;
    },
    onAddSku(data) {
        this.data_skus = data;
        this.skuList += 1;
    },

    onPublish() {
        axios.patch(`/wapi/inventory-publish/${this.data_inventory.id}`).then(resp => {
            window.location.href = this.url;
        });
    }
  }

}
</script>

<style>

</style>
