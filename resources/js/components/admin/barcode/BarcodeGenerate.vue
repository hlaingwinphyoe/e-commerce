<template>
    <div class="table-responsive">
        <table class="table table-borderless">
            <thead>
                <th width="100px">Featured</th>
                <th>Name</th>
                <th width="200px">Barcode</th>
                <th width="200px">Generate</th>
                <th width="100px">Stock</th>
                <th width="150px">Price</th>
                <th width="150px">Pre Ordered</th>
            </thead>
            <tbody>
                 <generate v-for="sku in skus" :key="sku.id" :sku="sku"></generate>
            </tbody>
        </table>
    </div>
  
</template>

<script>
import Generate from './Generate.vue';
import DownnloadCode from './DownloadCode.vue';
export default {
    components: {
        'download-code' : DownnloadCode,
        'generate' : Generate,
    },
    props: {
        skus: { required: true }
    },
    data() {
        return {
            // data_sku : this.sku,
            // can_download: this.sku.code ? true : false
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
            axios
                .patch(`/wapi/sku-barcodes/${this.sku.id}`)
                .then(resp => {
                    this.data_sku = resp.data;
                    this.can_download = true;
                });
        }
    },
    computed: {
        // getThumbnail() {
        //     return this.sku.thumbnail.includes('default.png') ? this.sku.item.thumbnail : this.sku.thumbnail;
        //     // return this.sku.thumbnail;
        // }
    }
};
</script>
