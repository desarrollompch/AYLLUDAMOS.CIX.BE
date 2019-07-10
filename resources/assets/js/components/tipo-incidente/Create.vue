<template>
    <div class="card">
        <div class="card-header">
            Crear Tipo Incidente
        </div>
        <form action="" v-on:submit.prevent="newTipoIncidente()">
            <div class="card-body">
                <tipo-incidente-form :tipoIncidente="tipoIncidente" :errors="errors"></tipo-incidente-form>
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
               tipoIncidente: {
                   descripcion: ''
               },
               errors: [],
               loading:false,
               cancel_url:route('tipo-incidente.index',0)
           }
       },
       mounted(){
         $('#mute').removeClass('on');
       },
        methods: {
            newTipoIncidente(){
                this.loading = true;
                axios.post(route('tipo-incidente.store'), this.tipoIncidente).then( (response) =>{
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