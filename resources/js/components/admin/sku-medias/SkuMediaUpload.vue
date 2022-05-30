<template>
    <div class="box mb-3">
        <div id="feature-box" class="collapse show">
            <template v-if="!medias.length">
                <div class="box-body upload-btn-wrapper py-2">
                    <button class="upload-btn p-0 mb-1">
                        <span class="me-2"
                            ><i class="fas fa-images"></i
                        ></span>
                        <span>ပုံတင်ရန် ဤနေရာကိုနှိပ်ပါ။</span>
                    </button>
                    <input
                        type="file"
                        name="media"
                        ref="media"
                        @change="onChange($event.target.files)"
                    />
                    <small class="text-muted mm-font"
                        >jpg, jpeg, png, gif, webp (File Type) များသာလက်ခံပါသည်။</small
                    >
                    <p class="mb-0 mm-font small text-danger">
                        Featured image နှင့်ပုံတူလျှင် မထည့်လည်းရပါသည်။
                    </p>
                </div>

                <div v-if="error"><small class="bg-secondary-light px-3 rounded fw-bold">{{error}}</small></div>

                <!-- loading -->
                <template v-if="filesList.length">
                    <div class="box-body">
                        <div
                            class="progress mb-2"
                            v-for="media in filesList"
                            :key="media.name"
                            :style="`height:8px`"
                        >
                            <div
                                class="progress-bar progress-bar-striped progress-bar-animated bg-dark h-100"
                                :style="`width:${media.percentage}%`"
                                :aria-valuenow="media.percentage"
                                :aria-valuemin="0"
                                :aria-valuemax="100"
                            ></div>
                        </div>
                    </div>
                </template>
                <!-- loading -->
            </template>
            <!-- media list -->
            <template v-else>
                <div class="box-body py-2 media-item-container">
                    <sku-media-item
                        v-for="media in medias"
                        :key="media.id"
                        :media="media"
                        :images="images"
                        @on-destroy-media="onDestroyMedia"
                        @on-update-title="onUpdateTitle"
                    ></sku-media-item>
                </div>
            </template>
            <!-- media list -->
        </div>
    </div>
</template>

<script>
import SkuMediaItem from "./SkuMediaItem.vue";

export default {
    components: {
        "sku-media-item": SkuMediaItem
    },
    props: {
        sku_id: { required: true },
        images: {
            required: true,
            type: Array,
            default: () => []
        }
    },
    data() {
        return {
            error: '',
            loading: false,

            medias: this.images ? this.images : [],

            filesList: []
        };
    },
    methods: {
        onChange(files) {
            if (!files.length) return;

            Array.from(files).map(x => {
                this.filesList.push({
                    name: x.name,
                    percentage: 50,
                    file: x
                });
            });

            this.submit();
        },
        submit() {
            this.loading = true;

            let request = this.filesList.map(item => {
                return new Promise((resolve, reject) => {
                    let form = new FormData();
                    form.append("media", item.file);
                    let config = {
                        onUploadProgress: event => {
                            let percent = Math.round(
                                (event.loaded * 100) / event.total
                            );
                            item.percentage = percent;
                            if (item.percentage >= 100) {
                                item.color = "#26a69a";
                            }
                        }
                    };

                    axios
                        .post(`/wapi/sku-medias/${this.sku_id}`, form, config)
                        .then(response => {
                            this.filesList = this.filesList.filter(
                                x => x.name != item.name
                            );
                            this.medias.push(response.data);
                            this.$emit("media-updated", this.medias);
                            resolve();
                        })
                        .catch(error => {
                            this.error = error.response.data.errors['media'][0];
                            reject();
                        });
                });
            });

            Promise.all([request])
                .then(() => {
                    this.loading = false;
                    this.$refs.media.value = "";
                })
                .catch(error => {
                    this.loading = false;
                    this.$refs.media.value = "";
                });
        },
        onDestroyMedia(data) {
            this.medias = this.medias.filter(x => {
                return x.id != data.id;
            });
            this.$emit("on-destroy-media", data);
        },
        onUpdateTitle(data) {
            this.medias = this.medias.map(x => {
                return x.id == data.id ? data : x;
            });
        }
    }
};
</script>

<style>
.upload-btn-wrapper {
    position: relative;
    overflow: hidden;
    display: inline-block;
}
.upload-btn {
    width: 100%;
    display: flex;
    align-items: center;
    color: gray;
    background-color: transparent;
    font-size: 16px;
    font-weight: bold;
    border: 0;
}
.upload-btn:hover {
    cursor: pointer;
}

.upload-btn-wrapper input[type="file"] {
    font-size: 100px;
    position: absolute;
    left: 0;
    top: 0;
    opacity: 0;
    width: 100%;
    height: 100%;
}
.slide-box .media-item-container {
    display: flex;
    flex-wrap: wrap;
}
.slide-box .media-item-container .media-item {
    width: 45%;
    padding: 15px;
    margin-right: 15px;
    border: 1px solid #efefef;
}
blockquote.primary {
    border-width: 1px 1px 1px 7px;
    border-style: solid;
    border-color: rgb(252, 68, 69);
    border-image: initial;
    border-left: 7px solid rgb(252, 68, 69);
}
</style>
