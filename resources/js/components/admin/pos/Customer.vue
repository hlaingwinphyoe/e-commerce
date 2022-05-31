<template>
    <div class="position-relative">
        <p class="me-2 mb-1">Customer Name</p>
        <div class="input-group input-group-sm mb-2 position-relative">
            <input
                type="text"
                class="form-control form-control-sm"
                placeholder="Customer Name"
                v-model="form.name"
                @keyup="onSearchCustomer"
            />
            <button class="btn btn-sm btn-primary" @click.prevent="onToggleBox">
                <i class="fa fa-plus"></i>
            </button>
        </div>

        <div v-show="users.length" class="absolute-box results px-2 py-3 bg-white border shadow rounded">
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

        <div
            class="cus-info absolute-box results px-2 py-3 bg-white border rounded shadow"
        >
            <div class="row">
                <div class="col-md-6">
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

                <div class="col-md-6">
                    <label class="text-muted">Address</label>
                    <textarea
                        rows="1"
                        class="form-control form-control-sm"
                        v-model="form.address"
                        placeholder="Address"
                    ></textarea>
                </div>

                <div class="from-group col-md-6">
                    <button
                        class="btn btn-sm btn-primary"
                        :class="isSave ? '' : 'disabled'"
                        @click.prevent="onSaveCustomer"
                    >
                        Add Customer Info
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        order: {required: true,}
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
            this.onSaveCustomer();
        },
        onToggleBox() {
            $('.cus-info').toggleClass('show');
        }
    },
    computed: {
        isSave() {
            return this.form.name;
        }
    }
}
</script>
