<template>
    <div class="card">
        <div class="card-header">
            Registrar nacionalidad
        </div>
        <form action="" v-on:submit.prevent="newRol()">
            <nacionalidad-form :nacionalidad="nacionalidad"  :errors="errors"></nacionalidad-form>

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
        data() {
            return {
                nacionalidad: {
                    descripcion: '',
                    estado:'Activo',
                    permisos:[]
                },
                errors: [],
                loading: false,
                polygon:{},
                cancel_url:route('nacionalidad.index',0)
            }
        },
        mounted(){
          $('#mute').removeClass('on');
        },
        methods: {
            newRol() {
                this.loading = true;
                axios.post(route('nacionalidad.store'), this.nacionalidad).then((response) => {
                    if (response.data.success) {
                       window.location.href = this.cancel_url;
                    }

                    this.loading = false;

                }).catch((error) => {
                    if (error.response.status == 422) {
                        this.errors = error.response.data.errors;
                    }

                    this.loading = false;

                })
            }
        }
    }
</script>