<template>
    <div class="row" :key="attribute_form">
        <div class="col-md-6 mb-4">
            <div class="bg-white shadow rounded px-3 py-4 h-100">
                <div class="d-flex mb-2">
                    <h5 class="me-2">Sku Form</h5>
                    <h5 v-if="sku_control" class="text-capitalize">- {{ sku_control}}</h5>
                    <select v-else class="w-auto form-select form-select-sm" v-model="sku_control" @change="onChangeSkuControl">
                        <option value="clothing">Clothing Form</option>
                        <option value="general">General Form</option>
                    </select>
                </div>

                <!-- General Attribute Form -->
                <general-form v-if="sku_control=='general'" :item="item" :item_attributes="data_item_attributes" :has_sku="has_sku" @on-make-skus="onMakeSkus" @on-add-new-sku="onAddNewSku" @on-open-modal="onOpenModal" @on-update-attribute="onUpdateAttribute"></general-form>

                <!-- Clothing Attribute Form -->
                <attribute-form v-if="sku_control=='clothing'" :item="item" :item_attributes="data_item_attributes" :has_sku="has_sku" @on-make-skus="onMakeSkus" @on-add-new-sku="onAddNewSku" @on-open-modal="onOpenModal"></attribute-form>

                <!-- modal -->
                <div class="modal fade" id="edit-modal">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h6 class="modal-title" id="exampleModalLabel">Edit Name</h6>
                                <button type="button" class="btn-close btn btn-sm btn-outline-danger" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="d-flex">
                                    <span v-if="modal_form.value.type=='color_code'" class="btn btn-sm border p-3 me-2" :style="`background:${modal_form.value.url}`"></span>
                                    <div v-else-if="modal_form.value.type == 'pattern'" class="me-2 border rounded" style="width:35px; height: 35px">
                                        <img :src="modal_form.value.url" alt="pattern" class="w-100 h-100">
                                    </div>
                                    <div v-if="modal_form.value.type !='color_code'" class="form-group">
                                        <label for="">Name</label>
                                        <input type="text" class="form-control form-control-sm" v-model="modal_form.name">
                                    </div>
                                    <div v-else class="form-group">
                                        <label>Color Family</label>
                                        <select class="form-select" v-model="modal_form.name">
                                            <option value="">Select Color Family</option>
                                            <option v-for="color in colors" :key="color.id" :value="color.name" class="text-capitalize">{{ color.name }}</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- medias -->

                                <div class="py-3" v-if="data_medias.length">
                                    <h6>Choose Image</h6>
                                    <div class="d-flex flex-wrap">
                                        <div class="me-3 mb-3"  v-for="data_media in data_medias" :key="`media-${data_media.id}`">
                                            <a href="#" @click.prevent="onAddAttributeImage(data_media)">
                                                <img :src="`/storage/thumbnail/${data_media.slug}`" :alt="data_media.slug" class="value-featured" :class="modal_form.media_id == data_media.id ? 'border' : ''"/>
                                            </a>
                                        </div>
                                        
                                    </div>
                                </div>

                                <!-- buttons -->
                                <div class="py-3 d-flex">
                                    <button type="button" class="btn btn-sm btn-outline-danger me-2" data-bs-dismiss="modal">Close</button>
                                    <button type="button" class="btn btn-sm btn-primary me-auto" @click.prevent="onUpdateValue">Update</button>
                                    <button type="button" v-if="!has_sku" class="btn btn-sm btn-outline-danger" @click.prevent="onDeleteValue"><i class="fa fa-trash"></i></button>
                                </div>
                            </div>
                        </div>                
                    </div>
                </div> 
               
                <!-- price-form -->
                <div class="py-3" v-if="has_sku">
                    <h5>Add Price</h5>
                    <div class="d-flex">
                        <div class="form-group me-2">
                            <input type="text" v-model="form.price" class="form-control form-control-sm">
                        </div>
                        <div class="form-group">
                            <button type="button" class="btn btn-sm btn-outline-primary" @click.prevent="onAddPricing">Add / Update</button>
                        </div>
                    </div>
                </div>

                <!-- discount-form -->
                <div class="py-3 border-top" v-if="has_sku">                    
                    <div class="d-flex align-items-center mb-3" v-if="discount_amount">
                        <h6 class="mb-0">{{ discount_form.name }} - </h6>
                        <span class="ms-2 fw-bold text-primary pe-3">{{ discount_form.amt }} {{ discount_form.status == '26'? 'Ks' : '%' }} ( {{ discount_amount }} )</span>

                        <a href="#" class="text-danger"  @click.prevent="onDeleteDiscount">
                            <span><i class="fas fa-trash"></i></span>
                        </a>
                    </div>
                    <h5 v-else>Add Discount</h5>
                                        
                    <div class="d-flex">
                        <div class="form-group me-2 mb-2 position-relative">
                            <label for="">Name</label>
                            <input type="text" v-model="discount_form.name" class="form-control form-control-sm" @keyup="onSearchDiscount">
                            <div class="results absolute-box bg-white shadow rounded mt-1" v-if="discountypes.length">
                                <ul class="nav flex-column">
                                    <li class="nav-item" v-for="res in discountypes" :key="res.id">
                                        <a href="#" class="nav-link py-1 text-primary" style="font-size: .9rem" @click.prevent="onChooseDiscount(res)">{{ res.name }}</a>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <div class="form-group me-2 mb-2">
                            <label for="">Start Date</label>
                            <input type="date" v-model="discount_form.start_date" class="form-control form-control-sm">
                        </div>

                         <div class="form-group me-2 mb-2">
                            <label for="">End Date</label>
                            <input type="date" v-model="discount_form.end_date" class="form-control form-control-sm">
                        </div>
                    </div>
                    <div class="d-flex">
                        <div class="form-group me-2 mb-2">
                            <label for="">Amount</label>
                            <input type="text" v-model="discount_form.amt" class="form-control form-control-sm">
                        </div>
                        <div class="form-group me-2 mb-2 align-self-end">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" v-model="discount_form.status" name="status" id="fixed" value="26">
                                <label class="form-check-label" for="fixed">Ks</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" v-model="discount_form.status" name="status" id="percent" value="27">
                                <label class="form-check-label" for="percent">%</label>
                            </div>
                        </div>
                        <div class="form-group mb-2 align-self-end">
                            <button type="button" class="btn btn-sm btn-outline-primary" @click.prevent="onAddDiscount">Add</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-4" v-if="data_skus.length">
            <sku-list :skus="data_skus" :item="item" :key="data_skus_key" @on-delete-sku="onDeleteSku"></sku-list>
        </div>
    </div>
