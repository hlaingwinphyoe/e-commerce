<template>
    <div class="bg-white border rounded px-2 py-3 mb-4">
        <h6 class="text-primary">Add More Items</h6>
        <div class="row">
             <div class="col-md-4 mb-2">
                    <div class="form-group position-relative">
                        <label class="text-muted">
                            Item Name
                            <span class="text-danger">**</span>
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

                <div class="col-md-2 mb-2">
                    <label class="text-muted">Qty</label>
                    <input type="text" class="form-control form-control-sm" v-model="form.qty"/>
                </div>
                <div class="col-md-2 mb-2">
                    <label class="text-muted">Sale Price</label>
                    <input type="text" class="form-control form-control-sm" v-model="form.price"/>
                </div>
                <div class="col-md-2 mb-2 align-self-end">
                    <div class="form-group">
                        <button type="button" class="btn btn-sm btn-primary" @click.prevent="onAddSku">Save</button>
                    </div>
                </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        order: {required: true,}
    },
    data() {
        return {
            name: '',
            results: [],
            form: {
                sku: '',
                qty: 1,
                price: '',
            }
        }
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
                            this.results = resp.data;
                        });
                }, 300);
            } else {
                this.results = [];
            }
        },
        onSelectData(sku) {
            this.form.sku = sku.id;
            this.name = sku.item_name;
            this.name += sku.data ? '('+ sku.data +')' : '';
            this.results = [];
            this.form.price = sku.price;
            this.form.qty = 1;
        },
        onAddSku() {
            axios.post(`/wapi/order-skus/${this.order.id}`, this.form).then(resp => {
                this.$emit('on-add-sku', resp.data);
            });
        }
    }
}
</script>