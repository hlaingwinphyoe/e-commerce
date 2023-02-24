<template>
    <li class="nav-item py-3 border-bottom">
        <div class="d-flex">
            <a href="#" v-if="!has_sku" class="me-2 text-danger" @click.prevent="onDeleteAttribute"><i class="fa fa-trash"></i></a>
            <span class="me-2 w-min-100 fw-bold">{{ attribute.name }}</span>
            <input type="text" placeholder="Add Value" class="w-auto me-2 form-control form-control-sm"  v-model="form.value">
            <button type="button" v-if="form.value" class="btn btn-sm btn-outline-primary" @click.prevent="onAddValue">Add Value</button>
        </div>
        <ul class="nav pt-3" v-if="data_values.length">
            <li class="nav-item" v-for="value in data_values" :key="value.id">
                <a href="#" class="nav-link font-xs border rounded py-1 px-2 me-2" @click.prevent="onOpenModal(value)">{{ value.name }}</a>
            </li>
        </ul> 
    </li>
</template>

<script>
export default {
    props: {
        attribute: { required: true },
        item: {required: true},
        has_sku: {required: true}
    },
    data() {
        return {
            data_values: this.attribute.values,
            form: {
                value: '',
                item: this.item,
                attribute: this.attribute.name,
                type: 'general',
            }
        }
    },
    methods: {
        onDeleteAttribute() {
            axios.delete(`/wapi/attributes/${this.attribute.id}`).then(resp => {
                this.$emit('on-update-attribute', resp.data);
            });
        },
        onAddValue() {
            axios.post(`/wapi/attribute-values`, this.form).then(resp => {
                this.data_values = resp.data.values;
                this.form.value = '';
                 if(resp.data.value && this.has_sku) {
                    this.onMakeSku(resp.data.value);
                }
                this.$emit('on-add-value', resp.data)
            });
        },
        onOpenModal(value) {
            this.$emit('on-open-modal', value)
        },
        
        onMakeSku(value) {
            // let form = {
            //     'value_id' : value.id
            // }
            // axios.post(`/wapi/item-skus-make/${this.item.id}`, form).then(resp => {
            //     this.$emit('on-add-new-sku', resp.data);
            // });       
        },
    }
};
</script>
