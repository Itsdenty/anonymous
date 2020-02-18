<template>
    <input type="file"  id="" accept="image/*" @change="onChange">
</template>


<script>
    
    export default {

        methods: {
            onChange(e){
                
                if(! e.target.files.length) return;

                let file = e.target.files[0]; //the first file

                let reader = new FileReader();

                reader.readAsDataURL(file);

                //since it will take some time to load
                //so, we trigger a function once it is loaded

                reader.onload = e => {
                    let src = e.target.result; 
                    this.$emit('loaded', {src, file}); 

                }
            }
        }
    }
</script>
