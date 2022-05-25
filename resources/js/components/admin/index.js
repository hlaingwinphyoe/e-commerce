import { createApp } from "vue";


import MediaUpload from './medias/MediaUpload.vue';
import SearchOrCreate from './forms/SearchOrCreate.vue';

const app = createApp({
//
});

app.component('media-upload', MediaUpload);
app.component('search-or-create', SearchOrCreate);

app.mount('#fse-admin');