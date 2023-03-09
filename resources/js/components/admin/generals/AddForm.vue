<template>
    <div class="card">
        <div class="card-body mb-3">
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
                            <a
                                href="#"
                                class="small nav-link text-dark"
                                @click.prevent="
                                    onSelectData(res)
                                "
                                >{{ res.item_name }}</a
                            >
                        </li>
                    </ul>
                </div>
            </div>

            <div class="row">
                <div class="col-6">
                    <div class="form-group">
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

                <div class="col-6" v-if="inventory.type !== 'general'">
                     <div class="form-group">
                        <label for="">{{ inventory.type == 'return' ? 'Price' : 'Buy Price' }}</label>
                        <input
                            type="text"
                            class="form-control form-control-sm"
                            placeholder="Amount"
                            v-model="form.amount"
                        />
                    </div>
                </div>
            </div>



            <div class="from-group" >
                <button
                    type="submit"
                    class="btn btn-sm btn-secondary"
                    :class="canSave ? '' : 'disabled'"
                    @click.prevent="onAddSku"
                >
                    <small class="me-2"><i class="fas fa-save"></i></small>
                    <span>Add</span>
                </button>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        inventory: { required: true },
        skus: {required: true, default: () => []}
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
                            //console.log(resp.data)
                            this.results = resp.data.data.filter(res => {
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
            //for barcode input
        },
        // onSelectData(data) {
        //     this.form.item = data.id;
        //     this.name = data.name;
        //     this.results = [];
        //     this.form.amount = this.inventory.type == 'return' ? data.price : this.form.amount;
        // },

        onSelectData(sku) {
            this.form.sku = sku.id;
            this.name = sku.data
                ? sku.item_name + " (" + sku.data + ")"
                : sku.item_name;
            this.results = [];
            this.form.amount = this.inventory.type == 'return' ? data.price : this.form.amount;
        },

        onAddSku() {
            axios.post(`/wapi/general-skus/${this.inventory.id}`, this.form).then((resp) => {
                this.$emit("on-add-sku", resp.data);
                this.$refs.name.focus();
                this.clearForm();
            });
        },

        clearForm() {
            this.name = "";
            this.form.sku = '';
            this.form.amount = '';
            this.form.qty = '';
            this.form.remark = '';
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
