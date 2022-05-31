<template>
    <a
        href="#"
        class="
            nav-link
            py-1
            text-dark
            small
            ps-2
            me-2
            bg-white
            rounded-2
            border
            h-100
        "
        :class="data_stock > 0 ? '' : 'disabled'"
        @click.prevent="
            onSelectedSku(
                res.id,
                res.data ? res.data : res.item.name,
                res.discount ? res.discount : res.price
            )
        "
    >
        <div class="text-center mb-1">
            <img
                :src="
                    res.thumbnail.includes('default.png')
                        ? res.item.thumbnail
                        : res.thumbnail
                "
                class="featured-img me-2"
                :alt="`${res.item_name} - ${res.data}`"
            />
        </div>
        <p class="mb-0 text-uppercase fw-bold">
            {{ res.code ? res.code : res.item.code }}
        </p>
        <span>{{ res.item.name }}{{ res.data ? ":" + res.data : "" }}</span>
        <span class="me-1 text-danger h6">( {{ data_stock }} )</span>
        <p class="fw-bold text-primary mb-0">
            {{ res.discount ? res.discount : res.price }} Ks
        </p>
    </a>
</template>

<script>
export default {
    props: {
        order_id: { required: true },
        res: { required: true },
    },
    data() {
        return {
            data_stock: this.res.stock,
        }
    },
    created() {
       //
    },
    methods: {
        onSelectedSku(id, name, price) {
            let form = {
                sku: id,
                qty: 1,
                price: price
            };
            axios
                .post(`/wapi/order-skus/${this.order_id}`, form)
                .then((resp) => {
                    this.$emit("on-selected-sku", resp.data);
                });
        },
    },
};
</script>
