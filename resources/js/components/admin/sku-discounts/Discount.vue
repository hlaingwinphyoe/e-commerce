<template>
    <div class="sku_discount-container">
        <discount-list v-show="data_discounts.length" :discounts="data_discounts" @on-update-discount="onUpdateDiscount" @on-destroy-discount="onDestroyDiscount"></discount-list>
        <add-form v-show="data_discounts.length == 0" :discountypes="discountypes" :roles="roles" :statuses="statuses" @on-add-discount="onAddDiscount"></add-form>
    </div>
</template>

<script>
import AddForm from './AddForm.vue';
import DiscountList from './DiscountList.vue';
export default {
    components: {
        "add-form" : AddForm,
        "discount-list" : DiscountList,
    },
    props: {
        roles: {required: true, default: () => []},
        discountypes : {required: true, default: () => []},
        statuses: {required: true, default: () => []},
        item_id: {required: true},
        discounts: {required: true, default: () => []}
    },
    data() {
        return {
            data_discounts: this.discounts.length ? this.discounts : [],
        }
    },
    methods: {
        onAddDiscount(data) {
            this.data_discounts.push(data);
        },
        onUpdateDiscount(data) {
            this.data_discounts = this.data_discounts.map(x => {
                return x.id == data.id ? data : x;
            });
        },
        onDestroyDiscount(data) {
            this.data_discounts = this.data_discounts.filter(x => {
                return x.id !== data.id;
            });
        }
    }
}
</script>