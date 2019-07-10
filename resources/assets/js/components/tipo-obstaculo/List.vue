<template>
    <div>
        <div class="col-sm-12 table-responsive">
            <table class="table table bordered">
                <thead>
                    <tr>
                        <th class="number-col">#</th>
                        <th>
                            Descripción
                        </th>
                        <th class="action-col">
                            Opciones
                        </th>
                    </tr>
                  </thead>

                  <paginate ref="paginator" name="obstaculo" :list="tiposObstaculos" :per="10" tag="tbody">
                    <tr v-if="tiposObstaculos.length == 0">
                        <td>No existen tipos de obstáculos</td>
                    </tr>
                    <tr v-for="(tipoObstaculo, index) in paginated('obstaculo')">
                        <td>
                            {{index + 1}}
                        </td>
                        <td>
                            {{tipoObstaculo.descripcion}}
                        </td>
                        <td class="botones-horizontal">
                            <a :href="getEdiUrl(tipoObstaculo.id)" class="icono-editar"></a>
                            <a href="#" class="icono-eliminar" v-on:click="deleteTipoObstaculo(tipoObstaculo)"></a>
                        </td>
                    </tr>
                  </paginate>                         

                  <tfoot>
                    <td colspan="3">
                        <paginate-links for="obstaculo"
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
                tiposObstaculos: [],
                paginate: ['obstaculo'],
                paginaActual:1,
                isIniciado : false,
            }
        },
        mounted() {
            this.getTiposObstaculos();
        },
        beforeUpdate(){
          if(!this.isIniciado){
            this.getResultadosEnSession();
            this.isIniciado = true;
          }
        },
        methods: {
          getResultadosEnSession(){
            axios.get(route('tipo-obstaculo.getPageSession')).then((response) => {
              if(this.$refs.paginator){
                this.$refs.paginator.goToPage(response.data.paginaActual);
              }
            }).catch((error) => {
              console.log(error.data);
            });
          },
          onLangsPageChange (toPage, fromPage) {
            this.paginaActual = toPage;
          },
            deleteTipoObstaculo(tipoObstaculo){
                let r = confirm('Estas seguro de eliminar este registro');

                if(r === true)
                {
                    axios.delete(route('tipo-obstaculo.destroy', {id: tipoObstaculo.id})).then((response)=>{
                        if(response.data.success){
                            this.getTiposObstaculos();
                        }else{
                            alert(response.data.message);
                        }
                    }).catch((error)=>{
                        console.log(error.data);
                    });
                }
            },
            getTiposObstaculos(){
              $('#mute').addClass('on').addClass('h100');
                axios.get(route('tipo-obstaculo.all')).then((response) => {
                    this.tiposObstaculos = response.data.tiposObstaculos;
                    $('#mute').removeClass('on');
                });
            },
            getEdiUrl(id){
                return route('tipo-obstaculo.edit', {id: id,pagina:this.paginaActual});
            }

        }
    }
</script>