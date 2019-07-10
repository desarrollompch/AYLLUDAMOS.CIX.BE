<template>
    <div class="card">
        <div class="card-header">
            Editar Rol
        </div>
        <form action="" v-on:submit.prevent="editTipoPersona()">
            <div class="card-body">
                <rol-form v-if="rol.id" :rol="rol" :errors="errors"></rol-form>
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
                rol: {
                },
                errors: [],
                loading:false,
                cancel_url:route('rol.index',1)
            }
        },
        mounted(){
            let id = this.getIDfromURL();

            axios.get(route('rol.show',{id:id})).then((response) => {
                this.rol = response.data.rol;
                $('#mute').removeClass('on');
            });
        },
        methods: {
            editTipoPersona(){
                this.loading = true;
                axios.put(route('rol.update', {id:this.rol.id}), this.rol).then( (response) =>{
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
            },
            getIDfromURL(){
                let path = window.location.pathname;
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

                return id;
            }
        }
    }
</script>