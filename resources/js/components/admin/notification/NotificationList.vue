<template>
    <li class=" dropdown">
        <a
            href="#"
            class="nav-link dropdown-toggle text-white app-nav__item"
            data-bs-toggle="dropdown"
        >
            <i class="far fa-bell"></i>
            <small
                class="badge bg-secondary badge-sm position-absolute"
                v-if="count"
                style="top: 0px; right: 15px"
                >{{ count }}</small
            >
        </a>

        <div
            class="dropdown-menu dropdown-menu-right"
            style="width: 300px; box-shadow: 0px 1px 3px 2px rgba(0, 0, 0, 0.06)"
        >
            <div class="notification-item px-3 border-bottom">
                <span class="fw-bold p-3 d-flex">
                    <p
                        class="app-notification__message m-auto"
                        style="font-size: 15px; color: #2e2e2e"
                    >
                        You Have
                        <template v-if="count">
                            <small class="badge bg-secondary badge-sm">{{
                                count
                            }}</small>
                            Notifications
                        </template>
                        <template v-else>
                            No New Notifications
                        </template>
                    </p>
                </span>
            </div>
            <div class="app-notification__content" v-if="notifications.length">
                <notification-item
                    v-for="notification in notifications"
                    :key="notification.id"
                    :notification="notification"
                    @on-read-notification="onReadNotification"
                ></notification-item>
            </div>
            <div v-else>
                <li class="notification-item py-2 px-3 text-center text-muted">
                    No new notifications.
                </li>
            </div>
            <div class="notification-item pt-1 border-top text-center">
                <a href="/admin/notifications" class="small text-muted fw-bold"
                    >See All Notis.</a
                >
            </div>
        </div>
    </li>
</template>

<script>
import NotificationItem from "./NotificationItem.vue";

export default {
    props: {
        user_id: { required: true }
    },
    components: {
        "notification-item": NotificationItem
    },
    data() {
        return {
            notifications: [],
            count: ""
        };
    },
    created() {
        axios.get(`/wapi/get-order-noti`).then(response => {
            this.notifications = response.data.data;
            this.count = response.data.count;
        });
    },
    mounted() {
        Echo.private(`App.Models.User.${this.user_id}`).notification(
            notification => {
                this.notifications.unshift(notification);
                this.count = this.count + 1;
            }
        );
    },
    methods: {
        onReadNotification(data) {
            this.notifications = this.notifications.map(x =>
                x.id == data.id ? data : x
            );
            if (this.count > 0) {
                this.count = this.count - 1;
            }
        }
    }
};
</script>
<style scoped>
.app-notification__content {
    height: 220px;
    overflow-y: auto;
}
.app-notification__content::-webkit-scrollbar {
    width: 3px;
}
</style>
