<template>
  <div
    class="media-item mb-2 position-relative"
    :class="`media-item-${media.id}`"
  >
    <input type="hidden" name="featured[]" :value="media.id" />

    <div class="d-flex mb-2">
      <div v-if="priority">
          <div class="form-check">
              <input class="form-check-input" name="check" type="radio" :id="'check-'+media.id" @click="onChoose(media.id)" :value="media.id" :checked="media.is_check ? true : false">
              <label class="form-check-label" :for="'check-'+media.id">
                  ရွေးချယ်မည်
              </label>
          </div>
      </div>
      <a
        :href="`#media-title-${media.id}`"
        class="text-muted text-decoration-none"
        data-bs-toggle="modal"
      >
        <span class="mx-2">{{ getTitle }}</span>
        <small><i class="fa fa-pencil-alt text-dark"></i></small>
      </a>

      <div
        class="mx-1 position-absolute top-right bg-white px-1"
        style="width: 15px; top: 2px; right: 15px"
      >
        <a href="#" class="btn btn-sm btn-danger" @click.prevent="onDestroy()">
          <small><i class="fa fa-trash"></i></small>
        </a>
      </div>
    </div>

    <div v-if="isImage" style="max-width: 300px">
      <img
        :src="getSrc"
        :alt="media.name"
        width="200"
      />
    </div>

    <div v-else-if="isAudio">
      <audio controls>
        <source :src="getSrc" type="audio/mpeg">
        Your browser does not support the audio element.
      </audio>
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
              data-dismiss="modal"
              aria-label="Close"
            >
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body text-center">
            <div>
              <img
                :src="getSrc"
                :alt="media.name"
                width="100%"
              />
            </div>
            <div class="form-group mt-3">
              <label for="" class="d-block text-left">Add Label</label>
              <input
                type="text"
                placeholder="Image Label"
                v-model="form.title"
                class="form-control form-control-sm"
              />
            </div>
            <div class="form-group">
              <label for="" class="d-block text-left">Change Priority</label>
              <input
                type="text"
                placeholder="Image Priority"
                v-model="form.priority"
                class="form-control form-control-sm"
              />
            </div>
          </div>
          <div class="modal-footer justify-content-start border-0">
            <button
              type="button"
              class="btn btn-sm btn-outline-secondary"
              data-dismiss="modal"
            >
              Cancel
            </button>

            <button
              class="btn btn-sm btn-primary"
              @click.prevent="onUpdateTitle"
            >
              Update
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
    priority: {},
    images:{
      type: Array
    }
  },
  data() {
    return {
      type: ["jpg", "jpeg", "png", "gif", "bmp"],
      audio_type: ["mp3", "ogg"],
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
    onUpdateTitle() {
      axios.patch(`/wapi/medias/${this.media.id}`, this.form).then((resp) => {
        $(`#media-title-${this.media.id}`).modal("hide");
        this.$emit("on-update-title", resp.data);
      });
    },
    onChoose(id){
      axios.patch(`/wapi/medias/check/${id}`,this.form).then((resp) => {
      });
    }
  },
  computed: {
    isImage() {
      let result = this.type.filter(t => { return this.media.ext == t});
      return result.length ? true : false;
    },
    isAudio() {
      let result =  this.audio_type.filter(t => { return this.media.ext == t});
      return result.length ? true : false;
    },
    getTitle() {
      return this.media.title ?? this.media.name;
    },
    getSrc() {
      return this.media.type == 'category-icon' ? this.media.url : `/storage/thumbnail/${this.media.slug}`;
    }
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
  max-height: 150px;
}
</style>
