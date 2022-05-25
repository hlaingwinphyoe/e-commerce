<template>
    <div class="pricing_item-container row">
        <div class="form-group col-md-2">
            <label for="" class="small text-muted">Min</label>
            <input type="text" class="form-control form-control-sm" v-model="form.min_qty" required>
        </div>

        <div class="form-group col-md-2">
            <label for="" class="small text-muted">Max</label>
            <input type="text" class="form-control form-control-sm" v-model="form.max_qty">
        </div>

        <div class="form-group col-md-4">
            <label for="" class="small text-muted">Price</label>
            <input type="text" class="form-control form-control-sm form-control-price" v-model="form.price">
        </div>        
        <div class="form-group col-md-4 align-self-end">
            <a class="btn btn-sm btn-outline-primary me-2" @click.prevent="onUpdatePricing">
                <i class="fa fa-check"></i>
            </a>
            <a class="btn btn-sm btn-outline-danger" @click.prevent="onDeletePricing">
                <i class="fa fa-trash"></i>
            </a>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        pricing : {required: true}
    },
    data() {
      return {
          form : {
              price : this.pricing.amt,
              min_qty: this.pricing.min_qty,
              max_qty: this.pricing.max_qty ?? this.pricing.min_qty
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
}
</script>

<style>
.form-control-price {
    font-weight: bold;
    color: #3a90dc;
}
</style>