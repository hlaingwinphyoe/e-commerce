<template>
    <div class="payment-container">
        <ul class="nav">
            <li
                class="nav-item paymentype-item"
                :class="getClass(paymentype.id)"
                v-for="paymentype in paymentypes"
                :key="paymentype.id"
            >
                <a
                    href="#"
                    class="nav-link"
                    @click.prevent="onSelectPayment(paymentype.id)"
                    >{{ paymentype.name }}</a
                >
            </li>
        </ul>

        <div class="row py-2">
            <div class="col-8">
                <div class="form-group">
                    <input
                        type="text"
                        placeholder="Pay Amount"
                        class="form-control form-control-sm"
                        v-model="form.amount"
                        @change="onInputChange"
                    />
                    <small class="text-danger" v-show="is_exceed">Exceed Maximum</small>
                </div>
            </div>
            <div class="col-4">
                <button class="btn btn-sm btn-primary" @click.prevent="onMakePayment">Payment</button>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        order: {required: true}
    },
    data() {
        return {
            paymentypes: [],
            org_amt: 0,
            is_exceed: false,
            form: {
                order_id: this.order.id,
                paymentype_id: "",
                amount: 0
            }
        };
    },
    created() {
        axios
            .get(`/wapi/statuses`, { params: { type: "payment-type" } })
            .then(resp => {
                this.paymentypes = resp.data;
                this.form.paymentype_id = this.paymentypes.length
                    ? this.paymentypes[0].id
                    : "";
            });
        this.getBalance();
    },
    methods: {
        getClass(id) {
            return this.form.paymentype_id == id ? "selected" : "";
        },
        getBalance() {
            axios.get(`/wapi/get-balance/${this.order.id}`).then(resp => {
                this.form.amount = resp.data;
                this.org_amt = resp.data;
            });
        },
        onInputChange() {
            if(this.form.amount > this.org_amt) {
                this.form.amount = this.org_amt;
                this.is_exceed = true;
            }else {
                this.is_exceed = false;
            }
        },
        onSelectPayment(id) {
            this.form.paymentype_id = id;
        },
        onMakePayment() {
            axios.post(`/wapi/transactions`, this.form).then(resp => {
                this.getBalance();
                this.is_exceed = false;
            });
        }
    }
};
</script>

<style>
.paymentype-item {
    margin-bottom: 10px;
    margin-left: 5px;
    border: 1px solid rgba(86, 83, 210, 0.4);
}
.paymentype-item.selected {
    border: 2px solid #0c2aa0;
}
</style>
