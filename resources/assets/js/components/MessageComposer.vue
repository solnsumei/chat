<template>
  <div class="composer">
    <textarea v-model="message" @keydown.enter="send" placeholder="Message..."></textarea>
  </div>
</template>

<script>
  export default {
    data() {
      return {
        message: ''
      };
    },

    methods: {
      send(e) {
        e.preventDefault();
        if (this.message.trim() === '') {
          this.message = '';
          return;
        }

        this.$emit('send', this.message);
        this.message = '';
      }
    }
  }
</script>

<style lang="scss" scoped>
textarea {
  width: 97%;
  margin: 10px;
  resize: none;
  border-radius: 3px;
  border: 1px solid lightgrey;
  padding: 6px;
}
</style>