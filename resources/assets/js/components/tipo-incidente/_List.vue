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

                    <paginate ref="paginator" name="tipoIncidente" :list="tiposIncidentes" :per="5" tag="tbody">
                    <tr v-for="(tipoIncidente, index) in paginated('tipoIncidente')">
                        <td>
                            {{index + 1}}
                        </td>
                        <td>
                            {{tipoIncidente.descripcion}}
                        </td>
                        <td class="botones-horizontal">
                            <a :href="getEditUrl(tipoIncidente.id)" class="btn disenio-boton-accion">
                                <i class="fa fa-pencil fa-lg" aria-hidden="true"></i>Editar
                            </a>
                            <a href="#" class="btn disenio-boton-accion" v-on:click="deleteTipoIncidente(tipoIncidente)">
                                <i class="fa fa-times-circle-o fa-lg" aria-hidden="true"></i>Eliminar
                            </a>
                        </td>
                    </tr>
                </paginate>

                <tfoot>
                    <td colspan="4">
                        <paginate-links for="tipoIncidente"
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
                tiposIncidentes: [],
                paginate: ['tipoIncidente'],
                paginaActual:1,
                isIniciado : false,
            }
        },
        mounted() {
            this.getTiposIncidentes();
        },
        beforeUpdate(){
          if(!this.isIniciado){
            this.getResultadosEnSession();
            this.isIniciado = true;
          }
        },
        methods: {
          getResultadosEnSession(){
            axios.get(route('tipo-incidente.getPageSession')).then((response) => {
              console.log(response.data);
              console.log(this.tiposIncidentes.length);
              // if(this.tiposIncidentes.length > 0){
                this.$refs.paginator.goToPage(response.data.paginaActual);
              // }
            }).catch((error) => {
              console.log(error.data);
            });
          },
          onLangsPageChange (toPage, fromPage) {
            this.paginaActual = toPage;
          },
            deleteTipoIncidente(tipoIncidente){
                let r = confirm('Estas seguro de eliminar este registro');

                if(r === true)
                {
                    axios.delete(route('tipo-incidente.destroy',{id:tipoIncidente.id})).then((response)=>{
                        if(response.data.success){
                            this.getTiposIncidentes();
                        }else{
                            alert(response.data.message);
                        }
                    }).catch((error)=>{
                        console.log(error.data);
                    });
                }
            },
            getTiposIncidentes(){
              $('#mute').addClass('on').addClass('h100');
                axios.get(route('tipo-incidente.all')).then((response) => {
                    this.tiposIncidentes = response.data.tiposIncidentes;
                    $('#mute').removeClass('on');
                });
            },
            getEditUrl(id){
                return route('tipo-incidente.edit', {id:id,pagina:this.paginaActual});
            }

        }
    }
</script>