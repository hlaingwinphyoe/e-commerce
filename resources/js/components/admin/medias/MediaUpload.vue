<template>
  <div class="box mb-3">
    <div class="row ">
      <div v-show="type=='category'" class="col-3">
        <a href="#" class="d-flex flex-column align-items-center justify-content-center bg-light text-center border px-2 py-3" @click.prevent="show_icon=!show_icon">
          <span class="text-danger"><i class="fa fa-seedling fa-lg"></i></span>
          <small>Upload Icon</small>
        </a>
        <div v-if="!show_icon && icon_name != ''" class="py-2">
          <img :src="`/images/icons/${icon_name}`" :alt="icon_name" style="max-height: 100px; max-width: 100%;">
        </div>
      </div>
      <div :class="type == 'category' ? 'col-9' : 'col-12'">
        <div id="feature-box" class="collapse show">
          <div class="box-body upload-btn-wrapper py-2">
            <button class="upload-btn bg-light border w-auto text-primary">
              <span class="me-2 text-secondary action-btn"><i class="fas fa-images"></i></span>
              <small>ပုံတင်ရန် ဤနေရာကိုနှိပ်ပါ။</small>
            </button>
            <input
              type="file"
              name="media"
              ref="media"   
              multiple       
              @change="onChange($event.target.files)"
            />
            <small class="form-text text-muted">jpg,jpeg,png,gif,webp (File Type) များသာလက်ခံပါသည်။</small>         
            <small class="text-muted" v-if="priority">
              ပုံတစ်ပုံထက် ပို၍တင်ပါက အဓိကပုံအား ရွေးချယ်ပေးခဲ့ပါ။
            </small> 
            
          </div>

          <div v-if="error"><small class="bg-secondary-light px-3 rounded fw-bold">{{error}}</small></div>
          
          <!-- loading -->
          <template v-if="filesList.length">
            <div class="box-body">
              <div
                class="progress mb-2"
                v-for="media in filesList"
                :key="media.name"
                :style="`height:8px`"
              >
                <div
                  class="progress-bar progress-bar-striped progress-bar-animated bg-dark h-100"
                  :style="`width:${media.percentage}%`"
                  :aria-valuenow="media.percentage"
                  :aria-valuemin="0"
                  :aria-valuemax="100"
                ></div>
              </div>
            </div>
          </template>
          <!-- loading -->
          <!-- media list -->
          <template v-if="medias.length">
            <div class="box-body py-2 media-item-container">
              <media-item
                v-for="media in medias"
                :key="media.id"
                :media="media"
                :priority="priority"
                :images="images"
                @on-destroy-media="onDestroyMedia"
                @on-update-title="onUpdateTitle"
              ></media-item>
            </div>
          </template>
          <!-- media list -->
        </div>
      </div>
    </div>  

    <template v-show="show_icon">
      <div class="icon-list-container" :class="show_icon ? 'show' : ''">
        <div class="icon-lists">
        <div class="header">
          <div class="text-right mb-2">
            <a href="#" class="text-danger px-2 py-1 rounded border" @click.prevent="show_icon=!show_icon" style="border-radius: 2px solid">
              <i class="fa fa-times"></i>
            </a>
          </div>
          <h5 class="mb-4 bg-light text-primary p-2 rounded border">Choose Icons</h5>          
          <div class="d-flex flex-wrap" v-show="icon_images.length">
            <div v-for="icon in icon_images" :key="icon" class="mb-2 me-2">
              <div class="custom-checkbox custom-control">
                <input type="radio" name="icon_name" :id="`radio-${icon}`" :value="icon" class="custom-control-input" v-model="icon_name">
                <label :for="`radio-${icon}`" class="custom-control-label">
                  <img :src="`/images/icons/${icon}`" :alt="icon" style="max-height: 100px">
                </label>
              </div>              
            </div>
          </div>
          <div class="text-right">
            <a href="#" class="btn btn-sm btn-danger me-2" @click.prevent="show_icon = !show_icon">Cancel</a>
            <a href="#" class="btn btn-sm btn-primary" @click.prevent="show_icon = !show_icon">Upload</a>
          </div>
        </div>  
      </div>  
      </div>
    </template>  
  </div>
</template>

<script>
import MediaItem from "./MediaItem.vue";

