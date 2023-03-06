<template>
    <div class="sku_form-container">
        <p class="fw-bold text-primary">ဈေးနှုန်း သတ်မှတ်ရန် Form</p>
        <div class="row">
            <div class="form-group col-md-4">
                <label for="" class="small text-muted">ဈေးနှုန်း</label>
                <span v-show="form.buy_price" class="bg-light ml-2 px-2 py-1">
                    {{ form.buy_price }} MMK
                </span>
                <input
                    type="text"
                    class="form-control form-control-sm"
                    placeholder="Price"
                    v-model="form.price"
                />
            </div>

            <div class="form-group col-md-2 align-self-end">
                <button class="btn btn-sm btn-primary" @click.prevent="onAddSku">
                    <i class="fa fa-save me-2"></i>
                    <span>Add</span>
                </button>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        item_id: { required: true },
    },
    data() {
        return {
            exchange_rate: 1,
            form: {
                price: 0,
                item_id: this.item_id,
            }
        };
    },
    created() {
        // let params = {
        //     params: {
        //         currency : 'usd'
        //     }
        // };
        // axios.get(`/wapi/exchangerates/get-latest`, params).then(resp => {
        //     this.exchange_rate = resp.data;
        // });
    },
    methods: {
        onAddSku() {
            axios.post(`/wapi/single-skus`, this.form).then(resp => {
                this.form.price = 0;
                this.$emit("on-add-pricing", resp.data);
            });
        }
    },
    computed: {
        getEstimate() {
            let price = this.exchange_rate * this.form.price;

            let mod = price % 1000;

            if(mod > 500 || mod < 500) {
                price = Math.round(price / 1000) * 1000;
            }

            return new Intl.NumberFormat().format(price);
        }
    }
};
</script>
