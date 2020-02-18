<template>
    <div>
      <!-- Enable/Disable push notifications -->
      <!-- <button style="margin-bottom: 20px;"
        @click="togglePush"
        :disabled="pushButtonDisabled || loading"
        type="button" class="ui primary button"
        :class="{ 'ui primary button': !isPushEnabled, 'btn-danger': isPushEnabled }">
        {{ isPushEnabled ? 'Disable' : 'Enable' }} Push Notifications
      </button> -->

      <a id="create_form_icon" href="#" class="publish right floated ui primary button" @click="newNotif">
        <i class="plus icon"></i>Send Notification
      </a>
        <div id="create_form_modal" class="ui tiny modal">
            <div class="content">
            <form class="ui form" @submit.prevent="sendNotification">
                <div class="field">
                <label>Title of Notification</label>
                <input type="hidden" id="form_id" v-model="form.form_id">
                <input type="text" id="title" v-model="form.title" placeholder="Title of Notification">
                </div>
                <div class="field">
                <label>Body of the Notification</label>
                <textarea type="text" id="body" v-model="form.body" placeholder="Body of the Notification" rows="5"></textarea>
                </div>
                <div class="field">
                <label>Enter the particular URL you want to redirect to</label>
                <input type="url" id="website" v-model="form.website" placeholder="https://example.com">
                </div>
                <div class="field">
                <label>Choose Image to Use</label>
                <!-- <img :src="form.photo" alt="no image" width="50" height="50" class="mr-2"> -->
                <input type="file" accept="image/*"  name="photo" id="photo" @change="imgChange">
                <!-- <image-upload name="foobar" @loaded ="onLoad"></image-upload> -->
                    <!-- <input type="file"  id="" accept="image/*" @change="imgChange"> -->
                </div>

                <button
                :disabled="disabled" v-if="loading"
                type="submit" class="ui green button btn-send">
                Sending Notification...
                </button>

                <button v-else
                :disabled="disabled"
                type="submit" class="ui green button btn-send">
                Send Notification
                </button>

            </form>
            </div>
        </div>

        <!-- <send-notification :new_form = "form" @reloadMessages= "loadMessages"></send-notification> -->

        <br><br>
        
        <table class="ui table">
            <thead>
                <tr>
                    <th>Message Title</th>
                    <th>Send Date</th>
                    <th>Delivered</th>
                </tr>
            </thead>
            <tbody v-for = "message in messagesCurrent" :key="message.id">
                <tr>
                    <td>{{ message.title }}</td>
                    <td>{{ message.send_date }}</td>
                    <td>{{ message.delivered }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script>

// import SendNotification from './SendNotification';

export default {


    props: ['new_form'],

    // components: {
    //     SendNotification
    // },
    
    // data(){
    //     return {
    //         messagesCurrent: this.form.messages
    //     }
    // },

    data(){
      return {
            loading: false,
            isPushEnabled: false,
            pushButtonDisabled: true,
            messagesCurrent: this.new_form.messages,
            form: new Form({
            company: this.new_form.title,
            title: '',
            body: '',
            name: '',
            photo: '',
            website: '',
            form_id: this.new_form.id,
            })
        }
    },

    created(){
        // Fire.$on('reloadMessages', () => {
        //     this.loadMessages();
        // })
        this.loadMessages();
    },

    computed: {
      disabled(){
      return  this.form.title === '' || !this.form.title || this.form.body === '' || !this.form.body ||  this.form.website === '' || !this.form.website || this.form.photo === '' || this.loading 
    },
  },

    methods: {
        loadMessages(){
            axios.get('/reloadMessages').then((res) => {
                console.log('this latest message: ', res);
                this.messagesCurrent.unshift(res.data)
                // console.log(this.messagesCurrent);
                console.log(this.messagesCurrent);
            });
        },

        newNotif(){
        $('#create_form_modal').modal('show');
    },

     imgChange(e) {
       if(! e.target.files.length) return;
        let file = e.target.files[0];
        console.log(file);
        var reader = new FileReader();
          reader.onloadend = (file) => {
            this.form.photo = reader.result;
          }
          reader.readAsDataURL(file);
      },

      clicking(){
      console.log(this.$refs);
    },

    
    sendNotification () {
      this.loading = true

    this.form.post('/notifications')
        .catch(error => console.log(error))
        .then((resp) => {

          Fire.$emit('reloadMessage')

          // toast({
          //     type: 'success',
          //     title: 'Notification sent successfully'
          // }); 
          setTimeout(() => {
            $('#create_form_modal').modal('hide');
          }, 1000)
          this.form.company = '',
          this.form.title = '';
          this.form.body = '';
          this.form.website = '';
          console.log(resp)
          this.loading = false 
        })
    },
    }
}
</script>


<style scoped>
.btn {
  margin-right: 10px;
  margin-bottom: 10px;
}

</style>
