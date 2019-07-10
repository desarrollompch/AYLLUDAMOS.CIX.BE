<template>
    <div class="card">
        <div class="card-header">
            Crear Territorio Vecinal
        </div>
        <form action="" v-on:submit.prevent="newTerritorioVecinal()">
            <territorio-vecinal-form :territorioVecinal="territorioVecinal"  :errors="errors"></territorio-vecinal-form>

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
                territorioVecinal: {
                    descripcion: ''
                },
                errors: [],
                loading: false,
                polygon:{},
                cancel_url:route('territorio-vecinal.index',0)
            }
        },
        mounted(){
          console.log(this.cancel_url);
          $('#mute').removeClass('on');
        },
        methods: {
            newTerritorioVecinal() {
                this.loading = true;
                axios.post(route('territorio-vecinal.store'), this.territorioVecinal).then((response) => {
                  console.log(response.data);
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