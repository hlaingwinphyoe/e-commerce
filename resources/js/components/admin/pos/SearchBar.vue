<template>
    <div>
        <div class="search-bar-container search-bar bg-sidebar rounded border mb-3 px-2 py-3">
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
                <div class="col-5 col-md-5 ps-1">
                    <div class="input-group mb-3">
                        <span class="input-group-text" id="basic-addon1"><i class="fa-solid fa-search"></i></span>
                        <input
                            type="text"
                            class="form-control form-control-sm"
                            placeholder="Type here to search"
                            v-model="q"
                            ref="search"
                            @keydown="onSearch"
                            @change="onChange"
                            autofocus id="search"
                        />
                    </div>
                </div>
                <div class="col-8 col-md-2 d-none">
                    <div class="me-2 mb-2">
                        <label for="" class="text-muted">Qty</label>
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
                        <label for="" calss="text-muted">.</label>
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
            <div v-show="!loaded" class="loading">
                <span>Loading Data...</span>
            </div>
            <!-- new design -->
            <div class="row mobile-scroll">
                <div
                    class="col-6 col-md-4"
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
                        >Load More</a
                    >
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import ResultItem from "./ResultItem.vue";
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
            // maintype: "",
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
                this.loaded = true;
            });
        },
        onSelectCategory() {
            this.loaded = false;
            if(this.type == ""){
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
                    return maintype.id == e.target.value;
                });
                return ary.length ? x : "";
            });
        },
        onSearch() {
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
                        this.data_skus.data = resp.data;
                        this.loaded = true;
                    });
                }, 300);
            } else {
                this.data_skus = this.popular_data;
                this.loaded = true;
            }
        },
        onChange() {
            if (this.isClose) {
                axios
                    .get(`/wapi/skus`, { params: { q: this.q } })
                    .then((resp) => {
                        if (
                            resp.data.length == 1 &&
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
        // onSelectedSku(id, name, price) {
        //     this.q = name;
        //     this.form.sku = id;
        //     this.form.price = price;
        //     this.data_skus = this.popular_data;
        //     this.onAddSku();
        // },
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
.loading {
    position: absolute;
    width: 100%;
    height: 100%;
    top: 0;
    left: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-weight: bold;
    background-color: rgba(0, 0, 0, 0.3);
}
.result-data {
    max-height: 400px;
    overflow: auto;
    position: absolute;
    z-index: 1;
}
.featured-img {
    max-width: 100%;
    max-height: 100px;
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
