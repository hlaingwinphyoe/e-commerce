<template>
    <div class="py-3">
        <div class="form-group mb-2 position-relative">
            <label class="text-muted">Name</label>
            <input
                type="text"
                class="form-control form-control-sm"
                placeholder="Customer Name"
                v-model="form.name"
                @keydown="onSearchCustomer"
            />
            <div
                class="result-data border bg-white shadow px-4 py-2"
                v-show="users.length"
            >
                <ul class="nav flex-column">
                    <li
                        class="nav-item"
                        v-for="user in users"
                        :key="user.id"
                        :id="user.id"
                    >
                        <a
                            href="#"
                            class="nav-link py-1 text-muted ps-2"
                            @click.prevent="
                                onSelectedUser(user.id, user.name, user.phone, user.email)
                            "
                        >
                            {{ user.name }} - <span>{{ user.phone }}</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="row">
            <div class="col-6">
                <div class="form-group mb-2">
                    <label class="text-muted">Phone</label>
                    <input
                        type="text"
                        class="form-control form-control-sm"
                        placeholder="Phone"
                        v-model="form.phone"
                    />
                </div>
            </div>
            <div class="col-6">
                <div class="form-group mb-2">
                    <label class="text-muted">Email</label>
                    <input
                        type="text"
                        class="form-control form-control-sm"
                        placeholder="Email"
                        v-model="form.email"
                    />
                </div>
            </div>
        </div>

        <div class="form-group mb-2">
            <label class="text-muted">Address</label>
            <textarea
                rows="3"
                class="form-control form-control-sm"
                v-model="form.address"
                placeholder="Address"
            ></textarea>
        </div>
        <div class="form-group mb-2">
            <label class="text-muted">Remark</label>
            <textarea
                rows="3"
                class="form-control form-control-sm"
                placeholder="Remark"
                v-model="form.remark"
            ></textarea>
        </div>
        <div class="from-group">
            <button
                class="btn btn-sm btn-primary"
                :class="isSave ? '' : 'disabled'"
                @click.prevent="onSaveCustomer"
            >
                Add Customer Info
            </button>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        order: { required: true }
    },
    data() {
        return {
            users: [],
            timeout: "",
            form: {
                name: this.order.customer ? this.order.customer.name : "",
                email: this.order.customer ? this.order.customer.email : "",
                phone: this.order.customer ? this.order.customer.phone : "",
                address: this.order.customer ? this.order.customer.address : "",
                remark: this.order.customer ? this.order.customer.remark : "",
                customer_id: ""
            }
        };
    },
    methods: {
        onSaveCustomer() {
            axios
                .patch(`/wapi/save-customer/${this.order.id}`, this.form)
                .then(resp => {
                    this.$emit("on-save-customer", resp.data);
                });
        },
        onSearchCustomer() {
            if (this.form.name) {
                clearTimeout(this.timeout);
                this.timeout = setTimeout(() => {
                    let param = {
                        name: this.form.name
                    };
                    axios.get(`/wapi/users`, { params: param }).then(resp => {
                        this.users = resp.data;
                    });
                }, 300);
            } else {
                this.users = [];
            }
        },
        onSelectedUser(id, name, phone, email) {
            this.form.customer_id = id;
            this.form.name = name;
            this.form.phone = phone;
            this.form.email = email;
            this.users = [];
        }
    },
    computed: {
        isSave() {
            // return this.form.name && this.form.phone && this.form.address;
            return this.form.name;
        }
    }
};
</script>

<style>
.result-data {
    width: 100%;
    max-height: 200px;
    overflow: auto;
    position: absolute;
    z-index: 1;
}
</style>
