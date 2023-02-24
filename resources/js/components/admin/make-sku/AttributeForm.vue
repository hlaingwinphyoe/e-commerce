<template>
    <div class="">
        <ul class="nav flex-column">
            <template v-for="attr in attr_names" :key="attr.id">
                <li v-if="attr.slug == 'size' || attr.slug == 'color'" class="nav-item border-bottom py-3">
                    <span class="text-primary fw-bold">{{ attr.name }}</span>
                    <template v-if="attr.slug == 'size'">
                        <div class="d-flex mb-3">
                            <span class="me-2">Choose Size</span>
                            <select class="form-select form-select-sm w-auto" v-model="size" @change="onChangeSize">
                                <option value=""></option>
                                <option v-for="size in size_values" :key="size.id" :value="size.name">{{ size.name }}</option>
                            </select>
                        </div>
                        <ul class="nav mb-2">
                            <li class="nav-item" v-for="size in item_sizes" :key="size.id">
                                <a href="#" class="btn btn-sm btn-primary me-2" @click.prevent="onOpenModal(size)">{{ size.name }}</a>
                            </li>
                        </ul>
                    </template>
                    <template v-else-if="attr.slug == 'color'">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="d-flex mb-2">
                                    <span class="me-2">Pick Color</span>
                                    <input type="color" v-model="color" @change="onChangeColor" @keyup="onChangeColor"> 
                                </div>  
                                <ul class="nav">
                                    <li class="nav-item" v-for="item_color in item_colors" :key="item_color.id">
                                        <a href="#" class="btn btn-sm p-3 me-2 border" :class="item_color.name != item_color.url ? 'border-bold' : ''" :style="`background:${item_color.url}`" @click.prevent="onOpenModal(item_color)"></a>
                                    </li>
                                </ul>   
                            </div> 
                            <div class="col-md-6">
                                <div class="d-flex mb-2">
                                    <span class="me-2">Pattern</span>
                                    <button class="upload-btn-wrapper btn btn-sm btn-outline-primary">
                                        <input type="file" ref="media" @change="onChangePattern($event.target.files)">
                                        <small>Upload</small>
                                    </button>
                                </div>
                                <ul class="nav">
                                    <li class="nav-item" v-for="item_pattern in item_patterns" :key="item_pattern.id">
                                        <div class="me-2 border rounded">
                                            <a href="#" @click.prevent="onOpenModal(item_pattern)">
                                                <img :src="item_pattern.url" alt="pattern" style="width:35px; height: 35px">
                                            </a>
                                        </div>
                                    </li>
                                </ul> 
                            </div>
                        </div>              
                    </template>
                </li>
            </template>
        </ul>
        <!-- create-button -->
        <div class="py-3" v-if="!has_sku && has_color && has_size">
            <button class="btn btn-sm btn-primary" @click.prevent="makeSku">Create Sku Item</button>
        </div>

    </div>
</template>

<script>
export default {
    components: {
       //
    },
    props: {
        item: {required: true},
        item_attributes: {required: true},
        has_sku: {required: true}
    },
    data() {
        return {
            attr_names: [],
            size_values: [],
            color: '#DFDFDF',
            size: '',
            item_sizes: [],
            item_colors: [],
            item_patterns: [],
            has_color: false,
            has_size: false,
            modal : '',
            modal_form : {
                name: '',
                value: ''
            },
        }
    },
    mounted() {
        axios.get(`/wapi/statuses`, {params: {type : 'attribute'}}).then(resp => this.attr_names = resp.data);
        axios.get(`/wapi/statuses`, {params: {type : 'size'}}).then(resp => this.size_values = resp.data);
        //colors
        let color = this.item_attributes.find(x => x.name=='Color');
        let values = color ? color.values : [];
        this.getColors(values);        
        this.has_color = values.length ? true : false;

        this.item_sizes = this.getSizes();
    },
    methods: {
        getSizes() {
            let size = this.item_attributes.find(x => x.name=='Size');

            this.has_size = size && size.values.length ? true : false;
        
            return size ? size.values : [];
        },
        getColors(values) {
            this.item_colors = values.filter(x => x.type == 'color_code' ? x : '');

            this.item_patterns = values.filter(x => x.type == 'pattern' ? x : '');
        },
        onChangeColor() {
            // alert('here');
            let form = {
                item: this.item.id,
                attribute: 'Color', 
                value: this.color,
                type: 'color_code'
            };
            axios.post(`/wapi/attribute-values`, form).then(resp => {
                this.getColors(resp.data.values);
                this.has_color = resp.data.values.length ? true : false;
                // console.log(resp.data);
                if(resp.data.value && this.has_sku) {
                    this.onMakeSku(resp.data.value);
                }
            });
        },
        onChangePattern(files) {
            let form = new FormData();
            form.append('media', files[0]);
            form.append('type', files[0].type);
            form.append('item', this.item.id);
            form.append('attribute', 'Color');
            form.append('type', 'pattern');
            axios.post(`/wapi/attribute-values`, form).then(resp => {
                this.getColors(resp.data.values);
                this.has_color = resp.data.values.length ? true : false;
                if(resp.data.value && this.has_sku) {
                    this.onMakeSku(resp.data.value);
                }
            });
        },
        onChangeSize() {
            let form = {
                item: this.item.id,
                attribute: 'Size', 
                value: this.size,
                type: 'size'
            };
            axios.post(`/wapi/attribute-values`, form).then(resp => {
                this.item_sizes = resp.data.values;
                this.has_size = resp.data.values.length ? true : false;
                if(resp.data.value && this.has_sku) {
                    this.onMakeSku(resp.data.value);
                }
            });
        },
        onMakeSku(value) {
            let form = {
                'value_id' : value.id
            }
            axios.post(`/wapi/item-skus-make/${this.item.id}`, form).then(resp => {
                this.$emit('on-add-new-sku', resp.data);
            })
        },
        makeSku() {
            axios.post(`/wapi/item-skus/${this.item.id}`).then(resp => {
                this.$emit('on-make-skus', resp.data);
            });
        },
        onOpenModal(value) {
            this.$emit('on-open-modal', value);
            // this.modal_form.name = value.name;
            // this.modal_form.value = value;
            // this.modal = new bootstrap.Modal(`#edit-modal`);
            // this.modal.show();
        },
        onUpdateName() {
             axios.patch(`/wapi/values/${this.modal_form.value.id}`, this.modal_form).then(resp => {
                this.getColors(resp.data);
                this.modal.hide();
            });
        },
        // onDeleteValue(value) {
        //     axios.delete(`/wapi/values/${this.modal_form.value.id}`).then(resp => {
        //         this.getColors(resp.data);
        //         this.modal.hide();
        //     });
        // }
    },
    
}
</script>

<style>
.border-bold {
    border: 3px solid #333 !important;
}
</style>