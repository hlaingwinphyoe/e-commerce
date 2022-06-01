import { createApp } from "vue";


import MediaUpload from './medias/MediaUpload.vue';
import SkuMediaUpload from './sku-medias/SkuMediaUpload.vue';
import SearchOrCreate from './forms/SearchOrCreate.vue';
import SkuPrice from './skus/SkuPrice.vue';
import SkuDiscount from './sku-discounts/Discount.vue';
import PermissionBox from "./permissions/PermissionBox.vue";
import BarcodeGenerate from './barcode/BarcodeGenerate.vue';
import Stock from './add-stocks/Stock.vue';
import Purchase from './purchase/Purchase.vue';
import PosIndex from './pos/PosIndex.vue';
import ReturnSku from './returns/ReturnSku.vue';
import PaymentForm from './payment/PaymentForm.vue';
import WebOrder from './web-order/WebOrder.vue';

const app = createApp({
//
});

app.component('media-upload', MediaUpload);
app.component('sku-media-upload', SkuMediaUpload);
app.component('search-or-create', SearchOrCreate);
app.component('sku-price', SkuPrice);
app.component('sku-discount', SkuDiscount);
app.component("permission-box", PermissionBox);
app.component('barcode-generate', BarcodeGenerate);
app.component('stock', Stock);
app.component('purchase', Purchase);
app.component('pos-index', PosIndex);
app.component('return-sku', ReturnSku);
app.component('payment-form', PaymentForm);
app.component('web-order', WebOrder);

app.mount('#fse-admin');