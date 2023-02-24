<template>
    <div class="sku_price-container pb-4">
        <div class="row">
            <!-- Form -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <p class="card-title">Pricing Information</p>
                    </div>
                    <div class="card-body">
                        <!-- Has Attr -->
                        <div v-show="isShow">
                            <div class="form-check form-check-inline">
                                <input type="radio" name="has_attr" id="no-attr" class="custom-control-input" value="0" v-model="has_attr">
                                <label for="no-attr" class="custom-control-label ms-2">အမျိုးအစားကွဲ မရှိပါ</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input type="radio" name="has_attr" id="has-attr" class="custom-control-input" value="1" v-model="has_attr">
                                <label for="has-attr" class="custom-control-label ms-2">အမျိုးအစားကွဲ ရှိသည် (အရောင်၊ ဆိုဒ်၊ ပမာဏ)</label>
                            </div>
                        </div>

                        <!-- Attribute-form -->
                        <div class="attribute-form"></div>

                        <!-- Pricing Form -->
                        <div class="pricing-form pb-3 border-bottom">
                            <h5>Form</h5>
                            <div class="d-flex">
                                <div class="form-group me-3">
                                    <label for="" class="small text-muted">Mix Qty</label>
                                    <input type="number" min="1" class="quantity-input form-control form-control-sm" v-model="pricing_form.min_qty" required @change="onUpdatePricing">
                                </div>
                                <div class="form-group me-3">
                                    <label for="" class="small text-muted">Max Qty</label>
                                    <input type="number" min="1" class="quantity-input form-control form-control-sm" v-model="pricing_form.max_qty" @change="onUpdatePricing">
                                </div>
                                <div class="form-group me-3">
                                    <label for="" class="small text-muted">Price</label>
                                    <input type="text" class="pricing-input form-control form-control-sm" v-model="pricing_form.price" @change="onUpdatePricing">
                                </div>       
                                <div class="form-group align-self-end">
                                    <button class="btn btn-sm btn-primary me-2" @click.prevent="onUpdatePricing">
                                        <i class="fa fa-check"></i>
                                    </button>
                                </div>   
                            </div>
                        </div>

                        <!-- single or multi -->
                        <template v-if="has_attr === 0 || has_attr == '0' || (!data_has_attribute && has_sku)">
                            <single-sku :item_id="item_id" @change-attribute="changeAttribute"></single-sku>
                        </template>
                        <template v-else-if="has_attr == 1 || (data_has_attribute || has_sku)">
                            <multi-sku :item_id="item_id" :statuses="statuses" @change-attribute="changeAttribute"></multi-sku>
                        </template>
                    </div>          
                </div>
             </div>
        </div>

        <!-- Sku Lists -->
        
        
        
    </div>
</template>

<script>
import SingleSku from './single/Sku.vue';
import MultiSku from './multi/Sku.vue';
export default {
    components: {
        "single-sku" : SingleSku,
        "multi-sku" : MultiSku
    },
    props: {
        statuses: {required: true, default: () =>[]},
        item_id: {required: true},
        has_attribute: {required: true},
        has_sku: {required: true},
    },
    data() {
        return {
            has_attr : '',
            data_has_attribute: this.has_attribute,
            pricing_form: {
                price : '',
                min_qty: 1,
                max_qty: '',
            }
        }
    },
    computed: {
        isShow() {
            return this.data_has_attribute || this.has_sku || this.has_attr ? false : true;
        }
    },
    methods: {
        changeAttribute(data) {            
            this.has_attr = data ? 1 : 0;
            this.data_has_attribute = 1;
        },
        onUpdatePricing() {
            if(this.pricing_form.price && this.pricing_form.min_qty) {
                axios.patch(`/wapi/sku-pricings/${this.pricing.id}`, this.form).then(resp => {
                    // this.$emit('on-update-pricing', resp.data);
                });
            }
        }
    },
    
}
</script>