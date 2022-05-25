<template>
    <div class="row mb-1">
        <p class="col-4 col-md-3 mb-0">{{ attribute.name }}</p>
        <div class="col-6 col-md-md-6">
            <input
                type="text"
                class="form-control form-control-sm"
                placeholder="Value"
                v-model="name"
            />
        </div>
        <div class="col-2 col-md-3">
            <button class="btn btn-sm btn-outline-primary" @click.prevent="onAddAttributeValue">
                <small><i class="fa fa-check"></i></small>
            </button>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        attribute: {required: true, },
        sku_id: {required: true},
    },
    data() {
        return {
            name: ''
        }
    },
    methods: {
        onAddAttributeValue() {
            let data = {
                name: this.name,
                item_id: this.attribute.item_id,
                sku_id: this.sku_id,
                attribute_id: this.attribute.id
            };
            if(this.name) {
                axios.post(`/wapi/attribute-values`, data).then(resp => {
                    this.$emit('on-add-value', resp.data);
                });
            }
        }
}
}
</script>
