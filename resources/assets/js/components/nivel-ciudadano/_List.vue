<template>
    <div class="row">
        <div class="col-sm-12 table-responsive">
            <table class="table table bordered">
                <thead>
                    <tr>
                        <th class="number-col">#</th>
                        <th>
                            Descripción
                        </th>
                        <th>
                            Puntos
                        </th>
                        <th class="action-col">
                            
                        </th>
                    </tr>
                  </thead>

                    <paginate ref="paginator" name="nivelesCiudadanos" :list="nivelesCiudadanos" :per="5" tag="tbody">
                    <tr v-for="(nivelCiudadano, index) in paginated('nivelesCiudadanos')">
                        <td>
                            {{index + 1}}
                        </td>
                        <td>
                            {{nivelCiudadano.descripcion}}
                        </td>
                        <td>
                            {{nivelCiudadano.total_minimo}} - {{nivelCiudadano.total_maximo}}
                        </td>
                        <td>
                            <a :href="getEditUrl(nivelCiudadano.id)" class="btn btn-primary">
                                Editar
                            </a>
                            <a href="#" class="btn btn-danger" v-on:click="deleteNivelCudadano(nivelCiudadano)">
                                Eliminar
                            </a>
                        </td>
                    </tr>
                    </paginate>      

                <tfoot>
                    <td colspan="4">
                        <paginate-links for="nivelesCiudadanos"
                          @change="onLangsPageChange"
                          :hide-single-page="true"
                          :classes="{
                            'ul': 'pagination',
                            'li': 'page-item',
                            'a':  'page-link'
                          }"
                        ></paginate-links>
                    </td>
                </tfoot>

                
            </table>
        </div>
    </div>
</template>

<script>
    import VuePaginate from 'vue-paginate';
    Vue.use(VuePaginate);
    export default {
        data(){
            return {
                nivelesCiudadanos: [],
                paginate: ['nivelesCiudadanos'],
                paginaActual:1,
                isIniciado : false,
            }
        },
        mounted() {
            this.getNivelesCiudadanos();
        },
        updated(){
          if(!this.isIniciado){
            console.log('ENTRO');
            this.getResultadosEnSession();
            this.isIniciado = true;
          }
        },
        methods: {
          getResultadosEnSession(){
            console.log('ENTRO ENRTO ENTRO');
            console.log(this.nivelesCiudadanos.length);
            axios.get(route('nivel-ciudadano.getPageSession')).then((response) => {
              if(this.nivelesCiudadanos.length > 0){
                this.$refs.paginator.goToPage(response.data.paginaActual);
                this.paginaActual = response.data.paginaActual;
              }
            }).catch((error) => {
              console.log(error.data);
            });
          },
          onLangsPageChange (toPage, fromPage) {
            this.paginaActual = toPage;
          },
            deleteNivelCudadano(nivelCiudadano){
                let r = confirm('Estas seguro de eliminar este registro');

                if(r === true)
                {
                    axios.delete(route('nivel-ciudadano.destroy', {id:nivelCiudadano.id})).then((response)=>{
                        if(response.data.success){
                            this.getNivelesCiudadanos();
                        }else{
                            alert(response.data.message);
                        }
                    }).catch((error)=>{
                        console.log(error.data);
                    });
                }
            },
            getNivelesCiudadanos(){
              $('#mute').addClass('on').addClass('h100');
                // this.nivelesCiudadanos = null;
                axios.get(route('ciudadano.all')).then((response) => {
                    this.nivelesCiudadanos = response.data.nivelesCiudadanos;
                    $('#mute').removeClass('on');
                });
            },
            getEditUrl(id){
                return route('nivel-ciudadano.edit', {id:id,pagina:this.paginaActual});
            }

        }
    }
</script>