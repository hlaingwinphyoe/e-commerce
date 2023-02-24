<template>
    <tr>
        <td>{{ index+1 }}</td>
        <td>            
            <a href="#" class="upload-btn-wrapper">
                <input type="file" ref="media" @change="onChangeMedia($event.target.files)">
                <div style="width: 55px; max-height: 50px">
                    <img :src="thumbnail_url" :alt="sku.item_name" class="w-100 h-100">
                </div>
            </a>
        </td>
        <template v-if="item.sku_control == 'clothing'">
        <td>
            <span v-if="sku.color" class="btn btn-sm border p-3" :style="`background:${sku.color}`"></span>
            <div v-else-if="sku.pattern" class="me-2 border rounded" style="width:35px; height: 35px">
                <img :src="sku.pattern" alt="pattern" class="w-100 h-100">
            </div>
        </td>
        <td>{{ sku.size }}</td>
        </template>
        <template v-else>
            <td>{{ sku.data }}</td>
        </template>
        <td><input type="text" v-model="form.price" class="form-control form-control-sm" style="max-width: 100px" @change="onUpdatePrice"></td>
        <template v-if="item.is_stock_control">
        <td><a :href="`/admin/skus/${sku.id}`" class="btn btn-sm btn-outline-primary">{{ stock_form.current_stock }}</a></td>
        <td><input type="text" class="form-control form-control-sm qty-input" placeholder="Qty" v-model="stock_form.qty" @change="onChangeStock"></td>
        </template>
        <td class="text-end">
            <button v-if="!stock_form.current_stock" type="button" class="btn btn-sm btn-outline-danger" @click.prevent="onDeleteSku"><i class="fa fa-times"></i></button>
        </td>
    </tr>
</template>

<script>
export default {
    props: {
        sku: {required: true},
        index: {required: true},
        item: {required: true},
    },
    data() {
        return {
            thumbnail_url : this.sku.thumbnail,
            form: {
                price: this.sku.pricing_amt,
                min_qty: 1,
            },
            stock_form: {
                qty : 0,
                amount: this.sku.price,
                current_stock: this.sku.stock,
                sku: this.sku.id
            }
        }
    },
    methods: {
        onDeleteSku(){
            axios.delete(`/wapi/skus/${this.sku.id}`).then(resp => {
                this.$emit('on-delete-sku', resp.data);
            });
        },
        onUpdatePrice() {
            axios.patch(`/wapi/skus-update-price/${this.sku.id}`, this.form).then(resp => {
                //
            });
        },
        onChangeMedia(files) {
            let form = new FormData();
            form.append('media', files[0]);
            form.append('type', files[0].type);

            axios.post(`/wapi/sku-medias/${this.sku.id}`, form).then(resp => {
                this.thumbnail_url = resp.data.sku.thumbnail;
            });
        },
        onChangeStock() {
            axios.post(`/wapi/sku-inventories`, this.stock_form).then(resp => {
                this.stock_form.current_stock = resp.data && resp.data.sku ? resp.data.sku.stock : this.sku.stock;
                this.stock_form.qty = 0;
            });
        }
    }
}
</script>