</template>

<script>
import GeneralForm from './GeneralForm.vue';
import AttributeForm from './AttributeForm.vue';
import SkuList from './SkuList.vue';
export default {
    components: {
        'general-form' : GeneralForm,
        'attribute-form' : AttributeForm,
        'sku-list' : SkuList
    },
    props: {
        item: {required: true},
        item_attributes: {required: true},
        skus: {required: true},
    },
    data() {
        return {
            discount_id: this.item.discounts.length ? this.item.discounts[0].id : '',
            discount_amount: this.item.discount,
            attribute_form: 0,
            sku_control : this.item.sku_control,
            data_item_attributes: this.item_attributes,
            data_skus: this.skus,
            data_medias: this.item.medias,
            has_sku: this.skus.length ? true : false,
            form: {
                price: this.item.min_price,
                min_qty: 1,
                max_qty: 1,
            },
            data_skus_key: 0,
            discount_form: {
                discountype: '',
                name: '',
                start_date: '',
                end_date: '',
                amt: '',
                status: '',
            },
            timeout: '',
            discountypes: [],
            modal: '',
            modal_form : {
                name: '',
                value: '',
                media_id: '',
            },
            colors: [],
        }
    },
    mounted() {
        axios.get(`/wapi/v1/item-discounts/${this.item.id}`).then(resp => {
            if(resp.data) {
                this.onChooseDiscount(resp.data.discountype);
            }
            
        });
    },
    methods: {
        onChangeSkuControl() {
            axios.patch(`/wapi/v1/items/${this.item.id}`, {sku_control : this.sku_control}).then(resp => {
                //
            });
        },
        onDeleteSku(data) {
            this.data_skus = data.skus;
            this.data_item_attributes = data.attributes;
            this.has_sku = this.data_skus.length ? true : false;
            this.attribute_form += 1;
        },
        onMakeSkus(data) {
            this.data_skus = data.skus;
            this.has_sku = data.skus.length ? true : false;
            this.data_skus_key +=1;
        },
        onAddNewSku(data) {
            this.data_skus = data;            
            this.has_sku = this.data_skus.length ? true : false;
        },
        onAddPricing() {
            axios.post(`/wapi/v1/item-pricings/${this.item.id}`, this.form).then(resp => {
                this.data_skus = resp.data;
                this.data_skus_key +=1;
            });
        },
        onSearchDiscount() {
            clearTimeout(this.timeout);
            if(this.discount_form.name) {
                this.timeout = setTimeout(() => {
                    axios.get(`/wapi/discountypes`, {params: {q : this.discount_form.name}}).then(resp => {
                        this.discountypes = resp.data;
                    });
                }, 100);
            }
        },
        onChooseDiscount(discount) {
            if(discount) {
                this.discount_form.discountype = discount.id;
                this.discount_form.name = discount.name;
                this.discount_form.amt = discount.amt;
                this.discount_form.start_date = discount.start_date;
                this.discount_form.end_date = discount.end_date;
                this.discount_form.status = discount.status_id;
                this.discountypes = [];
            }
            
        },
        onAddDiscount() {
            axios.post(`/wapi/v1/item-discounts/${this.item.id}`, this.discount_form).then(resp => {
                this.discount_form.name = resp.data.discount.discountype.name;
                this.discount_amount = resp.data.amount;
                this.discount_id = resp.data.discount.id;
            })
        },
        onDeleteDiscount(){
            axios.delete(`/wapi/v1/item-discounts/${this.discount_id}`). then(resp => {
                this.discount_form.discountype = "";
                this.discount_form.name = "";
                this.discount_form.amt = "";
                this.discount_form.start_date = "";
                this.discount_form.end_date = "";
                this.discount_form.status = "";
                this.discount_amount = "";
            });
        },
        onOpenModal(value) {
            this.modal_form.name = value.name;
            this.modal_form.value = value;
            this.modal_form.media_id = value.media_id;
            this.modal = new bootstrap.Modal(`#edit-modal`);
            this.modal.show();
        },
        onAddAttributeImage(media) {
            this.modal_form.media_id = media.id;
        },
        onDeleteValue(value) {
            axios.delete(`/wapi/values/${this.modal_form.value.id}`).then(resp => {
                // this.getColors(resp.data);
                this.data_item_attributes = resp.data.attributes;
                this.attribute_form += 1;
                this.modal.hide();
            });
        },
        onUpdateValue() {
            axios.patch(`/wapi/values/${this.modal_form.value.id}`, this.modal_form).then(resp => {
                this.data_item_attributes = resp.data.attributes;
                this.attribute_form += 1;
                this.modal.hide();
            });
        },
        onUpdateName() {
            axios.patch(`/wapi/values/${this.modal_form.value.id}`, {name : this.modal_form.name}).then(resp => {
                this.data_item_attributes = resp.data.attributes;
                this.attribute_form += 1;
                this.modal.hide();
            });
        },
        onUpdateAttribute(data) {
            this.data_item_attributes = data;
            this.attribute_form += 1;
        }
    }
}
</script>

<style>
.value-featured {
    max-height: 75px;
}
.value-featured.border {
    border-width: 3px !important;
    border-color: #212529 !important;
}
</style>