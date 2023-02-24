<template>
<!--     <a href="#" class="d-block bg-white p-2 shadow-sm rounded mb-3 text-decoration-none">-->
<!--        <div class="box-header text-center overflow-hidden">-->
<!--            <img-->
<!--                :src="-->
<!--                    res.thumbnail.includes('default.png')-->
<!--                        ? res.item.thumbnail-->
<!--                        : res.thumbnail-->
<!--                "-->
<!--                class="sale-img me-2"-->
<!--                :alt="`${res.item_name} - ${res.data}`"-->
<!--            />-->
<!--        </div>-->
<!--        <div class="box-content bg-sidebar p-2 mt-1">-->
<!--            <p class="mb-1 fw-bold text-truncate"></p>-->
<!--            <div class="d-flex justify-content-between align-items-center">-->
<!--                -->
<!--                -->
<!--            </div>-->
<!--        </div>-->
<!--    </a>-->

        <div class="card sale-card mb-3" :class="data_stock > 0 ? '' : 'disabled'" @click.prevent="onSelectedSku(res.id, res.data ? res.data : res.item.name, res.discount ? res.discount : res.price)" style="width: 14rem;">
            <img :src="
                    res.thumbnail.includes('default.png')
                        ? res.item.thumbnail
                        : res.thumbnail
                " class="sale-img border-bottom"
                 :alt="`${res.item_name} - ${res.data}`"
            >
            <div class="card-body">
                <p class="text-muted text-truncate">{{ res.item_name }} {{ res.data ? '('+ res.data +')' : '' }}</p>
                <p class="card-text fw-bold d-flex justify-content-between align-items-center text-success">
                    <span class="h6 mb-0">{{ res.discount ? res.discount : res.price }} Ks</span>

                    <span class="badge bg-success p-2">{{ data_stock }}</span>
                </p>
                <h6 class="fw-bold text-primary">{{ res.code }}</h6>
            </div>
        </div>

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
