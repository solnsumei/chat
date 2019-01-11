<template>
  <div class="chat-app">
    <Conversation :contact="selectedContact" :messages="messages" :token="user.api_token" @new="saveNewMessage"/>
    <ContactsList :contacts="contacts" @selected="startConversationWith"/>
  </div>
</template>

<script>
  import Conversation from './Conversation';
  import ContactsList from './ContactsList';

    export default {
      props: {
        user: {
          type: Object,
          required: true
        }
      },

      data() {
        return {
          selectedContact: null,
          messages: [],
          contacts: []
        };
      },
      mounted() {
        Echo.private(`messages-${this.user.id}`)
          .listen('NewMessage', (e) => {
            this.handleIncoming(e.message);
          });

        axios.get(`/contacts?api_token=${this.user.api_token}`)
          .then((response) => {
            this.contacts = response.data;
            this.startConversationWith(this.contacts[0])
          });
      },

      methods: {
        startConversationWith(contact) {

          this.updateUnreadCount(contact, true);

          axios.get(`/conversation/${contact.id}?api_token=${this.user.api_token}`)
            .then((response) => {
              this.messages = response.data;
              this.selectedContact = contact;
            });
        },

        saveNewMessage(text) {
          this.messages.push(text);
        },

        handleIncoming(message) {
          if (this.selectedContact && message.from === this.selectedContact.id) {
            return this.saveNewMessage(message);
          }

          this.updateUnreadCount(message.sender, false);

        },

        updateUnreadCount(contact, reset) {
          this.contacts = this.contacts.map((single) => {
            if (single.id !== contact.id) {
              return single;
            }

            if (reset) {
              single.unread = 0;
            } else {
              single.unread += 1;
            }

            return single;
          });
        }
      },

      components: { Conversation, ContactsList }
    }
</script>

<style lang="scss" scoped>
  .chat-app {
    display: flex;
  }
</style>