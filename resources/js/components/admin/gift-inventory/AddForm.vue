<template>
    <div class="add-form row">
        <div class="col-md-8">
            <div class="py-3">
                <div class="form-group position-relative">
                    <label for="">Gift Name</label>
                    <small class="help-text text-muted"
                        >အမည်ထည့်ပါ။ မထည့်၍မရပါ။</small
                    >
                    <input
                        type="text"
                        class="form-control form-control-sm"
                        placeholder="Search with name"
                        v-model="q"
                        @keyup="onSearch($event)"
                    />
                    <div class="results shadow bg-white" v-show="results.length">
                        <ul class="nav flex-column">
                            <li class="nav-item" v-for="res in results" :key="res.id">
                                <a href="#" @click.prevent="onChooseGift(res)" class="nav-link small text-muted">{{ res.name }}</a>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="form-group">
                    <label for="">Qty</label>
                    <small class="help-text text-muted"
                        >ပစ္စည်းအရေအတွက်ထည့်ပါ။ မထည့်၍မရပါ။</small
                    >
                    <input
                        type="text"
                        class="form-control form-control-sm"
                        placeholder="Qty"
                        v-model="form.qty"
                    />
                </div>

                <div class="form-group">
                    <label for="">Date</label>
                    <small class="help-text text-muted">ရက်စွဲထည့်ပါ။</small>
                    <input
                        type="date"
                        class="form-control form-control-sm"
                        v-model="form.date"
                        placeholder="Date"
                    />
                </div>

                <div class="form-group">
                    <button class="btn btn-sm btn-primary" @click.prevent="onAddInventory">Add</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        gift_id: {default : () => []}
    },
    data() {
        return {
            q: '',
            timeout: '',
            form: {
                gift_id: '',
                qty: 1,
                date: ''
            },
            gifts: [],
            results: [],
        }
    },
    created() {
        var today = new Date();
        var dd = today.toLocaleDateString("en-CA");
        this.form.date = dd;
        axios.get(`/wapi/gifts`).then(resp => {
            this.gifts = resp.data;
            resp.data.map(x => {
                if(x.id == this.gift_id) {
                    this.form.gift_id = x.id;
                    this.q = x.name;
                }
            });
        });
    },
    methods: {
        onAddInventory() {
            axios.post(`/wapi/gift-inventories`, this.form).then(resp => {
                this.$emit('on-add-inventory', resp.data);
            });
        },
        onSearch(event) {
            if(event.keyCode != 32  && this.q) {
                clearTimeout(this.timeout);
                this.timeout = setTimeout(()=>{
                    this.results = this.gifts.filter(x => {
                        return x.name.toLowerCase().includes(this.q.toLowerCase()) ? x : '';
                    });
                }, 300);
            }else {
                this.results = [];
            }
        },
        onChooseGift(res) {
            this.q = res.name;
            this.form.gift_id = res.id;
            this.results = [];
        }
    }
};
</script>

<style>
.results {
    position: absolute;
    width: 100%;
    max-height: 300px;
    overflow: auto;
    left: 0;
    z-index: 1;
}
</style>
