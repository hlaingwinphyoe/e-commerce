<template>
    <div class="waste_item-container mb-2">
        <input type="hidden" name="wastes[]" :value="waste.id" />
        <div>
            <span class="text-capitalize text-label">Waste</span>
        </div>
        <div class="row">
            <div class="form-group col-md-8 mb-2">
                <div class="input-group">
                    <input
                        type="text"
                        id="waste"
                        name="waste"
                        class="form-control form-control-sm"
                        placeholder="Waste"
                        v-model="form.amt"
                    />
                    <div>
                        <select
                            class="form-select"
                            v-model="form.status_id"
                        >
                            <option
                                v-for="status in statuses"
                                :key="status.id"
                                :value="status.id"
                            >
                                {{ status.name }}
                            </option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-2">
                <button class="btn btn-sm btn-secondary me-2" @click.prevent="onUpdateWaste">
                    <small>
                        <i class="fa fa-check"></i>
                    </small>
                </button>
                <button class="btn btn-sm btn-danger" @click.prevent="onDeleteWaste">
                    <small>
                        <i class="fa fa-trash"></i>
                    </small>
                </button>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        waste: { required: true, default: () => [] },
        index: { required: true, default: () => [] },
        statuses: { required: true, default: () => [] },
    },
    data() {
        return {
            currencies: [],
            form: {
                amt: this.waste.amt,
                status_id: this.waste.status_id,
            },
        };
    },
    methods: {
        onUpdateWaste() {
            if (this.form.amt) {
                axios.put(`/wapi/wastes/${this.waste.id}`, this.form).then((resp) => {
                    //console.log(resp.data)
                    this.$emit("on-update-waste", resp.data);
                });
            } else {
                axios.delete(`/wapi/wastes/${this.waste.id}`).then((resp) => {
                    this.$emit("on-destroy-waste", resp.data);
                });
            }
        },
        onDeleteWaste() {
            axios.delete(`/wapi/wastes/${this.waste.id}`).then((resp) => {
                this.$emit("on-destroy-waste", resp.data);
            });
        },
    },
};
</script>
