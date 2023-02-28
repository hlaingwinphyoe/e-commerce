<template>
    <div>
        <div class="search-bar-container search-bar mb-2 p-2">
            <div class="row">
                <div class="col-7 col-md-3 pe-1">
                    <div class="me-2 mb-2">
                        <select
                            class="form-select"
                            v-model="type"
                            @change="onSelectCategory"
                        >
                            <option value="">All Items</option>
                            <option
                                v-for="type in types"
                                :key="type.id"
                                :value="type.id"
                            >
                                {{ type.name }}
                            </option>
                        </select>
                    </div>
                </div>

                <!-- Search Bar -->
                <div class="col-5 col-md-5 ps-1">
                    <div class="input-group mb-3">
                        <span class="input-group-text bg-primary text-white" id="basic-addon1"><i class="fa-solid fa-search"></i></span>
                        <input
                            type="text"
                            class="form-control form-control-sm"
                            placeholder="Type here to search"
                            v-model="q"
                            ref="search"
                            @keyup="onSearch"
                            @change="onChange"
                            id="search"
                        />
                    </div>
                </div>

                <div class="col-8 col-md-2 d-none">
                    <div class="me-2 mb-2">
                        <label class="text-muted">Qty</label>
                        <input
                            type="text"
                            class="form-control form-control-sm"
                            placeholder="Qty"
                            v-model="form.qty"
                        />
                    </div>
                </div>
                <div class="col-4 col-md-2 d-none">
                    <div class="me-2 mb-2">
                        <label class="text-muted">.</label>
                        <div>
                            <a
                                href="#"
                                class="btn btn-sm btn-primary"
                                :class="getButtonClass"
                                @click.prevent="onAddSku"
                            >
                                <span><i class="fa fa-plus"></i></span>
                                <span>Add</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Results -->
        <div class="rounded smooth-scroll px-2 py-3 position-relative">
            <div class="text-center" v-show="!loaded">
                <div class="spinner-border text-primary position-absolute" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
<!--            <pre>{{ data_skus }}</pre>-->
            <!-- new design -->
            <transition name="switch" mode="out-in">
                <div v-if="data_skus.total > 0">
                    <transition-group tag="div" name="fade" appear class="row mobile-scroll">
                        <div
                            class="col-6 col-md-3"
                            v-for="res in data_skus.data"
                            :key="res.id"
                            :res="res"
                        >
                            <result-item
                                :res="res"
                                :order_id="order.id"
                                @on-selected-sku="onSelectedSku"
                            ></result-item>
                        </div>
                        <div class="col-12 text-center" v-if="page < data_skus.last_page">
                            <a
                                href="#"
                                class="btn btn-sm btn-outline-primary"
                                @click.prevent="onLoadMore"
                            >Load More
                            </a>
                        </div>
                    </transition-group>
                </div>
                 <div v-else>
                    <div class="d-flex justify-content-center">
                        <img src="../../../../../public/images/no-data-animate.svg" width="450" alt="">
                    </div>
                </div>
            </transition>
        </div>
    </div>
</template>

