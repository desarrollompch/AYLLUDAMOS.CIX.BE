<template>
    <div class="card">
        <div class="card-header">
            Crear Nivel Ciudadano
        </div>
        <form action="" v-on:submit.prevent="newNivelCudadano()">
            <div class="card-body">
                <nivel-ciudadano-form :nivelCiudadano="nivelCiudadano" :errors="errors"></nivel-ciudadano-form>
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
               nivelCiudadano: {
                   descripcion: '',
                   src_icono:''
               },
               errors: [],
               loading:false,
               cancel_url:route('nivel-ciudadano.index',0)
           }
       },
       mounted(){
         $('#mute').removeClass('on');
       },
        methods: {
            newNivelCudadano(){
                this.loading = true;
                axios.post(route('nivel-ciudadano.store'), this.nivelCiudadano).then( (response) =>{
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