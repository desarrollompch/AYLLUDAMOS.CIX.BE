<template>
    <div class="card">
        <div class="card-header">
            Crear Persona
        </div>
        <form action="" v-on:submit.prevent="newPersona()">
            <persona-form :persona="persona"  :errors="errors"></persona-form>

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
                persona: {
                    state:'Activo',
                    tipo_persona_id:'',
                    nivel_ciudadano_id: '',
                    urbanizacion_id: ''
                },
                errors: [],
                loading: false,
                polygon:{},
                cancel_url:route('persona.index',0)
            }
        },
        mounted(){
          $('#mute').removeClass('on');
        },
        methods: {
            newPersona() {
                this.loading = true;
                axios.post(route('persona.store'), this.persona).then((response) => {
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