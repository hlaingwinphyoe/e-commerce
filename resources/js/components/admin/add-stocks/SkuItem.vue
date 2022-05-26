<template>
    <div class="sku-item-container">
        <div class="mb-2">
            <p>{{ sku.data }}</p>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        sku: {required: true}
    }
}
</script>