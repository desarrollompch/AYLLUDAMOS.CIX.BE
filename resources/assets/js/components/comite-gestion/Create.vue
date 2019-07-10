<template>
    <div class="card">
        <div class="card-header">
            Crear Comite de Gestión
        </div>
        <form action="" v-on:submit.prevent="newNivelAgua()">
            <div class="card-body">
                <comite-gestion-form :comiteGestion="comiteGestion" :errors="errors"></comite-gestion-form>
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
               comiteGestion: {
                   territorio_vecinal_id: ''
               },
               errors: [],
               loading:false,
               cancel_url: route('comite-gestion.index',0)
           }
       },
       mounted(){
         $('#mute').removeClass('on');
       },
        methods: {
            newNivelAgua(){
                this.loading = true;
                axios.post(route('comite-gestion.store'), this.comiteGestion).then( (response) =>{
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