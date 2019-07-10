<template>
    <div class="card">
        <div class="card-header">
            Crear Alcalde Vecinal
        </div>
        <form action="" v-on:submit.prevent="newNivelAgua()">
            <div class="card-body">
                <actividad-puntuacion-form :actividadPuntuacion="actividadPuntuacion" :errors="errors"></actividad-puntuacion-form>
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
               actividadPuntuacion: {
                   estado_incidente_id: ''
               },
               errors: [],
               loading:false,
               cancel_url: route('actividad-puntuacion.index',0)
           }
       },
       mounted(){
         $('#mute').removeClass('on');
       },
        methods: {
            newNivelAgua(){
                this.loading = true;
                axios.post(route('actividad-puntuacion.store'), this.actividadPuntuacion).then( (response) =>{
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