export default {
  props: {
    images: {
      required: true,
      type: Array,
      default: () => [],
    },
    type: { default: () => "images" },
    priority:{
       type: String,
       default: ""
    }
  },
  components: {
    "media-item": MediaItem,
  },
  data() {
    return {
      loading: false,

      // medias: this.images ? this.images : [],
      medias: [],
      error: null,

      filesList: [],
      show_icon: false,
      icon_images: [],
      icon_name: ''
    };
  },
  created() {
    this.images.map((img_id) => {
      axios.get(`/wapi/medias/${img_id}`).then((resp) => {
        this.medias.push(resp.data);
      });
    });
  },
  watch: {
    show_icon: function(val){
      if(val && this.icon_images.length == 0) {
        axios.get(`/wapi/get-icons`).then(resp => {
          this.icon_images = resp.data;
        });
      }      
    },
  },
  methods: {
    onChange(files) {
      if (!files.length) return;

      Array.from(files).map((x) => {
        this.filesList.push({
          name: x.name,
          percentage: 50,
          file: x,
        });
      });      

      this.submit();
    },
    submit() {
      this.loading = true;

      let request = this.filesList.map((item) => {
        return new Promise((resolve, reject) => {
          let form = new FormData();
          form.append("media", item.file);
          form.append("type", this.type);
          let config = {
            onUploadProgress: (event) => {              
              let percent = Math.round((event.loaded * 100) / event.total);
              item.percentage = percent;
              if (item.percentage >= 100) {
                item.color = "#26a69a";
              }
            },
          };

          axios
            .post(`/wapi/medias`, form, config)
            .then((response) => {
              console.log(response.data);
              this.filesList = this.filesList.filter(
                (x) => x.name != item.name
              );
              this.medias.push(response.data);
              this.$emit("media-updated", this.medias);
              resolve();
            })
            .catch((error) => {
              this.error = error.response.data.errors['media'][0];
              reject();
            });
        });
      });

      Promise.all([request])
        .then(() => {
          this.loading = false;
          this.$refs.media.value = "";
        })
        .catch((error) => {
          this.loading = false;
          this.$refs.media.value = "";
        });
    },
    onDestroyMedia(data) {
      this.medias = this.medias.filter((x) => {
        return x.id != data.id;
      });
      //   this.$emit("media-updated", this.medias);
    },
    onUpdateTitle(data) {
      this.medias = this.medias.map((x) => {
        return x.id == data.id ? data : x;
      });
    },
  },
};
</script>

<style>
.upload-btn-wrapper {
  position: relative;
  overflow: hidden;
  display: inline-block;
}
.upload-btn {
  width: 100%;
  display: flex;
  /* flex-direction: column; */
  align-items: center;
  color: gray;
  background-color: transparent;
  /* padding: 8px 20px; */
  font-size: 20px;
  font-weight: bold;
  border: 0;
}
.upload-btn:hover {
  cursor: pointer;
}

.upload-btn-wrapper input[type="file"] {
  font-size: 100px;
  position: absolute;
  left: 0;
  top: 0;
  opacity: 0;
  width: 100%;
  height: 100%;
}
.slide-box .media-item-container{
  display: flex;
  flex-wrap: wrap;
}
.slide-box .media-item-container .media-item {
  width: 45%;
  padding: 15px;
  margin-right: 15px;
  border: 1px solid #efefef;
}
blockquote.primary {
    border-width: 1px 1px 1px 7px;
    border-style: solid;
    border-color: rgb(252, 68, 69);
    border-image: initial;
    border-left: 7px solid rgb(252, 68, 69);
}

.icon-list-container {
  position: fixed;
  width: 100%;
  height: 100vh;
  background: rgba(0,0,0,.5);
  top: 0;
  left: 0;
  z-index: 99999;
  display: none;
  align-items: center;
  justify-content: center;
}
.icon-list-container.show {
  display: flex;
}
.icon-lists {
  min-width: 50%;
  max-width: 90%;
  background: white;
  padding: 15px 25px;
  max-height: 90vh;
  overflow: auto;
}
@media (min-width: 992px) {
  .icon-lists {
    max-width: 75%;
  }
}
.icon-lists .custom-checkbox .custom-control-input:indeterminate ~ .custom-control-label::before {
  border-color: #969696;
  background-color:transparent;
}

</style>
