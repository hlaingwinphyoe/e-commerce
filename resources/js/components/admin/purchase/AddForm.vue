<template>
    <div class="add-form-container">
        <div class="bg-white rounded shadow px-2 py-3">
            <h5 class="text-primary mb-3">Add Items</h5>

            <div class="row">
                <div class="col-md-4">
                    <div class="form-group mb-3 position-relative">
                        <label for="">
                            Item Name
                            <span class="text-danger">**</span>
                            <a
                                href="#item-name"
                                class="btn btn-sm btn-pill btn-secondary d-none"
                                data-bs-toggle="collapse"
                            >
                                <span class="small"
                                    ><i class="fa fa-info"></i
                                ></span>
                            </a>
                            <div class="collapse" id="item-name">
                                <div class="alert alert-danger mt-1">
                                    Anim pariatur cliche reprehenderit, enim
                                    eiusmod high life accusamus terry richardson
                                    ad squid. Nihil anim keffiyeh helvetica,
                                    craft beer labore wes anderson cred nesciunt
                                    sapiente ea proident.
                                </div>
                            </div>
                        </label>
                        <input
                            type="text"
                            class="form-control form-control-sm"
                            placeholder="Search or Scan"
                            v-model="name"
                            ref="name"
                            autofocus
                            @keydown="onEnterInput"
                            @change="onInputChange"
                        />
                        <div
                            class="results absolute-box bg-white shadow rounded mt-1"
                            v-show="results.length"
                        >
                            <ul class="nav flex-column">
                                <li
                                    class="nav-item border-bottom"
                                    v-for="res in results"
                                    :key="res.id"
                                    :id="res.id"
                                >
                                    
                                        <a
                                            href="#"
                                            class="small nav-link text-dark"
                                            @click.prevent="
                                                onSelectData(
                                                    res
                                                )
                                            "
                                            >{{ res.item_name }} {{ res.data ? '('+ res.data +')' : '' }}</a
                                        >
                                    
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group mb-3">
                        <label for="">
                            Quantity
                            <span class="text-danger">**</span>
                        </label>
                        <input
                            type="number"
                            class="form-control form-control-sm"
                            placeholder="Quantity"
                            v-model="form.qty"
                        />
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group mb-3">
                        <label for=""> Buy Price </label>
                        <input
                            type="text"
                            class="form-control form-control-sm"
                            placeholder="Amount"
                            v-model="form.amount"
                        />
                    </div>
                </div>
                <div class="col-md-2" v-if="sale_price">
                    <div class="form-group mb-3">
                        <label for=""> Sale Price </label>
                        <div><small class="text-white bg-secondary rounded px-1 py-0">{{ sale_price }}</small></div>
                    </div>
                </div>
                <div class="form-group mb-3" v-show="canSave">
                    <button
                        type="submit"
                        class="btn btn-sm btn-secondary"
                        @click.prevent="onAddSku"
                    >
                        <small class="me-2"
                            ><i class="fas fa-save"></i
                        ></small>
                        <span>Save</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        inventory: { required: true },
        skus: {required: true}
    },
    data() {
        return {
            name: "",
            results: [],
            timeout: "",
            form: {
                sku: "",
                amount: "",
                qty: "",
                remark: "",
            },
            sale_price: ""
        };
    },
    methods: {
        onEnterInput() {
            clearTimeout(this.timeout);
            if (this.name) {
                this.timeout = setTimeout(() => {
                    axios
                        .get(`/wapi/skus`, {
                            params: { q: this.name, order: null },
                        })
                        .then((resp) => {
                            // this.results = resp.data;
                            this.results = resp.data.filter(res => {
                                let sku = this.skus.some(s => s.id === res.id);
                                return sku ? '' : res;
                            });
                        });
                }, 300);
            } else {
                this.results = [];
            }
        },
        onInputChange() {
            if (this.name) {
                axios
                    .get(`/wapi/skus`, { params: { q: this.name } })
                    .then((resp) => {
                        if (resp.data.length == 1) {
                            this.form.sku = resp.data[0].id;
                            this.form.qty = 1;
                        }
                    });
            }
        },
        onSelectData(sku) {
            this.form.sku = sku.id;
            this.name = sku.data
                ? sku.item_name + " (" + sku.data + ")"
                : sku.item_name;
            this.results = [];
            this.form.amount = sku.buy_price;
            this.sale_price = sku.price;
        },
        onAddSku() {
            axios
                .post(`/wapi/sku-inventories/${this.inventory.id}`, this.form)
                .then((resp) => {
                    this.$refs.name.focus();
                    this.$emit("on-add-sku", resp.data);
                    this.clearForm();
                });
        },
        clearForm() {
            this.name = '';
            this.form.qty = '';
            this.form.sku = '';
            this.form.amount = '';
        }
    },
    computed: {
        canSave() {
            return this.form.sku && this.form.qty;
        },
    },
};
</script>

<style>
.custom-result-data {
    max-height: 200px;
    overflow: auto;
}
</style>
