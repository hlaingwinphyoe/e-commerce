<template>
    <div class="sku_price-container pb-4">
        <div v-show="isShow">
            <div class="form-check form-check-inline">
                <input type="radio" name="has_attr" id="no-attr" class="custom-control-input" value="0" v-model="has_attr">
                <label for="no-attr" class="custom-control-label">အမျိုးအစားကွဲ မရှိပါ</label>
            </div>
            <div class="form-check form-check-inline">
                <input type="radio" name="has_attr" id="has-attr" class="custom-control-input" value="1" v-model="has_attr">
                <label for="has-attr" class="custom-control-label">အမျိုးအစားကွဲ ရှိသည် (အရောင်၊ ဆိုဒ်၊ ပမာဏ)</label>
            </div>
        </div>
        <template v-if="has_attr === 0 || has_attr == '0' || (!data_has_attribute && has_sku)">
            <single-sku :item_id="item_id" @change-attribute="changeAttribute"></single-sku>
        </template>
        <template v-else-if="has_attr == 1 || (data_has_attribute || has_sku)">
            <multi-sku :item_id="item_id" :statuses="statuses" @change-attribute="changeAttribute"></multi-sku>
        </template>

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
            data_has_attribute: this.has_attribute
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
        }
    }
}
</script>
