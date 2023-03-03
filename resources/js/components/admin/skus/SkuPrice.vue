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
                        name="pure_price"
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

        <Wastes :wastes="wastes" :statuses="statuses" @on-updates-waste="onUpdateWaste" />

        <div class="row align-items-center">
            <div class="form-group col-md-8 mb-4" v-if="form.pure_price">
                <label>Pure Price</label>
                <input
                    type="text"
                    :value="form.pure_price"
                    v-model="form.pure_price"
                    class="form-control form-control-sm"
                    id="pure_price"
                />
            </div>
            <div class="form-group col-md-4">
                <button
                    class="btn btn-sm btn-secondary"
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
import {isProxy, toRaw} from "vue";

export default {
    components: {
        PricingBox,
        Wastes,
        Cost
    },
    props: {
        statuses: {required: true, default: () =>[]},
        exchange_rates: { required: true, default: () => [] },
        item: {required: true, default: () => []},
        roles: { required: true, default: () => [] },
    },
    data() {
        return {
            form: {
                rate: this.exchange_rates[0],
                ex_rate: this.isEditing() ? this.getExchangeRate(this.item.currency_id)[0].mmk : this.exchange_rates[0].mmk,
                div_rate: this.isEditing() ? this.getExchangeRate(this.item.currency_id)[0].rate : this.exchange_rates[0].rate,
                buy_price: 0,
                // pure_price: 0,
                pure_price: this.isEditing() ? this.item.price : 0,
                waste_status: this.statuses[0].slug,
            },

            waste: this.isEditing() && this.item.wastes.length ? this.item.wastes[0].amt : "",
            status_id: this.isEditing() && this.item.wastes.length ? this.item.wastes[0].status_id : this.statuses[0].id,

            currency_id: this.isEditing() ? this.item.currency_id : this.exchange_rates[0].currency_id,
            pur_price: this.isEditing() ? this.item.pure_price : 0,
            costs: this.isEditing() ? this.item.costs : [],
            wastes: this.isEditing() ? this.item.wastes : [],
            pricings: this.isEditing() ? this.item.pricings : [],
        }
    },
    created() {
        this.calculatePrice();
        console.log(this.getPureCost())
    },
    methods: {
        isEditing() {
            return Object.keys(this.item).length;
        },

        getExchangeRate(currency_id) {
            return this.exchange_rates.filter((ex_rate) => {
                return ex_rate.currency_id == currency_id;
            });
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

        onChangeStatus(event) {
            let status = this.statuses.filter((status) => {
                return status.id == event.target.value;
            });
            this.form.waste_status = status[0].slug;
        },

        getRawCost() {
            let costs = 0;
            if (this.costs.length) {
                costs = this.costs.reduce((acc, cost) => {
                    let ex_rate = this.exchange_rates.find((rate) => {
                        return rate.currency_id === cost.currency_id;
                    });
                    return acc + cost.amt * ex_rate.mmk;
                }, 0);
            }
            return this.getPurePrice() + costs;
        },
        getWastes() {
            let wastes = 0;
            if (this.waste) {
                wastes = this.form.waste_status == "percent"
                        ? (this.getRawCost() * this.waste) / 100
                        : this.waste;
            }
            return wastes;
        },

        getPurePrice() {
            return this.pur_price * this.form.ex_rate;
        },

        onUpdateCost(data) {
            this.costs = data;
        },

        onUpdateWaste(data) {
            if(isProxy(data)){
                this.wastes = toRaw(data)
            }
        },

        getPureCost() {
            return this.getRawCost() + this.getWastes();
        },

        calculatePrice() {
            this.form.pure_price = 0;
            this.form.pure_price += this.isEditing()
                ? this.getPurePrice()
                : this.form.buy_price;
            this.form.pure_price = this.getPureCost();

            // console.log($("#pure_price").val())
        },
    }
}
</script>
