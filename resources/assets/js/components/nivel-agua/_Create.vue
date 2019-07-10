<template>
    <div class="card">
        <div class="card-header">
            Crear Nivel de Agua
        </div>
        <form action="" v-on:submit.prevent="newNivelAgua()">
            <div class="card-body">
                <nivel-agua-form :nivelAgua="nivelAgua" :errors="errors"></nivel-agua-form>
            </div>
            <div class="card-footer text-center">
                <button class="btn disenio-boton-accion" type="submit" :disabled="loading">
                    <i class="fa fa-check fa-lg" aria-hidden="true"></i>Guardar
                </button>
                <a class="btn disenio-boton-accion" :href="cancel_url">
                    <i class="fa fa-times fa-lg" aria-hidden="true"></i>Cancelar
                </a>
            </div>
        </form>
    </div>
</template>

<script>
    export default {
       data(){
           return {
               nivelAgua: {
                   descripcion: ''
               },
               errors: [],
               loading:false,
               cancel_url: route('nivel-agua.index',0)
           }
       },
       mounted(){
         $('#mute').removeClass('on');
       },
        methods: {
            newNivelAgua(){
                this.loading = true;
                axios.post(route('nivel-agua.store'), this.nivelAgua).then( (response) =>{
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