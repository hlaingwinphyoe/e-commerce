<template>
    <div class="sku_item-container col-md-4 mb-3">
        <div class="bg-sidebar py-3 px-4 h-100 position-relative">
            <p class="mb-2 fw-bold text-primary">{{ sku_name }}</p>
            <variant-list v-show="variants.length" :variants="variants" @on-delete-variant="onDeleteVariant"></variant-list>
            <div class="mb-2 text-muted" v-show="attributes.length">
                <add-attribute-list :attributes="attributes" @on-add-value="onAddValue"></add-attribute-list>
            </div>            
            <div v-show="data_pricings.length" v-for="pricing in data_pricings" :key="pricing.id">
                <sku-pricing-item :pricing="pricing" @on-delete-pricing="onDeletePricing"></sku-pricing-item>
            </div>
            <sku-media-upload :sku_id="sku.id" :images="images" @on-destroy-media="onDestroyMedia"></sku-media-upload>
            <div class="close-buttons shadow">
                <a href="#" class="text-danger" @click.prevent="onDeleteSku"><i class="fa fa-times fa-lg"></i></a>
            </div>
        </div>
        
    </div>
</template>

<script>
import SkuPricingItem from './SkuPricingItem.vue';
import VariantList from './VariantList.vue';
import AddAttributeList from './add-attributes/AttributeList.vue';
export default {
    components: {
        "sku-pricing-item" : SkuPricingItem,
        "variant-list" : VariantList,
        "add-attribute-list" : AddAttributeList,
    },
    props: {
        sku: {required: true},        
    },
    data() {
        return {
            sku_name: this.sku.data ? this.sku.data : '',
            images: this.sku.medias.length ? this.sku.medias : [],
            variants : this.sku.variants.length ? this.sku.variants : [],
            attributes: [],
            org_attributes: [],
            data_pricings: this.sku.pricings ?? []

        }
    },
    created() {
        axios.get(`/wapi/sku-attributes/${this.sku.id}`).then(resp => {
            if(resp.data.length) {
                this.org_attributes = resp.data;
                this.attributes = resp.data.filter(attr => {
                    let boo = this.variants.filter(variant => {
                        return variant.attribute_id == attr.id;
                    });
                    return boo.length == 0 ? attr : '';
                });
            }
        });
    },
    methods: {
        onDeleteSku() {
            axios.delete(`/wapi/skus/${this.sku.id}`).then(resp => {
                this.$emit('on-delete-sku', resp.data);
            });
        },
        onDestroyMedia(data) {
            this.images = this.images.filter(x => {
                return x.id !== data.id;
            });
        },
        onDeleteVariant(data) {
            this.variants = this.variants.filter(x => {
                return x.id !== data.variant_id;
            });
            this.sku_name = data.sku_name;            
            this.attributes.push(data.attribute);
            this.$emit('on-delete-variant', data.skus);
        },
        onAddValue(data) {
            this.attributes = this.attributes.filter(attr => {
                return attr.id !== data.attribute_id;
            });
            this.sku_name = data.sku_name;
            //reload variants
            axios.get(`/wapi/sku-variants/${this.sku.id}`).then(resp => {
                this.variants = resp.data;
            });
        },
        onDeletePricing(data) {
            this.data_pricings = this.data_pricings.filter(pricing => {
                return pricing.id !== data.id;
            });
        },
    }
}
</script>

<style>
.close-buttons {
    position: absolute;
    top: 0;
    right: 5px;
    padding: 0 3px;
}
</style>