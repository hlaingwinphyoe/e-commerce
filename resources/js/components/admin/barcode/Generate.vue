<template>
    <tr class="align-middle">
        <td>
            <img
                :src="getThumbnail"
                class="featured-img"
                :alt="data_sku.data ? data_sku.data : data_sku.item.name"
            />
        </td>
        <td>{{ data_sku.data ? data_sku.data : data_sku.item.name }}</td>

        <td>
            <div v-html="data_sku.barcode"></div>
            <p class="mb-0">{{ data_sku.code }}</p>
            <a :href="`/admin/print-barcodes/${sku.id}`" v-show="can_download">Download</a>
        </td>
        <td>
            <div class="barcode-generate">
                <div class="form-group mb-2">
                    <input
                        type="text"
                        class="form-control form-control-sm"
                        ref="barcode"
                        autofocus
                        placeholder="Scan Input"
                        @change="onInputBarCode"
                    />
                </div>
                <div class="form-group">
                    <button
                        class="btn btn-sm btn-primary"
                        @click.prevent="onGenerateBarCode"
                    >
                        <span class="me-2"><i class="fas fa-barcode"></i></span>
                        <span class="ml-1 d-none d-md-inline-block"
                            >Generate</span
                        >
                    </button>
                </div>
            </div>
        </td>
        <td class="text-center">{{ data_sku.stock }}</td>
        <td class="">{{ data_sku.price }}</td>
    </tr>
</template>

<script>
import DownnloadCode from './DownloadCode.vue';
export default {
    components: {
        'download-code' : DownnloadCode
    },
    props: {
        sku: { required: true }
    },
    data() {
        return {
            data_sku : this.sku,
            can_download: this.sku.code ? true : false
        }
    },
    methods: {
        onInputBarCode() {
            if (this.$refs.barcode.value) {
                let data = {
                    'barcode' : this.$refs.barcode.value
                };
                axios
                    .patch(`/wapi/sku-barcodes/${this.sku.id}`, data)
                    .then(resp => {
                        this.$refs.barcode.value = "";
                        this.data_sku = resp.data;
                        this.can_download = true;
                    });
            }
        },
        onGenerateBarCode() {
            let data = {
                    'barcode' : this.$refs.barcode.value
                };
            axios
                .patch(`/wapi/sku-barcodes/${this.sku.id}`, data)
                .then(resp => {
                    this.data_sku = resp.data;
                    this.can_download = true;
                });
        }
    },
    computed: {
        getThumbnail() {
            return this.sku.thumbnail.includes('default.png') ? this.sku.item.thumbnail : this.sku.thumbnail;
            // return this.sku.thumbnail;
        }
    }
};
</script>
