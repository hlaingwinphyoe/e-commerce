import { createApp } from "vue";


import MediaUpload from './medias/MediaUpload.vue';
import SkuMediaUpload from './sku-medias/SkuMediaUpload.vue';
import SearchOrCreate from './forms/SearchOrCreate.vue';
import SkuPrice from './skus/SkuPrice.vue';
import SkuDiscount from './sku-discounts/Discount.vue';

const app = createApp({
//
});

app.component('media-upload', MediaUpload);
app.component('sku-media-upload', SkuMediaUpload);
app.component('search-or-create', SearchOrCreate);
app.component('sku-price', SkuPrice);
app.component('sku-discount', SkuDiscount);

app.mount('#fse-admin');