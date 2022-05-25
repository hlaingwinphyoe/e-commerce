<template>
    <div class="multi_sku-container">
        <!-- Already Created Skus -->
        <template v-if="skus.length">
            <pricing-form
                :item_id="item_id"
                @on-add-pricing="onAddPricing"
            ></pricing-form>
            <!-- Add Attribute Form -->
            <div class="row">
                <div class="col-md-4">
                    <div
                        class="border py-3 px-4 mb-3 bg-sidebar position-relative mb-3"
                    >
                        <p class="mb-2 fw-bold text-primary">Attribute နောက်တခုထည့်မည်</p>
                        <ul class="nav mb-2">
                            <template v-for="attr in attributes">
                                <li :key="attr.id"  v-if="!attr.values.length" class="nav-item border mb-1 me-2">
                                    <a href="#" class="nav-link py-1" @click.prevent="onRemoveAttr(attr.id)">
                                        <span>{{ attr.name }}</span>
                                        <span class="small ms-2 text-danger" ><i class="fa fa-times"></i></span>
                                    </a>
                                </li>
                            </template>
                            
                        </ul>
                        <div class="row mb-2">
                            <div class="col-8 col-md-6">
                                <select
                                    class="form-select"
                                    @change="onChangeNewAttr"
                                    v-model="attr_name"
                                >
                                    <option value="">Choose Title</option>
                                    <option
                                        v-for="status in statuses"
                                        :key="status"
                                        :value="status"
                                        >{{ status }}</option
                                    >
                                    <option value="other">Others</option>
                                </select>
                            </div>
                            <div
                                class="col-4 col-md-4 form-group"
                                v-show="show_attr_form"
                            >
                                <input
                                    type="text"
                                    class="form-control form-control-sm"
                                    v-model="attr_name"
                                    placeholder="Attribute name"
                                />
                            </div>
                            <div class="col-2 col-md-2">
                                <button
                                    class="btn btn-sm btn-outline-primary"
                                    :class="
                                        attr_name == '' &&
                                        attr_name != 'other'
                                            ? 'disabled'
                                            : ''
                                    "
                                    @click.prevent="onAddNewAttr"
                                >
                                    <small><i class="fa fa-check"></i></small>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <sku-list
                :skus="skus"
                :attributes="attributes"
                :item_id="item_id"
                @on-add-new-sku="onAddNewSku"
                @on-delete-sku="onDeleteSku"
                @on-delete-variant="onDeleteVariant"
            ></sku-list>
        </template>

        <!-- create attributes (Before Skus) -->
        <template v-else>
            <div class="mb-3">
                <a href="#" class="small" @click.prevent="changeAttribute"
                    >အမျိုးအစားကွဲမရှိပါ?</a
                >
            </div>
            <p class="py-3 mb-0 small fw-bold">
                အမျိုးကွဲခေါင်းစဥ်ရွေးပါ။ List ထဲတွင် မရှိပါက Others ကိုရွေး၍
                ထည့်နိုင်ပါသည်။(Color, Size, Flavour,...)
            </p>
            <div class="row">
                <div class="col-md-2 form-group">
                    <select
                        class="form-select"
                        v-model="form.attr_name"
                        @change="onSelectAttributeName"
                    >
                        <option value="">Choose Title</option>
                        <option
                            v-for="status in statuses"
                            :key="status"
                            :value="status"
                            >{{ status }}</option
                        >
                        <option value="other">Others</option>
                    </select>
                </div>
                <div class="col-md-2 form-group" v-show="show_attr_form">
                    <input
                        type="text"
                        class="form-control form-control-sm"
                        v-model="form.attr_name"
                        placeholder="Attribute name"
                    />
                </div>
                <div class="col-md-3 form-group">
                    <button
                        class="btn btn-sm btn-secondary"
                        :class="
                            form.attr_name == '' && form.attr_name != 'other'
                                ? 'disabled'
                                : ''
                        "
                        @click.prevent="onAddAttribute"
                    >
                        Add Attribute
                    </button>
                </div>
            </div>
            <attribute-list v-show="attributes.length" :attributes="attributes" @on-delete-attribute="onDeleteAttribute"></attribute-list>

            <button
                type="button"
                class="btn btn-sm btn-outline-secondary"
                @click.prevent="makeSku"
            >
                Make SKUs (အမျိုးကွဲ Items များဖန်တီးမည်)
            </button>
        </template>
    </div>
