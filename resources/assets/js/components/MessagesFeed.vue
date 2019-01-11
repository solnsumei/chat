<template>
  <div class="feed" ref="feed">
    <ul v-if="contact">
      <li v-for="message in messages" :class="`message${message.to === contact.id ? ' sent' : ' received'}`" @key="message.id">
        <div class="text">
          {{message.text}}
        </div>
      </li>
    </ul>
  </div>
</template>

<script>
  export default {
    props: {
      contact: {
        type: Object,
      },
      messages: {
        type: Array,
        required: true,
      }
    },
    methods: {
      scrollToBottom() {
        setTimeout(() => {
          this.$refs.feed.scrollTop = this.$refs.feed.scrollHeight - this.$refs.feed.clientHeight;
        }, 50);
      }
    },
    watch: {
      contact(contact) {
        this.scrollToBottom();
      },
      messages(messages) {
        this.scrollToBottom();
      }
    }
  }
</script>

<style lang="scss" scoped>
.feed {
  background-color: #f0f0f0;
  height: 100%;
  max-height: 470px;
  overflow: scroll;

  ul {
    list-style-type: none;
    padding: 5px;

    li {
      &.message {
        margin: 10px 0;
        width: 100%;

        .text {
          max-width: 280px;
          padding: 12px;
          display: inline-block;

          .sender {
            font-weight: bold;
          }
        }

        &.received {
          text-align: left;

          .text {
            border-top-right-radius: 5px;
            border-bottom-right-radius: 5px;
            background-color: lightgrey;
          }
        }

        &.sent {
          text-align: right;

          .text {
            border-top-left-radius: 5px;
            border-bottom-left-radius: 5px;
            background-color: lightblue;
          }
        }
      }
    }
  }
}
</style>