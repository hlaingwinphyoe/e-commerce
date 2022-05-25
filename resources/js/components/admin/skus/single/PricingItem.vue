<template>
    <div class="pricing_item-container row">
        <div class="form-group col-md-2">
            <label for="" class="small text-muted">အနည်းဆုံး အရေအတွက်</label>
            <input type="number" class="form-control form-control-sm" v-model="form.min_qty" required>
        </div>
        <div class="form-group col-md-2">
            <label for="" class="small text-muted">အများဆုံး အရေအတွက်</label>
            <input type="number" class="form-control form-control-sm" v-model="form.max_qty">
        </div>
        <div class="form-group col-md-2">
            <label for="" class="small text-muted">ဈေးနှုန်း</label>
            <input type="text" class="form-control form-control-sm" v-model="form.price">
        </div>       
        <div class="form-group col-md-2 align-self-end">
            <button class="btn btn-sm btn-primary me-2" @click.prevent="onUpdatePricing">
                <i class="fa fa-check"></i>
            </button>
            <button class="btn btn-sm btn-secondary" @click.prevent="onDeletePricing">
                <i class="fa fa-trash"></i>
            </button>
        </div>        
    </div>
</template>

<script>
export default {
  props: {
    pricing: { required: true, default: () => [] },
  },
  data() {
      return {
          form : {
              price : this.pricing.amt,
              min_qty: this.pricing.min_qty,
              max_qty: this.pricing.max_qty,
          }
      }
  },
  methods: {
      onUpdatePricing() {
          axios.patch(`/wapi/sku-pricings/${this.pricing.id}`, this.form).then(resp => {
              this.$emit('on-update-pricing', resp.data);
          });
      },
      onDeletePricing() {
          axios.delete(`/wapi/sku-pricings/${this.pricing.id}`).then(resp => {
              this.$emit('on-delete-pricing', resp.data);
          });
      }
  },
  computed: {
        getEstimate() {
            let price = this.pricing.exchange_rate * this.form.price;

            let mod = price % 1000;

            if(mod > 500 || mod < 500) {
                price = Math.round(price / 1000) * 1000;
            }

            return new Intl.NumberFormat().format(price);
        }
    }
};
</script>
