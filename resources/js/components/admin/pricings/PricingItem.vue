<template>
  <div class="pricing_item-container">
    <label for>
      Profit ({{ pricing.role.name }}) -
      <span class="font-weight-bold">{{ getProfit }}</span>
    </label>
    <div class="row">
      <div class="col-md-6">
        <input type="hidden" name="pricings[]" :value="pricing.id" />
        <div class="input-group input-group">
          <input
            type="text"
            class="form-control form-control-sm"
            placeholder="Waste"
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
          class="btn btn-sm btn-outline-dark"
          @click.prevent="onUpdatePricing"
        >
          <i class="fa fa-check"></i>
        </button>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    pricing: { required: true, default: () => [] },
    statuses: { required: true, default: () => [] },
    roles: { required: true, default: () => [] },
    pure_price: { required: true, default: () => 0 },
  },
  data() {
    return {
      form: {
        waste: this.pricing.amt,
        status_id: this.pricing.status_id,
        role_id: this.pricing.role_id,
      },
    };
  },
  methods: {
    onUpdatePricing() {
      if (this.form.waste) {
        axios
          .patch(`/wapi/pricings/${this.pricing.id}`, this.form)
          .then((resp) => {
            this.$emit("on-update-pricing", resp.data);
          });
      } else {
        axios.delete(`/wapi/pricings/${this.pricing.id}`).then((resp) => {
          this.$emit("on-delete-pricing", resp.data);
        });
      }
    },
  },
  computed: {
    getProfit() {
      let profit =
        this.pricing.status.slug == "percent"
          ? (this.pure_price * this.pricing.amt) / 100
          : this.pricing.amt;
      let amount = this.pure_price + profit;
      if (this.pricing.role.slug == "level-1") {
        amount = Math.ceil(amount / 10) * 10;
      } else if (this.pricing.role.slug == "level-2") {
        amount = Math.round(amount / 100) * 100;
      } else {
        amount = Math.ceil(amount / 100) * 100;
      }
      return amount;
    },
  },
};
</script>
