<template>
    <div class="add_cost_form-container">
        <label for class="text-label">Add Wastes(Amount or %)</label>
        <div class="row align-items-center">
            <div class="form-group col-md-8 mb-2">
                <div class="input-group">
                    <input
                        type="text"
                        id="waste"
                        class="form-control form-control-sm"
                        placeholder="Waste"
                        v-model="form.amt"
                    />
                    <div>
                        <select
                            class="form-select"
                            name="status_id"
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
            <div class="form-group col-md-4 mb-2">
                <button
                    class="btn btn-sm btn-secondary"
                    :disabled="form.waste === '' || form.status_id === ''"
                    @click.prevent="onAddWaste"
                >
                    Add Waste
                </button>
            </div>
        </div>
    </div>
</template>


<script>
export default {
    props: {
        statuses: { required: true, default: () => [] },
    },
    data() {
        return {
            form: {
                amt: "",
                status_id: this.statuses[0].id,
            },
        };
    },
    methods: {
        onAddWaste() {
            axios.post(`/wapi/wastes`, this.form).then((resp) => {
                this.form.amt = "";
                this.form.status_id = this.statuses[0].id;
                this.$emit("on-add-waste", resp.data);
            });
        },
    },
};
</script>
