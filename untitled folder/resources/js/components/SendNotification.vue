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

            <div class="field">
              <label style="margin-bottom: 10px;">When to Send:</label>
              <div class="ui">
                <span style="margin-right: 20px;">
                  <input type="radio" name="sending" value="now" v-model="form.item" checked>
                  <label>Send Now</label>
                </span>
                <span>
                  <input type="radio" name="sending" value="later" v-model="form.item">
                  <label>Send Later</label>
                </span>
              </div>
            </div>
            <div class="field" v-if="form.item === 'later'">
              <!-- <datepicker  
                v-model="form.send_date"
              >
              </datepicker> -->
              

                <!-- <datetime v-model="form.send_date" min-datetime="0"></datetime> -->
                <!-- <vue-timepicker></vue-timepicker> -->

                <VueCtkDateTimePicker v-model="form.send_date" />

            </div>

            <button
              :disabled="disabled" v-if="loading && form.item === 'now'"
              type="submit" class="ui green button btn-send">
              Sending Notification...
            </button>

            <button
              :disabled="disabled" 
              v-if="!loading && form.item === 'now'"
              type="submit" class="ui green button btn-send">
              Send Notification
            </button>

            <button  
              v-if="!loading && form.item === 'later'"
              :disabled="disabled"
              type="submit" 
              class="ui green button btn-send"
              >
              Send Later
            </button>
          </form>
        </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios'
// import ImageUpload from './ImageUpload.vue';
// import Datepicker from 'vuejs-datepicker';


export default {

  // components: {
  //   // ImageUpload
  //   Datepicker
  // },

  props: ['new_form'],

  data(){
      return {
        current: 0,
          send_now: true,
          loading: false,
          isPushEnabled: false,
          pushButtonDisabled: true,
          form: new Form({
          company: this.new_form.title,
          title: '',
          body: '',
          name: '',
          photo: '',
          website: '',
          form_id: this.new_form.id,
          send_date: '',
          send_to: '',
          item: 'now'
        })
      }
  },

  created(){
    console.log('the push: ', this.new_form.messages);
  },

  computed: {
      disabled(){
      return  this.form.title === '' || !this.form.title || this.form.body === '' || !this.form.body ||  this.form.website === '' || !this.form.website || this.form.photo === '' || this.loading 
    },
  },

  methods: {

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
          console.log(resp);
          // toast({
          //     type: 'success',
          //     title: 'Notification sent successfully'
          // }); 
          // setTimeout(() => {
            $('#create_form_modal').modal('hide');
          // }, 1000)
          this.form.company = '',
          this.form.title = '';
          this.form.body = '';
          this.form.website = '';
          console.log(resp)
          this.loading = false 
          // window.location.href = window.origin + '/pushes';
          window.location.reload(window.origin + '/pushes');
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
