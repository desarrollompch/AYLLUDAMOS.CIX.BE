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
                        <th>
                            Color
                        </th>
                        <th class="action-col">
                            Opciones
                        </th>
                    </tr>
                  </thead>

                  <paginate ref="paginator" name="incidente" :list="estadosIncidentes" :per="10" tag="tbody">
                    <tr v-if="estadosIncidentes.length == 0">
                        <td colspan="3">No existen estados incidentes</td>
                    </tr>
                    <tr v-for="(estadoIncidente, index) in paginated('incidente')">
                        <td>
                            {{index + 1}}
                        </td>
                        <td>
                            {{estadoIncidente.descripcion}}
                        </td>
                        <td>
                            <input type="color" class="form-control" name="color" v-model="estadoIncidente.color" style="width: 50px; height: 40px;" disabled ="true">
                        </td>
                        <td class="botones-horizontal">
                            <a :href="getEditUrl(estadoIncidente.id)" class="icono-editar"></a>
                            <a href="#" class="icono-eliminar" v-on:click="deleteTipoPersona(estadoIncidente)"></a>
                        </td>
                    </tr>
                  </paginate>                      

              <tfoot>
                    <td colspan="3">
                        <paginate-links for="incidente"
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
                estadosIncidentes: [],
                paginate: ['incidente'],
                paginaActual:1,
                isIniciado : false,
            }
        },
        mounted() {
            this.getEstadosIncidentes();
        },
        beforeUpdate(){
          if(!this.isIniciado){
            this.getResultadosEnSession();
            this.isIniciado = true;
          }
        },
        methods: {
          getResultadosEnSession(){
            axios.get(route('estado-incidente.getPageSession')).then((response) => {
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
            deleteTipoPersona(estadoIncidente){
                let r = confirm('Estas seguro de eliminar este registro');

                if(r === true)
                {
                    axios.delete(route('estado-incidente.destroy', {id: estadoIncidente.id})).then((response)=>{
                        if(response.data.success){
                            this.getEstadosIncidentes();
                        }else{
                            alert(response.data.message);
                        }
                    }).catch((error)=>{
                        console.log(error.data);
                    });
                }
            },
            getEstadosIncidentes(){
              $('#mute').addClass('on').addClass('h100');
                axios.get(route('estado-incidente.all')).then((response) => {
                    this.estadosIncidentes = response.data.estadosIncidentes;
                    $('#mute').removeClass('on');
                });
            },
            getEditUrl(id){
                return route('estado-incidente.edit', {id: id,pagina:this.paginaActual});
            }

        }
    }
</script>