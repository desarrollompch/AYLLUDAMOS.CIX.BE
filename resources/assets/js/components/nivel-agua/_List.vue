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
                        <th class="action-col">
                            
                        </th>
                    </tr>
                    </thead>

                    <paginate ref="paginator" name="niveles" :list="nivelesAgua" :per="5" tag="tbody">
                    <tr v-for="(nivelAgua, index) in paginated('niveles')">
                        <td>
                            {{index + 1}}
                        </td>
                        <td>
                            {{nivelAgua.descripcion}}
                        </td>
                        <td class="botones-horizontal">
                            <a :href="getEdiUrl(nivelAgua.id)" class="btn disenio-boton-accion">
                                <i class="fa fa-pencil fa-lg" aria-hidden="true"></i>Editar
                            </a>
                            <a href="#" class="btn disenio-boton-accion" v-on:click="deleteNivelAgua(nivelAgua)">
                                <i class="fa fa-times-circle-o fa-lg" aria-hidden="true"></i>Eliminar
                            </a>
                        </td>
                    </tr>
                  </paginate>                        

                <tfoot>
                    <td colspan="3">
                        <paginate-links for="niveles"
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
                nivelesAgua: [],
                paginate: ['niveles'],
                paginaActual:1,
                isIniciado : false,
            }
        },
        mounted() {
            this.getNivelesAgua();
        },
        beforeUpdate(){
          if(!this.isIniciado){
            this.getResultadosEnSession();
            this.isIniciado = true;
          }
        },
        methods: {
          getResultadosEnSession(){
            axios.get(route('nivel-agua.getPageSession')).then((response) => {
              // if(this.nacionalidades.length > 0){
                this.$refs.paginator.goToPage(response.data.paginaActual);
              // }
            }).catch((error) => {
              console.log(error.data);
            });
          },
          onLangsPageChange (toPage, fromPage) {
            this.paginaActual = toPage;
          },
            deleteNivelAgua(nivelAgua){
                let r = confirm('Estas seguro de eliminar este registro');

                if(r === true)
                {
                    axios.delete(route('nivel-agua.destroy', {id: nivelAgua.id})).then((response)=>{
                        if(response.data.success){
                            this.getNivelesAgua();
                        }else{
                            alert(response.data.message);
                        }
                    }).catch((error)=>{
                        console.log(error.data);
                    });
                }
            },
            getNivelesAgua(){
              $('#mute').addClass('on').addClass('h100');
                axios.get(route('nivel-agua.all')).then((response) => {
                    this.nivelesAgua = response.data.nivelesAgua;
                    $('#mute').removeClass('on');
                });
            },
            getEdiUrl(id){
                return route('nivel-agua.edit', {id: id,pagina:this.paginaActual})
            }

        }
    }
</script>