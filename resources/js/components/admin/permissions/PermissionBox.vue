<template>
  <div class="permission-box">
      <ul class="nav flex-column">
          <li class="nav-item" v-for="(permis, type) in types" :key="type">
              <permission-list :type="type" :permissions="permis" :selected_permissions="selected_permissions"></permission-list>
          </li>
      </ul>
  </div>
</template>

<script>
import PermissionList from "./PermissionList.vue";
export default {
    components: {
        "permission-list" : PermissionList
    },
    props: {
        types: {required: true},
        selected_permissions: {required: true, default: () => []}
    }
}
</script>

<style>

</style>