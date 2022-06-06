<template>
    <div class="bg-white border rounded mb-4 px-2 py-3">
        <h6 class="text-primary">Customer Information</h6>
        <div class="d-flex">
            <div class="w-100">
                <span class="text-muted me-2">Name -</span>
                <span class="fw-bold">{{
                    data_order.customer ? data_order.customer.name : ""
                }}</span>
            </div>
            <div class="text-end">
                <a href="#" @click.prevent="onToggle"
                    ><i class="fa fa-angle-down"></i
                ></a>
            </div>
        </div>
        <div class="collapse" id="info">
             <div class="py-4">
                <div class="row">
                    <div class="col-md-6 form-group">
                        <label class="text-muted small">Name</label>
                        <input type="text" class="form-control form-control-sm" v-model="form.name" />
                    </div>
                    <div class="col-md-6 form-group">
                        <label class="text-muted small">Phone</label>
                        <input type="text" class="form-control form-control-sm" v-model="form.phone" />
                    </div>
                    <div class="col-md-6 form-group">
                        <label class="text-muted small">Address</label>
                        <textarea class="form-control form-control-sm" rows="3" placeholder="Address" v-model="form.address"></textarea>
                    </div>
                    <div class="col-md-6 form-group">
                        <label class="text-muted small">Remark</label>
                        <textarea class="form-control form-control-sm" rows="3" placeholder="Remark" v-model="form.remark"></textarea>
                    </div>
                    <div class="col form-group">
                        <button type="button" class="btn btn-sm btn-primary" @click.prevent="onUpdateInfo">Update Info</button>
                    </div>
                </div>             
            </div>
        </div>
       
    </div>
</template>

<script>
export default {
    props: {
        order: { required: true },
    },
    data() {
        return {
            collapse: '',
            data_order: this.order,
            form: {
                name: this.order.customer ? this.order.customer.name : '',
                phone: this.order.customer ? this.order.customer.phone : '',
                address: this.order.customer ? this.order.customer.address : '',
                remark: this.order.customer ? this.order.customer.remark : '',
            }
        }
    },
    mounted() {
        this.collapse = new bootstrap.Collapse('#info',{
            toggle: false
        });
        if(this.$isMobile()) {
            this.collapse.hide();
        }else {
            this.collapse.show();
        }        
    },
    methods: {
        onToggle() {
            this.collapse.toggle();
        },
        onUpdateInfo() {
            axios.patch(`/wapi/save-customer/${this.order.id}`, this.form).then(resp => {
                this.data_order = resp.data;
            });
        }
    }
    
};
</script>
