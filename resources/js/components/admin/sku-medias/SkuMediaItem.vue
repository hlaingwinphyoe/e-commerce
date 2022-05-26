<template>
  <div
    class="media-item mb-2 position-relative"
    :class="`media-item-${media.id}`"
  >
    <input type="hidden" name="featured[]" :value="media.id" />

    <div v-if="isImage" class="position-relative d-inline-block">
     <a
        :href="`#media-title-${media.id}`"
        class="text-muted d-inline-block px-4 py-2 border rounded"
        data-bs-toggle="modal"
      >
     
      <img
        :src="`/storage/thumbnail/${media.slug}`"
        :alt="media.name"
        class="featured-img"
      />
      </a>
      <div
        class="mx-1 position-absolute top-right shadow px-1"
        style="width: 15px; top: 2px; right: 0px"
      >
        <a href="#" class="text-secondary" @click.prevent="onDestroy()">
          <small><i class="fa fa-times"></i></small>
        </a>
      </div>
    </div>

    <div class="mr-1" v-else>
      <i class="far fa-file-alt"></i>
    </div>
    <div
      class="modal fade"
      :id="`media-title-${media.id}`"
      tabindex="-1"
      role="dialog"
    >
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header border-0 pb-0">
            <button
              type="button"
              class="close"
              data-bs-dismiss="modal"
              aria-label="Close"
            >
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body text-center">
            <div>
              <img
                :src="`/storage/thumbnail/${media.slug}`"
                :alt="media.name"
                width="100%"
              />
            </div>
          </div>
          <div class="modal-footer justify-content-start border-0">
            <button
              type="button"
              class="btn btn-sm btn-outline-secondary"
              data-bs-dismiss="modal"
            >
              Cancel
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    media: { required: true },
    images:{
      type: Array
    }
  },
  data() {
    return {
      type: ["jpg", "jpeg", "png", "gif", "bmp"],
      form: {
        title: this.media.title,
        priority: this.media.priority,
        images: this.images
      },
    };
  },
  methods: {
    onDestroy() {
      axios.delete(`/wapi/medias/${this.media.id}`).then((response) => {
        this.$emit("on-destroy-media", response.data);
      });
    },
  },
  computed: {
    isImage() {
      return $.inArray(this.media.ext, this.type) > 0 || this.media.ext == 'jpg' ? true : false;
    },
    getTitle() {
      return this.media.title ?? this.media.name;
    },
  },
};
</script>
<style scoped>
.check-radio{
    width: 20px;
    height: 20px;
    margin-top: 5px;
}
.featured-img {
  max-height: 65px;
}
</style>