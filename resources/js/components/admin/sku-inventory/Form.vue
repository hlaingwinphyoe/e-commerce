<template>
    <div class="row">
        <div class="col-md-8 col-lg-5 py-2 mb-3">
            <div class="form-group">
                <label for="">
                    Item Name
                    <span class="text-danger">**</span>
                    <a
                        href="#item-name"
                        class="btn btn-sm btn-pill btn-secondary d-none"
                        data-bs-toggle="collapse"
                    >
                        <span class="small"><i class="fa fa-info"></i></span>
                    </a>
                    <div class="collapse" id="item-name">
                        <div class="alert alert-danger mt-1">
                            Anim pariatur cliche reprehenderit, enim eiusmod
                            high life accusamus terry richardson ad squid. Nihil
                            anim keffiyeh helvetica, craft beer labore wes
                            anderson cred nesciunt sapiente ea proident.
                        </div>
                    </div>
                </label>
                <small class="help-text text-muted"
                    >ပစ္စည်းအမည်ရိုက်ရှာပြီး ရွေးပါ။ မရှိသေးလျှင် item တွင်
                    အသစ်သွားထည့်ပါ။မထည့်၍မရပါ။</small
                >
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
                <div class="custom-result-data shadow" v-show="results.length">
                    <ul class="nav flex-column">
                        <li
                            class="nav-item border-bottom"
                            v-for="res in results"
                            :key="res.id"
                            :id="res.id"
                        >
                            <template v-if="res.skus.length > 1">
                                <span class="nav-link text-dark">{{
                                    res.name
                                }}</span>
                                <ul class="nav">
                                    <li
                                        v-for="sku in res.skus"
                                        :key="sku.id"
                                        :value="sku.id"
                                        class="nav-item"
                                    >
                                        <a
                                            href="#"
                                            class="
                                                nav-link
                                                pt-0
                                                pb-1
                                                text-secondary
                                            "
                                            @click.prevent="
                                                onSelectData(sku, res.name)
                                            "
                                            >{{ sku.data }}</a
                                        >
                                    </li>
                                </ul>
                            </template>
                            <template v-else-if="res.skus.length == 1">
                                <a
                                    href="#"
                                    class="small nav-link text-dark"
                                    @click.prevent="
                                        onSelectData(res.skus[0], res.name)
                                    "
                                    >{{ res.name }}</a
                                >
                            </template>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="form-group">
                <label for="">
                    Quantity
                    <span class="text-danger">**</span>
                </label>
                <small class="help-text text-muted"
                    >ပစ္စည်းအရေအတွက်ထည့်ပါ။ မထည့်၍မရပါ။</small
                >
                <input
                    type="number"
                    class="form-control form-control-sm"
                    placeholder="Quantity"
                    v-model="form.qty"
                />
            </div>

            <div class="form-group">
                <label for=""> Amount </label>
                <small class="help-text text-muted">ဝယ်စျေးထည့်ပါ။</small>
                <input
                    type="text"
                    class="form-control form-control-sm"
                    placeholder="Amount"
                    v-model="form.amount"
                />
            </div>

            <div class="form-group">
                <label for=""> Remark </label>
                <small class="help-text text-muted"
                    >မှတ်ချက် ထည့်လိုက ထည့်နိုင်ပါသည်။</small
                >
                <textarea
                    name="remark"
                    class="form-control"
                    rows="3"
                    v-model="form.remark"
                ></textarea>
            </div>

            <div class="from-group" v-show="canSave">
                <button
                    type="submit"
                    class="btn btn-sm btn-secondary"
                    @click.prevent="onAddSku"
                >
                    <small class="me-2"><i class="fas fa-save"></i></small>
                    <span>Save</span>
                </button>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        inventory: { required: true },
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
        };
    },
    created() {
        // var today = new Date();
        // var dd = today.toLocaleDateString("en-CA");
        // this.form.date = dd;
    },
    methods: {
        onEnterInput() {
            clearTimeout(this.timeout);
            if (this.name) {
                this.timeout = setTimeout(() => {
                    axios
                        .get(`/wapi/get-data`, {
                            params: { name: this.name, order: null },
                        })
                        .then((resp) => {
                            this.results = resp.data;
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
                            // this.onAddSku();
                            // this.onStoreInventory();
                        }
                    });
            }
        },
        onSelectData(data, item_name) {
            // console.log(data);
            this.form.sku = data.id;
            this.name = data.data
                ? item_name + " (" + data.data + ")"
                : item_name;
            this.results = [];
        },
        onAddSku() {
            axios
                .post(`/wapi/sku-inventory/${this.inventory.id}`, this.form)
                .then((resp) => {
                    this.$refs.name.focus();
                    this.$emit("on-add-sku", resp.data);
                    (this.name = ""),
                        (this.form.amount = ""),
                        (this.form.qty = ""),
                        (this.form.remark = "");
                });
        },
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
