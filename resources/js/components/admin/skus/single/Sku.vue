<template>
    <div class="single_sku-container">
        <div class="">            
            <a href="#" class="small" @click.prevent="changeAttribute">အမျိုးအစားကွဲရှိပါသလား ?</a>
            <p class="small mb-2 py-3 fw-bold">အရေအတွက်ပေါ်မူတည်၍ ဈေးအမျိုးမျိုး ထည့်နိုင်ပါသည်။</p>
        </div>        
        <pricing-list v-if="pricings.length" :pricings="pricings"></pricing-list>
        <sku-form :item_id="item_id" @on-add-pricing="onAddPricing"></sku-form>
    </div>
</template>

<script>
import PricingList from './PricingList.vue';
import SkuForm from './Form.vue';
export default {
    components: {
        "sku-form" : SkuForm,
        "pricing-list" : PricingList,
    },
    props: {
        item_id: {required: true},
        // roles: {required: true, default: () =>[]},
    },
    data() {
        return {
            pricings : []
        }
    },
    created() {
        axios.get(`/wapi/item-pricings/${this.item_id}`).then(resp => {
            this.pricings = resp.data;
        });
    },
    methods: {
        onAddPricing(data) {
            this.pricings = data;
        },
        onUpdatePricing(data) {
            this.pricings = this.pricings.map(x => {
                return x.id == data.id ? data : x;
            });
        },
        onDeletePricing(data) {
            this.pricings = this.pricings.filter(x => {
                return x.id != data.id;
            });
        },
        changeAttribute() {
            axios.delete(`/wapi/single-skus/${this.item_id}`).then(resp => {
                this.$emit('change-attribute', true);
            });            
        }
    }
}
</script>