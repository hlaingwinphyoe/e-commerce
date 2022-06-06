<template>
    <div class="modal fade" :ref="`payment-modal-${order.id}`" :id="`payment-modal-${order.id}`">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-sidebar">
                    <h5 class="modal-title" id="exampleModalLabel">
                        Make Payment
                    </h5>
                    <button
                        type="button"
                        class="btn btn-sm btn-outline-danger"
                        data-bs-dismiss="modal"
                    >
                        <i class="fa fa-times"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <h5 class="text-primary mb-3">
                        Pay Amount -
                        <span
                            class="fw-bold"
                            >{{ pay_amount }}</span
                        >
                    </h5>
                    <div class="form-group px-1">
                        <label>To Pay Amount</label>
                        <input
                            type="text"
                            v-model="form.amount"
                            class="form-control form-control-sm"
                            @change="onInputChange"
                        />
                        <small class="text-danger" v-show="is_exceed"
                            >Exceed Maximum</small
                        >
                    </div>
                    <ul class="nav mt-3">
                        <li
                            class="nav-item paymentype-item rounded bg-sidebar"
                            :class="getClass(paymentype.id)"
                            v-for="paymentype in paymentypes"
                            :key="paymentype.id"
                        >
                            <a
                                href="#"
                                class="nav-link text-dark"
                                @click.prevent="onSelectPayment(paymentype.id)"
                                >{{ paymentype.name }}</a
                            >
                        </li>
                    </ul>
                </div>
                <div class="modal-footer bg-sidebar">
                    <button
                        type="button"
                        class="btn btn-sm btn-outline-secondary"
                        data-bs-dismiss="modal"
                    >
                        Close
                    </button>
                </div>
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
            pay_amount: '',
            form: {
                order_id: this.order.id,
                paymentype_id: "",
                amount: this.getBalance,
            },
            paymentypes: [],
            is_exceed: false,
        }
    },
    created() {
        axios
            .get(`/wapi/statuses`, { params: { type: "payment-type" } })
            .then((resp) => {
                this.paymentypes = resp.data;
                this.form.paymentype_id = this.paymentypes.length
                    ? this.paymentypes[0].id
                    : "";
            });

        this.getParams();
    },
    methods: {
        getParams() {
            axios.get(`/wapi/get-payment-params/${this.order.id}`).then(resp => {
                this.form.amount = resp.data.balance;
                this.pay_amount = resp.data.pay_amount;
            });
        },
        getClass(id) {
            return this.form.paymentype_id == id ? "selected" : "";
        },
        onSelectPayment(id) {
            this.form.paymentype_id = id;
            this.onMakePayment();
        },
        onMakePayment() {
            axios.post(`/wapi/transactions`, this.form).then((resp) => {
                this.data_order = resp.data;
                this.getParams();
                this.is_exceed = false;
                if(this.form.amount <= 0) {
                    window.reload();
                }
            });
        },
    }
}
</script>
