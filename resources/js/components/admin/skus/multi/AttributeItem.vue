<template>
    <div class="attribute_item-container mb-2 p-2 bg-sidebar">
        <div class="row align-items-center">
            <div class="col-md-6 d-flex align-items-center">
                <p class="mb-0 pt-2 fw-bold">{{ attr_form.name }}</p>
                <a href="#" class="small mx-2 d-none" @click.prevent="isEditAttr=!isEditAttr"><i class="fa fa-pencil-alt"></i> အမည်ပြင်မည်</a>
                <a href="#" class="small mx-2 text-secondary" @click.prevent="onDeleteAttribute"><i class="fa fa-trash"></i> ဖျက်မည်</a>
            </div>
            <div class="col-md-2" v-show="isEditAttr">
                <input type="text" class="form-control form-control-sm" v-model="attr_form.name" @change="onUpdateAttribute">
            </div>
            <div class="col-md-2" v-show="isEditAttr">
                <button class="btn btn-sm btn-secondary" @click.prevent="onUpdateAttribute"><i class="fa fa-check"></i></button>
            </div>
        </div>

        <!-- values form -->
        <div class="py-2">
            <p class="text-muted small mb-2">{{ attr_form.name }} အတွက် တန်ဖိုးများထည့်ပါ။ (ဥပမာ-Colorဆိုလျှင် Red, Green, Blue, ...)</p>
            <div class="row">
                <div class="col-md-2 form-group">
                    <input type="text" class="form-control form-control-sm" v-model="val_form.name" placeholder="Add values for attribute" @change="onAddValue">
                </div>
                <div class="col-md-2 form-group">
                    <button class="btn btn-sm btn-secondary" :class="val_form.name==''?'disabled':''" @click.prevent="onAddValue">Add</button>
                </div>
            </div>
        </div>
        <!-- values form -->

        <!-- values list -->
        <value-list v-show="values.length" :values="values" @on-delete-value="onDeleteValue"></value-list>
        <!-- values list -->
        
    </div>
</template>

<script>
import ValueList from './ValueList.vue';
import ValueItem from './ValueItem.vue';
export default {
    components: {
        "value-list" : ValueList,
        "value-item" : ValueItem
    },
    props: {
        attribute : {required: true, default: () => []}
    },
    data() {
        return {
            values: this.attribute.values,
            isEditAttr : false,
            attr_form: {
                name : this.attribute.name
            },
            val_form: {
                name: '',
                attribute_id: this.attribute.id
            }
        }
    },
    methods: {
        onUpdateAttribute() {
            axios.patch(`/wapi/attributes/${this.attribute.id}`, this.attr_form).then(resp => {
                this.isEditAttr = false;
            });
        },
        onDeleteAttribute() {
            axios.delete(`/wapi/attributes/${this.attribute.id}`).then(resp => {
                this.$emit('on-delete-attribute', resp.data);
            });
        },
        onAddValue() {
            axios.post(`/wapi/values`, this.val_form).then(resp => {
                this.values = resp.data;
                this.val_form.name = '';
            });
        },
        onDeleteValue(data) {
            this.values = this.values.filter(x => {
                return x.id !== data.id;
            });
        }
    }
}
</script>