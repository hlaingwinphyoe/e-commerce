<template>
    <div class="position-relative">
        <div class="input-group mb-2">
            <input
                type="text"
                class="form-control form-control-sm"
                placeholder="Search Order No."
                v-model="search_form.order_no"
                @keydown="onSearchOrder"
                @focus="show_results=true"
            />
            <button
                class="btn btn-sm btn-secondary border-left-0 rounded-left-0"
                @click.prevent="onCreateOrder"
            >
                <i class="fa fa-search"></i>
            </button>
        </div>
        <div
            class="result-data bg-white shadow p-2 w-100"
            v-show="data_orders.length && (show_results || search_form.order_no != '')"
        >
            <ul class="nav flex-column">
                <li class="nav-item text-end">
                    <a href="#" class="text-danger" @click.prevent="show_results=false"><i class="fa fa-times"></i></a>
                </li>
                <li
                    class="nav-item"
                    v-for="order in data_orders"
                    :key="order.order_no"
                    :id="order.order_no"
                >
                    <a
                        href="#"
                        class="nav-link py-1 text-muted"
                        @click.prevent="onSelectedOrder(order.order_no)"
                        >{{ order.order_no }}
                    </a>
                </li>
            </ul>
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
            show_results: false,
            timeout: '',
            search_form: {
                order_no: ''
            },
            data_orders: [],
        }
    },
    created() {
        axios.get(`/wapi/get-orders`, {params: {latest: 'latest', order_no: this.order.order_no }}).then(resp => {
            this.data_orders = resp.data;
        });
    },
    methods: {
        onSearchOrder() {
            if (this.search_form.order_no) {
                clearTimeout(this.timeout);
                this.timeout = setTimeout(() => {
                    axios
                        .get(`/wapi/get-orders`, { params: this.search_form })
                        .then(resp => {
                            this.data_orders = resp.data;
                        });
                }, 100);
            } else {
                this.data_orders = [];
            }
        },
        onSelectedOrder(order_no) {
            this.search_form.order_no = order_no;
            this.data_orders = [];
            window.location = `/admin/pos/create?order_no=${this.search_form.order_no}`;
        },
        onCreateOrder() {
            window.location = `/admin/pos/create?order_no=${this.search_form.order_no}`;
        },
    }
};
</script>

<style>
.result-data {
    max-height: 200px;
    overflow: auto;
    position: absolute;
    z-index: 1;
}
</style>
