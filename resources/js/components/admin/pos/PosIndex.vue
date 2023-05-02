<template>
    <div class="pos-container">
        <div class="row">
            <!-- Find and add items -->
            <div class="col-md-7 col-lg-7 left-sidebar">
                <div class="px-2 py-3">
                    <a href="/admin/pos" class="btn btn-sm btn-secondary">
                        <i class="fa fa-arrow-left"></i>
                        <span>Return to Sale Lists</span>
                    </a>
                </div>
                <div class="px-2 mb-3 mt-2">
                    <div
                        class="disabled-container"
                        :class="isSale ? 'disabled' : ''"
                    >
                        <search-bar
                            :order="data_order"
                            @on-add-sku="onAddSku"
                        ></search-bar>
                    </div>
                </div>
            </div>

            <!-- Payment Actions -->
            <div class="col-md-5 col-lg-5 right-sidebar items">
                <div class="pos-left px-2 py-3 bg-sidebar border rounded">
                    <div class="position-relative">
                        <!-- Customer -->
                        <div class="row">
                            <div class="col-md-6">
                                <customer :order="data_order" @on-save-customer="onSaveCustomer"></customer>
                            </div>
                        </div>
                    </div>

                    <!-- Content List -->
                    <div
                        class="disabled-container mb-1 pt-3"
                        :class="isSale ? 'disabled' : ''"
                    >
                        <h5 class="fw-bold">
                            <span>Receipt Lists</span>
                            <span v-show="cus_name" class="ms-2"
                                >( {{ cus_name }} )</span
                            >
                        </h5>

                        <div
                            class="table-responsive bg-white border rounded"
                            v-show="data_skus.length"
                        >
                            <table class="table mb-0">
                                <thead class="bg-white">
                                    <tr>
                                        <th width="300px">Name</th>
                                        <th class="">Qty</th>
                                        <th class="text-end" width="200px">
                                            Amount
                                        </th>
                                        <th width="30px">
                                            <small
                                                ><i class="fa fa-trash"></i
                                            ></small>
                                        </th>
                                    </tr>
                                </thead>
                                <sku-list
                                    :skus="data_skus"
                                    @on-update-sku="onUpdateSku"
                                    @on-delete-sku="onDeleteSku"
                                ></sku-list>
                                <tfoot class="tfoot-small">
                                    <tr>
                                        <th colspan="" class="text-end">
                                            Total
                                        </th>
                                        <th class="text-center">
                                            {{
                                                Number(
                                                    getTotalQty
                                                ).toLocaleString()
                                            }}
                                        </th>
                                        <th class="text-end text-success">
                                            {{
                                                Number(
                                                    getTotal
                                                ).toLocaleString()
                                            }}
                                        </th>
                                        <th></th>
                                    </tr>
                                    <tr>
                                        <th colspan="" class="text-end">
                                            Deli Fee
                                        </th>
                                        <th></th>
                                        <th class="text-end text-success">
                                            <input
                                                type="text"
                                                class="form-control form-control-sm text-end fw-bold text-success"
                                                v-model="deli_fee"
                                                @change="onAddDeliFee"
                                            />
                                            <span class="d-none">{{
                                                Number(
                                                    data_order.deli_fee
                                                ).toLocaleString()
                                            }}</span>
                                        </th>
                                        <th></th>
                                    </tr>
                                    <tr>
                                        <th colspan="" class="text-end">
                                            Discount
                                        </th>
                                        <th>
                                            <div
                                                class="d-flex align-items-center"
                                            >
                                                <div class="form-group me-2">
                                                    <input
                                                        type="text"
                                                        class="form-control form-control-sm qty-input"
                                                        v-model="discount_amt"
                                                        @change="onAddDiscount"
                                                    />
                                                </div>

                                                <div
                                                    class="form-check me-2"
                                                    v-for="status in statuses"
                                                    :key="status.id"
                                                >
                                                    <input
                                                        class="form-check-input"
                                                        type="radio"
                                                        name="discount"
                                                        :id="status.id"
                                                        :value="status.id"
                                                        v-model="
                                                            discount_status
                                                        "
                                                        @change="onAddDiscount"
                                                    />
                                                    <label
                                                        class="form-check-label"
                                                        :for="status.id"
                                                    >
                                                        {{ status.name }}
                                                    </label>
                                                </div>
                                            </div>
                                        </th>
                                        <th class="text-end text-success">
                                            {{
                                                Number(
                                                    getDiscount
                                                ).toLocaleString()
                                            }}
                                        </th>
                                        <th></th>
                                    </tr>
                                    <tr>
                                        <th colspan="" class="text-end">
                                            Sub Total
                                        </th>
                                        <th
                                            colspan="2"
                                            class="text-end text-success"
                                        >
                                            {{
                                                Number(
                                                    getSubTotal
                                                ).toLocaleString()
                                            }}
                                        </th>
                                        <th></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>

                    <!-- Quick Buttons -->
                    <div class="py-4 mb-3">
                        <div>
                            <a
                                href="#payment-form"
                                data-bs-toggle="modal"
                                class="btn btn-outline-dark me-2 mb-2"
                            >
                                <i class="fa fa-lock"></i>
                                <p class="mb-0">Make Pay</p>
                            </a>
                            <!-- Pay Form -->
                            <div class="modal fade" id="payment-form">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5
                                                class="modal-title"
                                                id="exampleModalLabel"
                                            >
                                                Make Payment
                                            </h5>
                                            <button
                                                type="button"
                                                class="btn btn-close"
                                                data-bs-dismiss="modal"
                                            ></button>
                                        </div>
                                        <div class="modal-body">
                                            <h5 class="text-primary mb-3">Pay Amount - <span class="fw-bold">{{ getPayAmount }}</span></h5>
                                            <div class="form-group px-1">
                                                <label>To Pay Amount</label>
                                                <input type="text" v-model="form.amount" class="form-control form-control-sm" @change="onInputChange" />
                                                <small
                                                    class="text-danger"
                                                    v-show="is_exceed"
                                                    >Exceed Maximum</small
                                                >
                                            </div>
                                            <ul class="nav mt-3">
                                                <li
                                                    class="nav-item paymentype-item rounded bg-sidebar"
                                                    :class="
                                                        getClass(paymentype.id)
                                                    "
                                                    v-for="paymentype in paymentypes"
                                                    :key="paymentype.id"
                                                >
                                                    <a
                                                        href="#"
                                                        class="nav-link text-dark bg-white rounded"
                                                        @click.prevent="
                                                            onSelectPayment(
                                                                paymentype.id
                                                            )
                                                        "
                                                        >{{
                                                            paymentype.name
                                                        }}
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="modal-footer">
                                            <button
                                                @click.prevent="onMakePayment"
                                                class="btn btn-sm btn-primary"
                                            >
                                                Pay
                                            </button>
                                            <button
                                                type="button"
                                                class="btn btn-sm btn-secondary"
                                                data-bs-dismiss="modal"
                                            >
                                                Close
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <a
                                href="#"
                                class="btn btn-primary me-2 mb-2"
                                @click.prevent="onMakeSale"
                            >
                                <i class="fa fa-lock"></i>
                                <p class="mb-0">Save Sale</p>
                            </a>

                            <a
                                href="#"
                                target="_blank"
                                class="btn btn-outline-danger me-2 mb-2"
                                :class="isSale ? 'disabled' : ''"
                                @click.prevent="onCancelOrder"
                            >
                                <i class="fa fa-trash"></i>
                                <p class="mb-0">Void Order</p>
                            </a>

                            <a
                                :href="`/admin/pos-print/${order.id}`"
                                class="btn btn-dark me-2 mb-2"
                            >
                                <i class="fa fa-print"></i>
                                <p class="mb-0">Print Slip</p>
                            </a>

                            <a
                                :href="`/save-invoice/${order.id}`"
                                class="btn btn-info me-2 mb-2"
                            >
                                <i class="fa fa-receipt"></i>
                                <p class="mb-0">Save Invoice</p>
                            </a>

                            <a
                                :href="`/admin/pos/create?order_no=${order.id}`"
                                class="btn btn-outline-success me-2 mb-2"
                            >
                                <i class="fa fa-redo"></i>
                                <p class="mb-0">Refresh</p>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- added item count & show lists -->
        <div class="fixed-bottom d-desktop-none">
            <a href="#" class="btn btn-primary rounded-0 py-3 items-sidebar-btn items-sidebar-open">
                <span class="pr-2 float-left">{{ Number(getTotalQty).toLocaleString() }} Added</span>
                <i class="fa fa-eye fa-lg pr-2"></i>
                <span class="float-right">{{ Number(getTotal).toLocaleString() }} ks</span>
            </a>
            <a href="#" class="btn btn-primary items-sidebar-btn items-sidebar-close py-3 rounded-0">
                <i class="fa fa-arrow-left"></i>
                <span class="pr-3">Back</span>
            </a>
        </div>
    </div>
