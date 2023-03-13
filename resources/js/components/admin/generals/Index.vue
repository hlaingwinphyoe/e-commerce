<template>
    <div class="row">
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-body">
                    <div class="form-group mb-3">
                        <label for="" class="form-label">
                            Date
                            <span class="text-danger">**</span>
                        </label>
                        <input type="date" name="date" class="form-control form-control-sm" placeholder="Date" v-model="form.date">
                    </div>
                    <div class="form-group mb-3">
                        <label for="" class="form-label">Description</label>
                        <textarea name="desc" id="" cols="30" rows="7" class="form-control" v-model="form.desc"></textarea>
                    </div>

                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="type" id="general" value="general" v-model="form.type">
                        <label class="form-check-label" for="general">General Use</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="type" id="waste" value="waste" v-model="form.type">
                        <label class="form-check-label" for="waste">Waste / Damage / Expired</label>
                    </div>

                    <div class="from-group mt-3">
                        <button @click.prevent="onFormSubmit" class="btn btn-sm btn-dark">
                            <small class="me-2"><i class="fas fa-save"></i></small>
                            <span>Update</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Add Sku Form -->
            <div v-if="data_inventory && !data_inventory.is_published" class="mb-3">
                <add-form :inventory="data_inventory" :skus="data_skus" @on-add-sku="onAddSku" />
            </div>
        </div>


        <!-- Sku Lists -->
        <div class="col-md-8">
            <div class="card" v-if="this.data_skus.length">
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
import GeneralList from '../generals/GeneralList.vue';
import AddForm from '../generals/AddForm.vue'
export default {
  components: { AddForm,GeneralList },
  props: {
    general: {required : true , default: () => ''},
    skus: {required: true, default: () => []},
    type: {required:true}
  },
  data() {
        return {
            form: {
                date: this.general ? this.general.date : '',
                desc: this.general ? this.general.desc : '',
                type: this.type,
            },
            timeout: '',
            data_inventory: this.general ? this.general : '',
            data_skus: this.skus,
            skuList: 0,
            url: this.type == 'return' ? '/admin/returns' : '/admin/generals',
        };
    },
  methods: {
    showAlert(message) {
        toastr.options.closeButton = true;
        toastr.success(message);
    },
    onFormSubmit() {
        axios.patch(`/wapi/generals/${this.general.id}`, this.form).then(resp => {
            this.data_inventory = resp.data;
            this.data_skus = resp.data.skus;
            this.showAlert("Successfully Updated.")
        });
    },

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
