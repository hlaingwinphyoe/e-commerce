<template>
  <div class="notification-item py-2 px-3 d-flex border-bottom">
   <template v-if="notification.type=='App\\Notifications\\OrderCreatedToAdmin'">
     <div class="row w-100 align-items-center">
       <div class="col-3">
         <span class="btn btn-sm btn-outline-info"><i class="fa fa-cart-arrow-down"></i></span>
       </div>
       <div class="col-9">
         <a href="#" class="d-block text-decoration-none text-muted" @click.prevent="onOpen">
           <small class="me-1">{{ notification.data.customer.name }}</small>
           <small class="mm-font">မှ Order တင်ထားပါသည်။</small>
         </a>
         <div class="d-block mt-1 small text-muted">
          <span>{{ formatedDate }}</span>
        </div>
       </div>
     </div>
   </template>
   <template v-else-if="notification.type=='App\\Notifications\\OrderNotiToUser'">
     <div class="row w-100 align-items-center">
       <div class="col-3">
         <span class="btn btn-sm btn-outline-success"><i class="fa fa-check"></i></span>
       </div>
       <div class="col-9">
         <a href="#" class="d-block text-decoration-none text-muted" @click.prevent="onOpen">
           <small class="mm-font me-1">သင်၏ Order </small>
           <small class="me-1">{{ notification.data.order.order_no }}</small>
           <small class="mm-font">အား လက်ခံရရှိပါသည်။</small>
         </a>
         <div class="d-block mt-1 small text-muted">
          <span>{{ formatedDate }}</span>
        </div>
       </div>
     </div>
   </template>

   <template v-else-if="notification.type=='App\\Notifications\\GiftRequestedToAdmin'">
     <div class="row w-100 align-items-center">
       <div class="col-3">
         <span class="btn btn-sm btn-outline-info"><i class="fa fa-gift"></i></span>
       </div>
       <div class="col-9">
         <a href="#" class="d-block text-decoration-none text-muted" @click.prevent="onOpen">
           <small class="me-1">{{ notification.data.user.name }}</small>
           <small class="mm-font me-1">မှ</small>
           <small class="me-1">{{ notification.data.gift.name }}</small>
           <small class="mm-font">အား လဲလှယ်ရန် request လုပ်လာပါသည်။</small>
         </a>
         <div class="d-block mt-1 small text-muted">
          <span>{{ formatedDate }}</span>
        </div>
       </div>
     </div>
   </template>

   <template v-else-if="notification.type=='App\\Notifications\\GiftAcceptedToUser'">
     <div class="row w-100 align-items-center">
       <div class="col-3">
         <span class="btn btn-sm btn-outline-success"><i class="fa fa-gift"></i></span>
       </div>
       <div class="col-9">
         <a href="#" class="d-block text-decoration-none text-muted" @click.prevent="onOpen">
           <small class="me-1">{{ notification.data.gift.name }}</small>
           <small class="mm-font" v-if="notification.data.status == 'order-confirmed' || notification.data.status == 'completed'">အား လဲလှယ်ခွင့် ရပါသည်။</small>
           <small class="mm-font" v-else-if="notification.data.status == 'cancel'">အား လဲလှယ်ခွင့် မရသေးပါ။</small>
         </a>
         <div class="d-block mt-1 small text-muted">
          <span>{{ formatedDate }}</span>
        </div>
       </div>
     </div>
   </template>

  </div>
</template>

<script>
import moment from "moment";

export default {
  props: {
    notification: { required: true }
  },
  data() {
    return {
      //
    };
  },
  computed: {
    formatedDate() {
      let now = moment();
      let date = moment(this.notification.created_at);
      return date.from(now);
    }
  },
  methods: {
    onOpen() {
        if (!this.notification.read_at) {
          axios
            .patch(`/wapi/mark-as-read/${this.notification.id}`)
            .then(response => {
              this.$emit("on-read-notification", response.data);
            })
            .then(() => {
              if (
                this.notification.type ==
                "App\\Notifications\\OrderCreatedToAdmin"
              ) {
                window.location = `/admin/orders/${this.notification.data.order.id}/edit`;
              }else if (
                this.notification.type ==
                "App\\Notifications\\OrderNotiToUser"
              ) {
                window.location = `/admin/user-orders/${this.notification.data.order.id}/edit`;
              }else if(this.notification.type == "App\\Notifications\\GiftRequestedToAdmin") {
                window.location = `/admin/gift-logs?q=${this.notification.data.gift.name}&user_name=${this.notification.data.user.name}`;
              }else if(this.notification.type == "App\\Notifications\\GiftAcceptedToUser") {
                window.location = `/admin/user-gifts-show?q=${this.notification.data.gift.name}`;
              }
            });
        } 
      }
  }
};
</script>
