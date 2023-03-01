<template>
    <div class="discount_add_form-container mb-2">
        <div class="row align-items-center">
            <div class="col-md-2 d-none mb-2">
                <select class="form-select form-select-sm" v-model="form.role_id">
                    <option v-for="role in roles" :key="role.id" :value="role.id">{{ role.name }}</option>
                </select>
            </div>
            <div class="col-md-2 mb-2">
                <select class="form-select form-select-sm" v-model="form.discountype_id" @change="onChangeDiscount($event)">
                    <option v-for="discountype in discountypes" :key="discountype.id" :value="discountype.id">{{ discountype.name }}</option>
                </select>
            </div>
            <div class="col-md-4 mb-2">
                <div class="input-group">
                    <input type="text" placeholder="Amount" class="form-control form-control-sm" v-model="form.amt">
                    <div>
                        <select class="form-select form-select-sm" v-model="form.status_id">
                            <option v-for="status in statuses" :key="status.id" :value="status.id">{{ status.name }}</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-md-2 mb-2">
                <input type="date" placeholder="End Date" class="form-control form-control-sm" v-model="form.end_date">
            </div>
            <div class="col-md-2 mb-2">
                <button class="btn btn-sm btn-secondary" @click.prevent="onAddDiscount">Add Discount</button>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        discountypes: {required: true, default: () => []},
        roles: {required: true, default: () => []},
        statuses: {required: true, default: () => []}
    },
    data() {
        return {
            form: {
                role_id : this.roles.length ? this.roles[0].id : 4,
                discountype_id: this.discountypes.length ? this.discountypes[0].id : '',
                amt: this.discountypes.length ? this.discountypes[0].amt : '',
                status_id: this.discountypes.length ? this.discountypes[0].status_id: '',
                end_date: this.discountypes.length ? this.discountypes[0].end_date : ''
            }
        }
    },
    methods: {
        onAddDiscount() {
            axios.post(`/wapi/item-discounts`, this.form).then(resp => {
                this.$emit('on-add-discount', resp.data);
            });

        },
        onChangeDiscount(event) {
            let discount = this.discountypes.filter(x => {
                return x.id == event.target.value;
            });
            this.form.amt = discount[0].amt;
            this.form.status_id = discount[0].status_id;
            this.form.end_date = discount[0].end_date;
        }
    }
}
</script>
