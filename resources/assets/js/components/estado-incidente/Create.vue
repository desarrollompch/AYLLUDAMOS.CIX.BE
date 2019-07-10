<template>
    <div class="card">
        <div class="card-header">
            Crear Estado Incidente
        </div>
        <form action="" v-on:submit.prevent="newEstadoIncidente()">
            <div class="card-body">
                <estado-incidente-form :estadoIncidente="estadoIncidente" :errors="errors"></estado-incidente-form>
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
               estadoIncidente: {
                   descripcion: ''
               },
               errors: [],
               loading:false,
               cancel_url: route('estado-incidente.index',0)
           }
       },
       mounted(){
         $('#mute').removeClass('on');
       },
        methods: {
            newEstadoIncidente(){
                this.loading = true;
                axios.post(route('estado-incidente.store'), this.estadoIncidente).then( (response) =>{
                    if(response.data.success)
                    {
                        window.location.href = route('estado-incidente.index',0);
                    }
                    this.loading = false;
                }).catch((error) =>{                  
                    console.log(error);
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