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
import GiftInventory from  "./gift-inventory/GiftInventory.vue";
import NotificationList from "./notification/NotificationList.vue";
import MakeSku from './make-sku/MakeSku.vue';
import Index from './generals/Index.vue';
import ExpenseIndex from './expense/Index.vue';
import MonthlyReport from './reports/Monthly.vue';
import { MonthPicker } from "vue-month-picker";
import { MonthPickerInput } from "vue-month-picker";

import VueMobileDetection from "vue-mobile-detection";

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
app.component("gift-inventory", GiftInventory);
app.component("notification-list", NotificationList);
app.component('make-sku', MakeSku);
app.component('general-index', Index);
app.component('expense-index',ExpenseIndex);
app.component('monthly-report', MonthlyReport);

app.use(MonthPicker);
app.use(MonthPickerInput);
app.use(VueMobileDetection);
app.mount('#fse-admin');
