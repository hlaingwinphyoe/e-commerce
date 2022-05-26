import { createApp } from "vue";


import MediaUpload from './medias/MediaUpload.vue';
import SearchOrCreate from './forms/SearchOrCreate.vue';
import PermissionBox from "./permissions/PermissionBox.vue";
import AddSku from "./sku-inventory/AddSku.vue";

const app = createApp({
//
});

app.component('media-upload', MediaUpload);
app.component('search-or-create', SearchOrCreate);
app.component("permission-box", PermissionBox);
app.component("add-sku", AddSku);

app.mount('#fse-admin');