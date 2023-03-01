<template>
    <div class="sku_price-container">
        <div class="row form-group mb-3">
            <input type="hidden" name="currency_id" v-model="currency_id" />
            <div class="col-md-6">
                <label for>Exchange Rate</label>
                <input
                    type="text"
                    class="form-control form-control-sm disabled"
                    v-model="form.ex_rate"
                    disabled
                />
            </div>
            <div class="col-md-6">
                <label for>Division Rate</label>
                <input
                    type="text"
                    class="form-control form-control-sm disabled"
                    v-model="form.div_rate"
                    disabled
                />
            </div>
        </div>
        <div class="row form-group mb-3">
            <div class="col-12">
                <label class="mb-1">Price</label>
                <span v-show="form.buy_price" class="bg-light small ms-2 px-2 py-1">
                    {{ form.buy_price }} MMK
                </span>
                <div class="input-group">
                    <input
                        type="text"
                        class="form-control form-control-sm"
                        placeholder="Pure Price"
                        v-model="pur_price"
                        @change="onPurPriceChange"
                    />
                    <div>
                        <select
                            class="form-select"
                            name="currency"
                            @change="onChangeCurrency($event)"
                            v-model="currency_id"
                        >
                            <option
                                v-for="exchange_rate in exchange_rates"
                                :key="exchange_rate.id"
                                :value="exchange_rate.currency_id"
                            >
                                {{ exchange_rate.currency.name }}
                            </option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <Cost :costs="costs" :exchange_rates="exchange_rates" @on-update-cost="onUpdateCost" />

        <Wastes :wastes="wastes" :statuses="statuses" @on-update-waste="onUpdateWaste" />

        <div class="row align-items-center" v-if="form.pure_price">
            <div class="form-group col-md-8 mb-4">
                <label>Pure Price</label>
                <input
                    type="text"
                    :value="form.pure_price"
                    class="form-control form-control-sm disabled"
                    disabled
                />
            </div>
            <div class="form-group col-md-4">
                <button
                    class="btn btn-sm btn-danger"
                    :disabled="pur_price === ''"
                    @click.prevent="calculatePrice"
                >
                    Calculate Price
                </button>
            </div>
        </div>

        <PricingBox :pricings="pricings" :statuses="statuses" :roles="roles" :pure_price="form.pure_price" />

    </div>
</template>

<script>

import Cost from "../costs/Cost";
import Wastes from "../wastes/Wastes";
import PricingBox from "../pricings/PricingBox";

export default {
    components: {
        PricingBox,
        Wastes,
        Cost
    },
    props: {
        statuses: {required: true, default: () =>[]},
        exchange_rates: { required: true, default: () => [] },
        item: {required: true},
        roles: { required: true, default: () => [] },
    },
    data() {
        return {
            form: {
                rate: this.exchange_rates[0],
                ex_rate: this.isEditing() ? this.getExchangeRate(this.item.currency_id)[0].mmk : this.exchange_rates[0].mmk,
                div_rate: this.isEditing() ? this.getExchangeRate(this.item.currency_id)[0].rate : this.exchange_rates[0].rate,
                buy_price: 0,
                pure_price: 0,
                waste_status: this.statuses[0].slug,
            },

            currency_id: this.isEditing() ? this.item.currency_id : this.exchange_rates[0].currency_id,
            costs: this.isEditing() ? this.item.costs : [],
            wastes: this.isEditing() ? this.item.wastes : [],
            pricings: this.isEditing() ? this.item.pricings : [],
        }
    },
    methods: {
        isEditing() {
            return Object.keys(this.item).length;
        },

        onPurPriceChange() {
            this.form.buy_price = this.pur_price * this.form.ex_rate;
        },

        onChangeCurrency(event) {
            this.form.rate = this.exchange_rates.filter((rate) => {
                return rate.id === event.target.value;
            });
            this.form.ex_rate = this.form.rate[0].mmk;
            this.form.div_rate = this.form.rate[0].rate;
            this.currency_id = this.form.rate[0].currency_id;
            if (this.pur_price) {
                this.form.buy_price = this.getPurePrice();
            }
        },

        getPurePrice() {
            return this.pur_price * this.form.ex_rate;
        },

        onUpdateCost(data) {
            this.costs = data;
        },

        onUpdateWaste(data) {
            this.wastes = data;
        },

        calculatePrice() {
            this.form.pure_price = 0;
            this.form.pure_price += this.isEditing()
                ? this.getPurePrice()
                : this.form.buy_price;
            this.form.pure_price = this.getPureCost();
        },
    }
}
</script>
