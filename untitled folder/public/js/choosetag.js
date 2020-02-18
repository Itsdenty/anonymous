Vue.component('choose', {

    name: 'choose',

    props: ['thetags'],

    data(){
        return {
            options: this.thetags,
            form: new Form({
                contact_tags: [],
            }),
        }
    },

    methods: {
        addTag (newTag) {
            const tag = {
                name: newTag,
                id: newTag.substring(0, 2) + Math.floor((Math.random() * 10000000))
            }
            this.options.push(tag)
            this.contact_tags.push(tag)
            // console.log(this.contact_tags);
        },
    }
});


new Vue ({
    el: '#noti',
});
