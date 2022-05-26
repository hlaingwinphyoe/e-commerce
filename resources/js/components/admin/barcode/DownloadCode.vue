<template>
    <div class="download-code-container">
        <a :download="`${sku.item.name}-${sku.data}.jpeg`" id="download" :href="src">Download</a>
        <div class="row">
            <div :id="sku.id" class="text-center bg-white col-md-3 py-2 px-1 border">
                <div v-html="sku.barcode"></div>
                <p class="mb-0">{{ sku.code }}</p>
                <p class="mb-0 small">{{ sku.item.name }} {{ sku.data ? '('+ sku.data +')' : '' }}</p>
                <p class="mb-0">{{ getPrice }} Ks</p>
            </div>
        </div>        
    </div>
</template>

    <script>
export default {
    props: {
        sku: {required: true}
    },
    data() {
        return {
            src : ''
        }
    },
    mounted() {
        html2canvas(document.getElementById(this.sku.id)).then(canvas => {
            this.src =  canvas.toDataURL('image/jpeg', 0.9);
        });
        document.getElementById(this.sku.id).click();
    },
    computed: {
        getPrice() {
            return new Intl.NumberFormat().format(this.sku.price);
        }
    }
}
</script>

<style>
img[alt="barcode"] {
    min-height: 100px;
}
</style>