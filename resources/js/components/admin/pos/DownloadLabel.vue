<template>
    <div class="download-label-container">
        <a :download="`${order.order_no}.jpeg`" id="download" :href="src"
            >Download</a
        >
        <div class="row">
            <div class="col-md-4">
                <div
                    :id="order.id"
                    class="text-center bg-white py-2 px-1 border"
                >
                    <div class="border-bottom d-flex">
                        <div class="w-25 d-flex align-items-center justify-content-center">
                            <img src="/images/logo.png" alt="logo">
                        </div>
                        <div class="w-75">
                            <h5 class="text-uppercase mb-0">Lucky Lion</h5>
                            <h6>USA Store</h6>
                            <p class="mb-0">www.luckylionmm.com</p>
                            <p class="">09 955017978, 09 677360114</p>
                            <p class="text-end">Date - {{ getDate }}</p>
                        </div>
                    </div>
                    <table class="table table-sm text-left border-0">
                        <tr>
                            <td width="150px">Name</td>
                            <td class="mm-font">: {{ order.customer ? order.customer.name : '' }}</td>
                        </tr>
                        <tr>
                            <td>Phone</td>
                            <td>: {{ order.customer ? order.customer.phone : '' }}</td>
                        </tr>
                        <tr>
                            <td>Address</td>
                            <td class="mm-font">: {{ order.customer ? order.customer.address : '' }}</td>
                        </tr>
                        <tr>
                            <td>Amount</td>
                            <td>
                                : {{ Number(order.price).toLocaleString() }} Ks
                            </td>
                        </tr>
                        <tr>
                            <td>Discount</td>
                            <td>
                                :
                                {{ Number(order.discount).toLocaleString() }} Ks
                            </td>
                        </tr>
                        <tr>
                            <td>Pay Amount</td>
                            <td>
                                : {{ Number(getPayAmount).toLocaleString() }} Ks
                            </td>
                        </tr>
                        <tr>
                            <td>Balance</td>
                            <td>
                                :
                                {{
                                    getBalance == 0
                                        ? "Paid"
                                        : Number(getBalance).toLocaleString() +
                                          " Ks"
                                }}
                            </td>
                        </tr>
                        <tr>
                            <td>Remark</td>
                            <td class="mm-font">: {{ order.customer ? order.customer.remark : '' }}</td>
                        </tr>
                    </table>

                    <p class="text-center border-top pt-2 mb-0">
                        ** Thanks for shopping with us **
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import moment from 'moment';
export default {
    props: {
        order: { required: true }
    },
    data() {
        return {
            src: "",
            data: this.order.data ? JSON.parse(this.order.data) : "",
            transactions: []
        };
    },
    mounted() {
        html2canvas(document.getElementById(this.order.id)).then(canvas => {
            this.src = canvas.toDataURL("image/jpeg", 0.9);
        });
    },
    created() {
        axios
            .get(`/wapi/transactions`, { params: { order_id: this.order.id } })
            .then(resp => {
                this.transactions = resp.data;
            });
    },
    computed: {
        getSubTotal() {
            return this.order.price - this.order.discount;
        },
        getPayAmount() {
            return this.transactions.reduce((total, x) => {
                return total + x.amount;
            }, 0);
        },
        getBalance() {
            return parseInt(this.getSubTotal) - parseInt(this.getPayAmount);
        },
        getDate() {
            return moment().format("D/M/Y");
        }
    }
};
</script>
