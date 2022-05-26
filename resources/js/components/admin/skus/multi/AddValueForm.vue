<template>
    <div class="add-value-form_container row mb-2">
        <div class="col-5 col-md-4">{{ attribute.name }}</div>
        <div class="col-5 col-md-6">
            <div class="form-group">
                <input type="text" class="form-control form-control-sm" v-model="form.name">
            </div>
        </div>
        <div class="col-2">
            <div class="form-group">
                <button class="btn btn-sm btn-outline-primary" @click.prevent="addValue">
                    <small><i class="fa fa-check"></i></small>
                </button>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        attribute: {required: true}
    },
    data() {
        return {
            form: {
                'name' : '',
                'attribute_id' : this.attribute.id,
                'value_id' : ''
            },
        }
    },
    methods: {
        addValue() {
            if(this.form.name) {
                axios.post(`/wapi/single-values`, this.form).then(resp => {
                    this.form.value_id = resp.data.id;
                    this.$emit('on-add-value', resp.data);
                });
            }
            
        }
    }
}
</script>