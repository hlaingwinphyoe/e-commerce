<template>
    <div class="general-form-container">
        <div v-if="!data_has_sku || !(skus.length == 1 && !attributes.length)" id="general-title" class="d-flex" :class="attributes.length ? `pb-3 border-bottom` : ''">
            <select
                class="w-auto me-2 form-select form-select-sm"
                v-model="form.attr_name"
                @change="onSelectAttributeName"
            >
                <option value="">Choose Title</option>
                <option v-if="!attributes.length" value="no_attr">No Attribute</option>
                <option
                    v-for="status in statuses"
                    :key="status.id"
                    :value="status.name"
                >
                    {{ status.name }}
                </option>
                <option value="other">Others</option>
            </select>
            <div class="me-2" v-show="show_attr_form">
                <input
                    type="text"
                    class="w-auto form-control form-control-sm"
                    v-model="form.attr_name"
                    placeholder="Attribute name"
                />
            </div>
            <div v-show="show_attr_form">
                <button
                    class="btn btn-sm btn-primary"
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

        <!-- attribute-lists -->
        <ul class="nav flex-column" v-if="attributes.length">
            <attribute-item v-for="attr in attributes" :key="attr.id" :has_sku="data_has_sku" :attribute="attr" :item="item.id" @on-open-modal="onOpenModal" @on-add-value="onAddValue" @on-update-attribute="onUpdateAttribute"></attribute-item>
        </ul>

       <!-- create-button -->
        <div class="py-3" v-if="canMakeSku">
            <button class="btn btn-sm btn-primary" @click.prevent="makeSku" @on-add-value="onAddValue">Create Sku Item</button>
        </div>
    </div>
</template>

<script>
import AttributeItem from './general/AttributeItem.vue';
export default {
    components: {
        'attribute-item' : AttributeItem
    },
    props: {
        item: { required: true },
        item_attributes: {required: true},
        has_sku: {required: true}
    },
    data() {
        return {
            skus: this.item.skus,
            statuses: [],
            attributes: this.item_attributes,
            data_has_sku: this.has_sku,
            show_attr_form : false,
            form: {
                attr_name: "",
                already_status: false,
                item_id: this.item.id,
            },
        };
    },
    mounted() {
        axios.get(`/wapi/statuses`, {params: {type : 'attribute'}}).then(resp => this.statuses = resp.data);
    },
    methods: {
        onSelectAttributeName() {
            if (
                this.form.attr_name &&
                this.form.attr_name != "other" &&
                this.form.attr_name != "no_attr"
            ) {
                this.onAddAttribute();
            } else if (this.form.attr_name && this.form.attr_name == "other") {
                this.show_attr_form = true;
                this.form.attr_name = "";
            } else if (
                this.form.attr_name &&
                this.form.attr_name == "no_attr"
            ) {
                axios
                    .post(`/wapi/skus`, { item_id: this.item.id })
                    .then((resp) => {
                        this.data_has_sku = true;
                        this.skus = resp.data.skus;
                        // $('#general-title').addClass('d-none');
                        this.$emit("on-make-skus", resp.data);
                    });
            }
        },
        onAddAttribute() {
            axios.post(`/wapi/attributes`, this.form).then((resp) => {
                this.form.attr_name = "";
                this.form.already_status = false;
                this.attributes = resp.data;
                this.show_attr_form = false;
            });
        },
         makeSku() {
            axios.post(`/wapi/item-skus/${this.item.id}`).then(resp => {
                this.data_has_sku = resp.data.skus.length ? true : false;
                this.skus = resp.data.skus;
                this.$emit('on-make-skus', resp.data);
            });
        },
        onAddValue(data) {
            this.attributes = data.attributes;
            // if(data.value && this.data_has_sku) {
            //     this.onMakeSku(data.value);
            // }
        },
        onMakeSku(value) {
            let form = {
                'value_id' : value.id
            }
            axios.post(`/wapi/item-skus-make/${this.item.id}`, form).then(resp => {
                this.$emit('on-add-new-sku', resp.data);
            })
        },
        onOpenModal(value) {
            this.$emit('on-open-modal', value)
        },
        onUpdateAttribute(data) {
            this.$emit('on-update-attribute', data);
        }
    },
    computed: {
        canMakeSku() {
            let has_attr = false;
            if(this.attributes.length) {
                let boo = [];
                this.attributes.forEach(attribute => {
                    boo.push(attribute.values.length ? true : false);
                });
                has_attr = boo.every(b => b === true);
            }
            return !this.data_has_sku && has_attr;
        },
    }
};
</script>
