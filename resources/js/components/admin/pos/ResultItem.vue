<template>
     <a href="#" class="d-block bg-white p-2 shadow rounded mb-3 text-decoration-none" :class="data_stock > 0 ? '' : 'disabled'" @click.prevent="onSelectedSku(res.id, res.data ? res.data : res.item.name, res.discount ? res.discount : res.price)">
        <div class="box-header text-center">
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
        <div class="box-content bg-sidebar py-2 mt-1 px-1">
            <p class="mb-1 fw-bold">{{ res.item_name }} {{ res.data ? '('+ res.data +')' : '' }}</p>
            <div>
                <span>{{ res.code }}</span>
                <span class="ms-2 btn btn-sm btn-outline-primary">{{ data_stock }}</span>
            </div>
            <p class="fw-bold">{{ res.discount ? res.discount : res.price }} Ks</p>
        </div>
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
