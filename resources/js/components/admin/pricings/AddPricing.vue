<template>
  <div class="add_pricing-container">
    <label for>Add Profits</label>
    <div class="row">
      <div class="col-md-6">
        <div class="input-group">
          <input
            type="text"
            class="form-control form-control-sm"
            placeholder="Profits"
            v-model="form.waste"
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
      <div class="col-md-4">
        <div class="form-group">
          <select v-model="form.role_id" class="form-select">
            <option v-for="role in roles" :key="role.id" :value="role.id">
              {{ role.name }}
            </option>
          </select>
        </div>
      </div>
      <div class="col-md-2">
        <button
          class="btn btn-sm btn-secondary"
          :disabled="form.waste == ''"
          @click.prevent="onAddPricing"
        >
          Add
        </button>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    statuses: { required: true, default: () => [] },
    roles: { required: true, default: () => [] },
  },
  data() {
    return {
      form: {
        role_id: this.roles[0].id,
        status_id: this.statuses[0].id,
        waste: "",
      },
    };
  },
  methods: {
    onAddPricing() {
      axios.post(`/wapi/pricings`, this.form).then((resp) => {
        this.$emit("on-add-pricing", resp.data);
        this.form.waste = "";
      });
    },
  },
};
</script>
