<template>
    <div class="card">
        <div class="card-header">
            Editar Directorio
        </div>
        <form action="" v-on:submit.prevent="editNivelAgua()">
            <div class="card-body">
                <configuracion-form v-if="configuracion.id" :configuracion="configuracion" :errors="errors"></configuracion-form>
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
                configuracion: {
                    descripcion: ''
                },
                errors: [],
                loading:false,
                cancel_url: route('configuracion.index',1)
            }
        },
        mounted(){
            let id = this.getIDfromURL();

            axios.get(route('configuracion.show', {id:id})).then((response) => {
                this.configuracion = response.data.configuracion;
                $('#mute').removeClass('on');
            });

        },
        methods: {
            editNivelAgua(){
                this.loading = true;
                axios.put(route('configuracion.update', {id:this.configuracion.id}), this.configuracion).then( (response) =>{
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