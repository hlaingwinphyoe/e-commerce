<template>
    <div class="sku_list-container row">
        <sku-item v-for="sku in skus" :key="sku.id" :sku="sku" @on-delete-sku="onDeleteSku" @on-delete-variant="onDeleteVariant"></sku-item>

        <div class="col-md-4 mb-3">
            <div class="border py-3 px-4 h-100 position-relative rounded border">
                <p class="mb-2 fw-bold text-primary">SKU နောက်တခုထည့်မည်</p>
                <div v-for="attribute in attributes" :key="attribute.id">
                    <add-value-form
                        :attribute="attribute"
                        @on-add-value="onAddValue"
                    ></add-value-form>
                </div>
                <div v-show="canAdd">
                    <button
                        class="btn btn-sm btn-secondary"
                        :class="canAdd ? '' : 'disabled'"
                        @click.prevent="onAddSku"
                    >
                        Add
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import AddValueForm from "./AddValueForm.vue";
import SkuItem from './SkuItem.vue';

export default {
    components: {
        "add-value-form": AddValueForm,
        'sku-item' : SkuItem
    },
    props: {
        skus: { required: true, default: () => [] },
        attributes: { required: true, default: () => [] },
        item_id: { required: true }
    },
    data() {
        return {
            form: {
                values: [],
                item_id: this.item_id
            },
        };
    },
    methods: {
        onAddValue(data) {
            this.form.values.push(data.id);
        },
        onAddSku() {
            axios.post(`/wapi/skus`, this.form).then(resp => {
                this.$emit("on-add-new-sku", resp.data);
            });
        },
        onDeleteSku(data) {
            this.$emit("on-delete-sku", data);
        },
        onDeleteVariant(data) {
            this.$emit('on-delete-variant', data);
        }
    },
    computed: {
        canAdd() {
            return this.attributes.length == this.form.values.length;
        },
        getInitialAttributes() {
            return this.attributes.filter(attr => {
                return attr.values.length == 0;
            });
        }
    }
};
</script>

<style>
.remove-button {
    position: absolute;
    top: -5px;
    right: 0;
}
</style>
