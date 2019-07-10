<template>
    <div class="card">
        <div class="card-header">
            Crear Usuario
        </div>
        <form action="" v-on:submit.prevent="newUser()">
            <user-form :user="user"  :errors="errors"></user-form>

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
                user: {
                    state:'Activo',
                    rol_id:''
                },
                errors: [],
                loading: false,
                polygon:{},
                cancel_url:route('user.index')
            }
        },
        mounted(){
          // $('#mute').addClass('on').addClass('h100');
           $('#mute').removeClass('on');
        },
        methods: {
            newUser() {
                this.loading = true;
                axios.post(route('user.store'), this.user).then((response) => {

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