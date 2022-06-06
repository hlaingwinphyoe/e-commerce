<template>
    <div class="mb-2">
        <p class="mb-0">{{ inventory.gift.name }}</p>
        <div class="d-flex">
            <div class="form-group me-2">
                <input
                    type="text"
                    class="form-control form-control-sm"
                    v-model="form.qty"
                />
            </div>
            <div class="form-group me-2">
                <input
                    type="date"
                    class="form-control form-control-sm"
                    v-model="form.date"
                />
            </div>
            <div class="form-group me-2">
                <button class="btn btn-sm btn-primary" @click.prevent="onUpdateInventory">
                    <i class="fa fa-check"></i>
                </button>
            </div>
            <div class="form-group me-2">
                <button class="btn btn-sm btn-outline-secondary" @click.prevent="onClose">Close</button>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        inventory: { required: true }
    },
    data() {
        return {
            form: {
                qty: this.inventory.qty,
                date: this.inventory.date,
                gift_id: this.inventory.id
            }
        };
    },
    methods: {
        onUpdateInventory() {
            axios.patch(`/wapi/gift-inventories/${this.inventory.id}`, this.form).then(resp => {
                this.$emit('on-update-inventory', resp.data);
            });
        },
        onClose() {
            axios.patch(`/wapi/gift-inventories-close/${this.inventory.id}`).then(resp => {
                this.$emit('on-update-inventory', resp.data);
            });
        }
    }
};
</script>
