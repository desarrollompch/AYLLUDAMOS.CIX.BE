<template>
    <div class="card">
        <div class="card-header">
            Crear Directorio
        </div>
        <form action="" v-on:submit.prevent="newNivelAgua()">
            <div class="card-body">
                <directorio-form :directorio="directorio" :errors="errors"></directorio-form>
            </div>
            <div class="card-footer">
                <button class="btn btn-primary" type="submit" :disabled="loading">
                    Guardar
                </button>
                <a class="btn btn-danger" :href="cancel_url">
                    Cancelar
                </a>
            </div>
        </form>
    </div>
</template>

<script>
    export default {
       data(){
           return {
               directorio: {
                   territorio_vecinal_id: ''
               },
               errors: [],
               loading:false,
               cancel_url: route('directorio.index',0)
           }
       },
       mounted(){
         $('#mute').removeClass('on');
       },
        methods: {
            newNivelAgua(){
                this.loading = true;
                axios.post(route('directorio.store'), this.directorio).then( (response) =>{
                    if(response.data.success)
                    {
                        window.location.href = this.cancel_url;
                    }

                    this.loading = false;

                }).catch((error) =>{
                    if(error.response.status == 422)
                    {
                        this.errors = error.response.data.errors;
                    }

                    this.loading = false;

                })
            }
        }
    }
</script>