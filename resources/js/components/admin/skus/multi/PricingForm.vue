<template>
    <div class="pricing_form-container mb-2 bg-sidebar px-2 py-1">
        <p class="mb-0 py-2 small fw-bold">SKUs အားလုံးအတွက် ဈေးထည့်မည်။ ဈေးမတူလျှင် တခုချင်း၌ ပြန်လည်ပြင်ပါ။</p>
        <div class="row">
            <div class="form-group col-md-2">
                <label for="" class="small text-muted">အနည်းဆုံး အရေအတွက်</label>
                <input type="number" class="form-control form-control-sm" v-model="form.min_qty" required>
            </div>

            <div class="form-group col-md-2">
                <label for="" class="small text-muted">အများဆုံး အရေအတွက်</label>
                <input type="number" class="form-control form-control-sm" v-model="form.max_qty">
            </div>
            <div class="col-md-2 form-group">
                <label for="" class="small text-muted">ဈေးနှုန်း</label>
                <input
                    type="text"
                    placeholder="Price"
                    class="form-control form-control-sm"
                    v-model="form.price"
                />
            </div>
            <div class="col-md-6 form-group align-self-end">
                <button
                    class="btn btn-sm btn-outline-secondary"
                    @click.prevent="onAddPrice"
                >
                    Add/Update Price
                </button>
                <a
                    :href="`/admin/items/${item_id}/edit`"
                    class="btn btn-sm btn-primary ms-2"
                >
                    <i class="fa fa-redo"></i>
                </a>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        item_id: { required: true }
    },
    data() {
        return {
            exchange_rate: 1,
            form: {
                min_qty: 1,
                max_qty: 1,
                price: ""
            }
        };
    },
    created() {
        // let params = {
        //     params: {
        //         currency: "usd"
        //     }
        // };
        // axios.get(`/wapi/exchangerates/get-latest`, params).then(resp => {
        //     this.exchange_rate = resp.data;
        // });
    },
    methods: {
        onAddPrice() {
            axios
                .post(`/wapi/item-pricings/${this.item_id}`, this.form)
                .then(resp => {
                    window.location.reload();
                    // this.form.price = "";
                    // this.form.min_qty = 1;
                    // this.form.max_qty = 1;
                    // this.$emit("on-add-pricing", resp.data);
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