</template>

<script>
import SearchBar from "./SearchBar.vue";
import SkuList from "./skus/SkuList.vue";
import SkuItem from "./skus/SkuItem.vue";
import CustomerInfo from "./CustomerInfo.vue";
import Customer from "./Customer.vue";
import OrderSearch from "./OrderSearch.vue";
export default {
    components: {
        "search-bar": SearchBar,
        "sku-list": SkuList,
        "sku-item": SkuItem,
        "customer-info": CustomerInfo,
        "customer": Customer,
        "order-search": OrderSearch
    },
    props: {
        order: { required: true },
        skus: { required: true },
        statuses: { required: true },
    },
    data() {
        return {
            search: window.location.search,
            price_by_add_sku: "",
            data_skus: this.skus,
            isSale: this.order.status_id == 3,

            data_orders: [],
            data_order: this.order,
            deli_fee: this.order.deli_fee,
            discount_amt: this.order.discount_amt,
            discount_status: this.order.discount_status ?? this.statuses[0].id,
            timeout: "",

            //for payments
            paymentypes: [],
            is_exceed: false,
            form: {
                order_id: this.order.id,
                paymentype_id: "",
                amount: this.getBalance,
            },
            cus_name: this.order.customer.name,
        };
    },
    created() {
        axios
            .get(`/wapi/statuses`, { params: { type: "payment-type" } })
            .then((resp) => {
                // console.log(resp.data)
                this.paymentypes = resp.data;
                this.form.paymentype_id = this.paymentypes.length
                    ? this.paymentypes[0].id
                    : "";
            });
            this.form.amount = this.getBalance;
    },
    methods: {
        onAddSku(data) {
            this.data_skus = data;
            this.price_by_add_sku = this.getTotal;
            this.order.price = this.getTotal;
            this.form.amount = this.getBalance;
        },
        onUpdateSku(data) {
            //console.log("here");
            this.data_skus = data;
            this.order.price = this.getTotal;
        },
        onDeleteSku(data) {
            this.data_skus = data;
            this.order.price = this.getTotal;
        },
        onMakeSale() {
            let form = {
                status: "completed",
            };
            axios.patch(`/wapi/orders/${this.order.id}`, form).then((resp) => {
                this.isSale = true;
                window.location = `/admin/pos-print/${this.order.id}`;
            });
        },
        onCancelOrder() {
            axios.patch(`/wapi/cancel-orders/${this.order.id}`).then((resp) => {
                window.location = `/admin/pos/create`;
            });
        },
        onAddDiscount() {
            let data = {
                amt: this.discount_amt,
                status_id: this.discount_status,
            };
            axios
                .post(`/wapi/order-discount/${this.order.id}`, data)
                .then((resp) => {
                    //console.log(resp.data);
                    this.data_order = resp.data;
                    this.form.amount = this.getBalance;
                });
        },
        onAddDeliFee() {
            let data = {
                deli_fee: this.deli_fee,
            };
            axios
                .patch(`/wapi/order-deli-fee/${this.order.id}`, data)
                .then((resp) => {
                    this.deli_fee = resp.data.deli_fee;
                    this.data_order = resp.data;
                    this.form.amount = this.getBalance;
                });
        },
        getClass(id) {
            return this.form.paymentype_id == id ? "selected" : "";
        },
        onInputChange() {
            if (Number(this.form.amount) > this.getBalance) {
                this.form.amount = this.getBalance;
                this.is_exceed = true;
            } else {
                this.is_exceed = false;
            }
        },
        onSelectPayment(id) {
            this.form.paymentype_id = id;
            // this.onMakePayment();
        },
        onMakePayment() {
            axios.post(`/wapi/transactions`, this.form).then((resp) => {
                this.data_order = resp.data;
                this.form.amount = this.getBalance;
                this.is_exceed = false;
                this.isSale = resp.data.status_id == 3 ? true : false;
                if (resp.data.status_id == 3) {
                    window.location = `/admin/pos-print/${this.order.id}`;
                }
            });
        },
        onSaveCustomer(data) {
            this.cus_name = data.customer.name;
            window.scrollTo({ top: 0, behavior: "smooth" });
        },
    },
    computed: {
        getTotalQty() {
            return this.data_skus.reduce((total, x) => {
                return total + x.pivot.qty;
            }, 0);
        },
        getTotal() {
            let total = this.data_skus.reduce((total, x) => {
                return total + x.pivot.qty * x.pivot.price;
            }, 0);
            return total;
        },
        getDiscount() {
            // 23 %, 22 fixed
            return this.data_order.discount_status == 23
                ? (this.data_order.price * this.data_order.discount_amt) / 100
                : this.data_order.discount_amt;
        },
        getSubTotal() {
            return this.getTotal - this.getDiscount + parseFloat(this.deli_fee);
        },
        getPayAmount() {
            return this.data_order.transactions.reduce((total, x) => {
                return x.status.slug == "in" ? total + x.amount : total;
            }, 0);
        },
        getChange() {
            return this.data_order.transactions.reduce((total, x) => {
                return x.status.slug == "out" ? total + x.amount : total;
            }, 0);
        },
        getBalance() {
            var balance = this.getSubTotal - this.getPayAmount + this.getChange;
            this.form.amount = balance;
            return balance.toFixed(2);
        },
    },
};
</script>

