<template>
    <div class="card">
        <div class="card-header">
            Editar Nivel Ciudadano
        </div>
        <form action="" v-on:submit.prevent="editNivelCudadano()">
            <div class="card-body">
                <nivel-ciudadano-form :nivelCiudadano="nivelCiudadano" :errors="errors"></nivel-ciudadano-form>
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
                nivelCiudadano: {
                },
                errors: [],
                loading:false,
                cancel_url: route('nivel-ciudadano.index',1)
            }
        },
        mounted(){
            let id = this.getIDfromURL();

            axios.get(route('nivel-ciudadano.show', {id:id})).then((response) => {
                this.nivelCiudadano = response.data.nivelCiudadano;
                $('#mute').removeClass('on');
            });

        },
        methods: {
            editNivelCudadano(){
                this.loading = true;
                axios.put(route('nivel-ciudadano.update', {id:this.nivelCiudadano.id}), this.nivelCiudadano).then( (response) =>{
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