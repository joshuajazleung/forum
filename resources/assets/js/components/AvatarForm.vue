<template>
    <div class="page-header">
        <div class="d-flex align-items-center">
            <img :src="avatar" alt="Avatar" width="40" height="40" class="mr-1">
            <h1 v-text="user.name"></h1>
        </div>

        <form v-if="canUpdate" method="POST" enctype="multipart/form-data">
            <image-upload name="avatar" @loaded="onLoad"></image-upload>
        </form>

    </div>
</template>

<script>
import ImageUpload from "./ImageUpload.vue";

export default {
  props: ["user"],
  components: { ImageUpload },
  data() {
    return {
      avatar: this.user.avatar_path
    };
  },
  computed: {
    canUpdate() {
      return this.authorize(user => user.id === this.user.id);
    }
  },
  methods: {
    onLoad(avatar) {
      this.avatar = avatar.src;
      this.persist(avatar.file);
    },

    persist(avatar) {
      let data = new FormData();

      data.append("avatar", avatar);

      axios
        .post(`/api/users/${this.user.name}/avatar`, data)
        .then(res => flash("Avatar uploaded!"));
    }
  }
};
</script>