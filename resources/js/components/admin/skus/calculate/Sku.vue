<template>
    <div class="single_sku-container">
        <sku-form :item_id="item_id" @on-add-pricing="onAddPricing"></sku-form>
    </div>
</template>

<script>
import SkuForm from "./SkuForm";

export default {
    components: {
        "sku-form" : SkuForm,
    },
    props: {
        item_id: {required: true},
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
    }
}
</script>
