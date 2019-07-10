<template>
    <div class="card">
        <div class="card-header">
            Editar Usuario
        </div>
        <form action="" v-on:submit.prevent="editTipoUser()">
            <div class="card-body">
                <user-form v-if="user.id" :user="user" :errors="errors"></user-form>
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
                user: {
                },
                errors: [],
                loading:false,
                cancel_url:route('user.index',1)
            }
        },
        mounted(){
            let id = this.getIDfromURL();

            axios.get(route('user.show',{id:id})).then((response) => {
                this.user = response.data.user;
            });
          $('#mute').removeClass('on');
        },
        methods: {
            editTipoUser(){
                this.loading = true;
                axios.put(route('user.update', {id:this.user.id}), this.user).then( (response) =>{
                  console.log(response)
                    if(response.data['success'])
                    {
                        window.location.href = this.cancel_url;
                    }
                    else
                    {
                        alert(response.data['message']);
                    }

                    this.loading = false;

                }).catch((error) =>{
                    if(error.response.status == 422)
                    {
                        this.errors = error.response.data.errors;
                    }

                    this.loading = false;

                })
            },
            getIDfromURL(){
                let path = window.location.pathname;
                console.log('path',path);
                let segments = path.split("/");
                let id = null;

                for(let segment of segments)
                {
                    if(!isNaN(segment))
                    {
                        if(!id)
                        {
                            id = segment;
                        }
                    }
                }
                console.log(id);
                return id;
            }
        }
    }
</script>