<script>
import ResultItem from "./ResultItem.vue";
import {throttle} from "lodash";
export default {
    components: {
        "result-item": ResultItem,
    },
    props: {
        order: { required: true },
    },
    data() {
        return {
            loaded: false,
            maintypes: [],
            types: [],
            type: "",
            data_types: [],
            maintype: "",
            q: "",
            isClose: false,
            form: {
                sku: "",
                qty: 1,
                price: 0,
            },
            page: 1,
            data_skus: [],
            popular_data: [],
            timeout: "",
        };
    },
    created() {
        axios.get(`/wapi/types`).then((resp) => {
            this.types = resp.data;
        });
        this.getPopularData();
    },
    methods: {
        getPopularData() {
            axios.get(`/wapi/popular-skus`).then((resp) => {
                this.popular_data = resp.data;
                this.data_skus = resp.data;
                //console.log(this.data_skus.data[0].id)
                this.loaded = true;
            });
        },
        onSelectCategory() {
            this.loaded = false;
            if(this.type === ""){
                axios.get(`/wapi/popular-skus`).then((resp) => {
                    this.popular_data = resp.data;
                    this.data_skus = resp.data;
                    this.loaded = true;
                });
            }else{
                axios.get(`/wapi/type-skus/${this.type}`).then((resp) => {
                    this.data_skus = resp.data;
                    this.loaded = true;
                });
            }
        },
        onLoadMore() {
            this.loaded = false;
            this.page += 1;
            axios
                .get(`${this.data_skus.path}`, {
                    params: { page: this.page, q: this.q },
                })
                .then((resp) => {
                    resp.data.data.map((x) => {
                        this.data_skus.data.push(x);
                    });
                    this.loaded = true;
                });
        },
        onCloseSearch() {
            this.isClose = true;
            // this.data_skus = this.popular_data;
            this.data_skus = [];
            this.q = "";
        },
        onChangeMainType(e) {
            this.data_types = this.types.filter((x) => {
                let ary = x.maintypes.filter((maintype) => {
                    return maintype.id === e.target.value;
                });
                return ary.length ? x : "";
            });
        },

        onSearch:throttle(function () {
            if (this.q) {
                clearTimeout(this.timeout);
                this.timeout = setTimeout(() => {
                    let param = {
                        q: this.q,
                        maintype: this.maintype,
                        type: this.type,
                    };
                    this.loaded = false;
                    axios.get(`/wapi/skus`, { params: param }).then((resp) => {
                        this.data_skus = resp.data;
                        //console.log(this.data_skus)
                        this.loaded = true;
                        this.data_skus.data.filter(sku => {
                            //console.log(sku)
                            //console.log(sku.item_name.toLowerCase().includes(this.q.toLowerCase()))

                            // return မပြန်လည်းအဆင်ပြေတယ်
                            return sku.item_name.toLowerCase().includes(this.q.toLowerCase()) ? sku : '';
                        })
                    });
                }, 300);
            } else {
                this.data_skus = this.popular_data;
                this.loaded = true;
            }
        },500),

        onChange() {
            if (this.isClose) {
                axios
                    .get(`/wapi/skus`, { params: { q: this.q } })
                    .then((resp) => {
                        if (
                            resp.data.length === 1 &&
                            resp.data.data[0].stock > 0
                        ) {
                            this.form.sku = resp.data[0].id;
                            this.form.price = resp.data[0].discount
                                ? resp.data[0].discount
                                : resp.data[0].price;
                            this.data_skus = this.popular_data;
                            axios
                                .post(
                                    `/wapi/order-skus/${this.order.id}`,
                                    this.form
                                )
                                .then((resp) => {
                                    this.q = "";
                                    this.form.sku = "";
                                    this.form.price = "";
                                    this.form.qty = 1;
                                    this.$refs.search.focus();
                                    this.$emit("on-add-sku", resp.data);
                                });
                        } else {
                            this.data_skus = resp.data;
                        }
                    });
            }
        },

        onSelectedSku(data) {
            // this.data_skus = this.popular_data;
            this.form.qty = 1;
            this.$refs.search.focus();
            this.$emit("on-add-sku", data);
        },
        onAddSku() {
            axios
                .post(`/wapi/order-skus/${this.order.id}`, this.form)
                .then((resp) => {
                    // this.q = "";
                    // this.form.sku = "";
                    // this.form.price = "";
                    this.form.qty = 1;
                    this.$refs.search.focus();
                    this.$emit("on-add-sku", resp.data);
                });
        },
    },
    computed: {
        getButtonClass() {
            return this.form.sku ? "" : "disabled";
        },
        getStock(data) {
            console.log(data);
            return 1;
        },
    },
};
</script>

<style>
.result-data {
    max-height: 400px;
    overflow: auto;
    position: absolute;
    z-index: 1;
}
.nav-link.disabled {
    background-color: #c9c9c9 !important;
}

@media (max-width: 576px){
    .mobile-scroll{
        overflow: scroll;
        max-height: 480px;
    }
}
</style>
