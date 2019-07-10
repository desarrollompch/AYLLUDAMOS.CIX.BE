<template>
    <div class="card">
        <div class="card-header">
            Editar Persona
        </div>
        <form action="" v-on:submit.prevent="editTipoPersona()">
            <div class="card-body">
                <persona-form v-if="persona.id" :persona="persona" :errors="errors"></persona-form>
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
                persona: {
                },
                errors: [],
                loading:false,
                cancel_url:route('persona.index',1)
            }
        },
        mounted(){
            let id = this.getIDfromURL();

            axios.get(route('persona.show', {id:id})).then((response) => {
                this.persona = response.data.persona;
                $('#mute').removeClass('on');
            });

        },
        methods: {
            editTipoPersona(){
                this.loading = true;
                axios.put(route('persona.update', {id:this.persona.id}), this.persona).then( (response) =>{
                    if(response.data)
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