</template>

<script>
import PricingForm from "./PricingForm.vue";
import SkuList from "./SkuList.vue";
import AttributeList from "./AttributeList.vue";
export default {
    components: {
        "pricing-form": PricingForm,
        "sku-list": SkuList,
        "attribute-list": AttributeList,
    },
    props: {
        statuses: { required: true, default: () => [] },
        item_id: { required: true }
    },
    data() {
        return {
            show_attr_form: false,
            form: {
                attr_name: "",
                already_status: false,
                item_id: this.item_id
            },
            data_statuses: [],
            // timeout: "",
            attributes: [],
            pricings: [],
            skus: [],
            attr_name: "",
            is_show_msg: false
        };
    },
    created() {
        axios.get(`/wapi/item-attributes/${this.item_id}`).then(resp => {
            this.attributes = resp.data;
        });
        axios.get(`/wapi/item-skus/${this.item_id}`).then(resp => {
            this.skus = resp.data;
        });
    },
    methods: {
        onSelectAttributeName() {
            if (this.form.attr_name && this.form.attr_name != "other") {
                this.onAddAttribute();
            } else if (this.form.attr_name && this.form.attr_name == "other") {
                this.show_attr_form = true;
                this.form.attr_name = "";
            }
        },
        onAddAttribute() {
            axios.post(`/wapi/attributes`, this.form).then(resp => {
                this.form.attr_name = "";
                this.form.already_status = false;
                this.attributes = resp.data;
                this.show_attr_form = false;
            });
        },
        onChangeNewAttr() {
            if (this.attr_name && this.attr_name != "other") {
                this.onAddNewAttr();
            } else if (this.attr_name && this.attr_name == "other") {
                this.show_attr_form = true;
                this.attr_name = "";
            }
        },
        onAddNewAttr() {
            if (this.attr_name) {
                let tmp = this.attributes.filter(attr => {
                    return (
                        attr.name.toLowerCase() == this.attr_name.toLowerCase()
                    );
                });
                if (tmp.length == 0) {
                    let data = {
                        attr_name: this.attr_name,
                        item_id: this.item_id
                    };
                    axios.post(`/wapi/attributes`, data).then(resp => {
                        window.location = `/admin/items/${this.item_id}/edit`;
                    });
                } else {
                    this.attr_name = '';
                }
            } else {
                alert("Enter attribute name first!");
            }
        },
        onRemoveAttr(id) {
            console.log(id);
            axios.delete(`/wapi/attributes/${id}`).then(resp => {
                 window.location = `/admin/items/${this.item_id}/edit`;
            });
        },
        onDeleteAttribute(data) {
            this.attributes = data;
        },
        makeSku() {
            axios.post(`/wapi/item-skus/${this.item_id}`).then(resp => {
                this.skus = resp.data.skus;
                this.attributes = resp.data.attributes;
            });
        },
        onDeleteSku(data) {
            this.skus = this.skus.filter(x => {
                return x.id !== data.id;
            });
            this.attributes = this.skus.length ? this.attributes : [];
        },
        onDeleteVariant(data) {
            // this.skus = data;
        },
        onAddPricing(data) {
            console.log(data);
            this.skus = data;
        },
        onAddNewSku(data) {
            window.location.reload();
            // this.skus = data;
        },
        changeAttribute() {
            this.$emit("change-attribute", false);
        }
    }
};
</script>

<style>
.data-results {
    padding: 5px;
    margin-top: 3px;
    border: 1px solid #efefef;
    color: #8c8c8c;
    font-size: 0.75rem;
    max-height: 150px;
    overflow: auto;
}
.data-results a {
    color: #8c8c8c;
}
.data-info:nth-child(2n) {
    background: #eeeff0;
}
</style>
