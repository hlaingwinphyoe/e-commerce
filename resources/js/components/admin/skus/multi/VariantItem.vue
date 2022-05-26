<template>
    <div class="variant_item-container bg-white rounded shadow small px-1 py-1 mb-2 me-2">
        <span class="text-muted">{{ variant.attribute.name }}</span>
        <span class="fw-bold"> - {{ getValue }}</span>
        <div class="row d-none">
            <p class="col-4 col-md-5 mb-0 small">{{ variant.attribute.name }}</p>            
            <div class="col-8 col-md-7 mb-0 d-flex align-items-center">
                <p class="mb-0 mr-2 fw-bold text-primary">{{ getValue }}</p>
                <a href="#" v-show="variant.attribute.parent_id" class="small text-secondary d-none" @click.prevent="onDeleteVariant">
                    <i class="fa fa-times"></i>
                </a>
            </div>
        </div>
        
    </div>
</template>

<script>
export default {
    props: {
        variant: {required: true}
    },
    computed: {
        getValue() {
            return this.variant.attribute.values.filter(x => {
                return x.id === this.variant.value_id;
            })[0].name;
        }
    },
    methods: {
        onDeleteVariant() {
            axios.delete(`/wapi/variants/${this.variant.id}`).then(resp => {
                this.$emit('on-delete-variant', resp.data);
            });
        }
    }
}
</script>