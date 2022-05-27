<template>
    <div class="discount-item-container mb-2">
        <input type="hidden" name="discounts[]" :value="discount.id" />
        <div class="row">
            <div class="col-md-2 form-group d-none mb-2">
                <input
                    type="text"
                    class="form-control form-control-sm disabled"
                    :value="discount.role.name"
                    disabled
                />
            </div>
            <div class="col-md-3 mb-2">
                <div class="form-group input-group">
                    <input
                        type="text"
                        class="form-control form-control-sm"
                        v-model="form.amt"
                    />
                    <select class="form-select" v-model="form.status_id">
                        <option
                            v-for="status in statuses"
                            :value="status.id"
                            :key="status.id"
                            >{{ status.name }}</option
                        >
                    </select>
                </div>
            </div>
            <div class="col-md-2 form-group mb-2">
                <a href="#" class="me-2 mb-1 btn btn-sm btn-outline-primary" @click.prevent="onUpdateDiscount"
                    ><i class="fa fa-check"></i
                ></a>
                <a
                    href="#"
                    class="mb-1 btn btn-sm btn-outline-danger"
                    @click.prevent="onDestroyDiscount"
                    ><i class="fa fa-trash"></i
                ></a>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        discount: { required: true },
        statuses: { required: true, default: () => [] }
    },
    data() {
        return {
            form: {
                amt: this.discount.amt,
                status_id: this.discount.status_id
            }
        };
    },
    methods: {
        onUpdateDiscount() {
            axios
                .patch(`/wapi/item-discounts/${this.discount.id}`, this.form)
                .then(resp => {
                    this.$emit("on-update-discount", resp.data);
                });
        },
        onDestroyDiscount() {
            axios
                .delete(`/wapi/item-discounts/${this.discount.id}`)
                .then(resp => {
                    this.$emit("on-destroy-discount", resp.data);
                });
        }
    }
};
</script>