<style>
.pos-container {
    font-size: 0.85rem;
}
.disabled-container {
    position: relative;
}
.disabled-container::after {
    content: "";
    position: absolute;
    width: 100%;
    height: 100%;
    top: 0;
    left: 0;
    z-index: -1;
}
.disabled-container.disabled::after {
    background: rgba(0, 0, 0, 0.1);
    z-index: 1;
}
.result-data {
    max-height: 200px;
    overflow: auto;
    position: absolute;
    z-index: 1;
}
.cus-info {
    display: none;
}
.cus-info.show {
    display: block;
}
.pos-left {
    min-height: 100vh;
}

/* mobile items sidebar */
@media (max-width: 576px){
    .left-sidebar{
        min-width: 100%;
        overflow: auto;
        z-index: 10;
        transition: all 0.3s ease;
        min-height: calc(100vh - 50px);
        background-color: #fff;
    }
    .right-sidebar {
        left: -100%;
        position: fixed;
        top: 0;
        bottom: 0;
        width: 100%;
        overflow: auto;
        z-index: 10;
        background-color: #f8f8fb;
        transition: all 0.3s ease;
    }
    .pos-left{
        padding: 0.5rem;
    }
    .row{
        --bs-gutter-x: 0;
    }
    .items.items-sidenav-toggled {
        left: 0;
        padding-bottom: 1rem;
    }
}

.items-sidebar-btn{
    width: 100%;
}

.float-left{
    float: left;
}
.float-right{
    float: right;
}
.pay-form{
    margin: 0 auto;
    float: right;
}
</style>
