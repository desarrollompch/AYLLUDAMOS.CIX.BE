<template>
    <div>
        <div class="col-sm-12 mb-15">
            <vue-xlsx-table @on-select-file="handleSelectedFile">
                Cargar Excel
            </vue-xlsx-table>
        </div>
        <div class="col-sm-12 table-responsive">
            <table class="table table bordered">
                <thead>
                    <tr>
                        <th class="number-col">#</th>
                        <th>
                            Nombre
                        </th>
                        <th>
                            Territorio Vecinal
                        </th>
                        <th class="action-col">
                            Opciones
                        </th>
                    </tr>
                </thead>
                <tbody v-if="urbanizaciones.length == 0">
                    <tr>
                        <td colspan="3">No existen urbanizaciones registradas</td>  
                    </tr>
                </tbody>
                <tbody v-else>
                    <tr v-for="(urbanizacion, index) in urbanizaciones" :key="index">
                        <td>
                            {{ parseInt(index) + 1 }}
                        </td>
                        <td>
                            {{ urbanizacion.descripcion }}
                        </td>
                        <td>
                            {{ urbanizacion.territorio_vecinal.descripcion }}
                        </td>
                        <td class="botones-horizontal">
                            <a :href="getEditUrl(urbanizacion.id)" class="icono-editar"></a>
                            <a href="#" class="icono-eliminar" v-on:click="deleteTipoPersona(urbanizacion)"></a>
                        </td>
                    </tr>
                </tbody>
                <tfoot>
                    <td colspan="3">
                        <ul class="paginate-links urbanizaciones pagination">
                            <li v-for="page in pagesNumber" v-bind:class="['number page-item', (page == isActived) ? 'active': '']">
                                <a href="#" @click.prevent="cambiarPagina(page)" class="page-link">{{ page }}</a>
                            </li>
                        </ul>
                    </td>
                </tfoot>
                  <!-- <paginate name="urbanizaciones" :list="urbanizaciones" :per="5" tag="tbody">
                    <tr v-for="(urbanizacion, index) in paginated('urbanizaciones')">
                        <td>
                            {{index + 1}}
                        </td>
                        <td>
                            {{urbanizacion.descripcion}}
                        </td>
                        <td>
                            {{urbanizacion.territorio_vecinal.descripcion}}
                        </td>
                        <td class="botones-horizontal">
                            <a :href="getEditUrl(urbanizacion.id)" class="btn disenio-boton-accion">
                                <i class="fa fa-pencil fa-lg" aria-hidden="true"></i>Editar
                            </a>
                            <a href="#" class="btn disenio-boton-accion" v-on:click="deleteTipoPersona(urbanizacion)">
                                <i class="fa fa-times-circle-o fa-lg" aria-hidden="true"></i>Eliminar
                            </a>
                        </td>
                    </tr>
                  </paginate>                           -->

                  <!-- <tfoot>
                    <td colspan="4">
                        <paginate-links for="urbanizaciones"
                          :hide-single-page="true"
                          :classes="{
                            'ul': 'pagination',
                            'li': 'page-item',
                            'a':  'page-link'
                          }"
                          ></paginate-links>
                    </td>
                </tfoot> -->
                
            </table>
        </div>
        <div class="modal fade" id="modalTemp" tabindex="-1" role="dialog" aria-labelledby="modalTemp"
             aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalTempLabel">Territorios Vecinales</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body table-responsive">
                        <table class="table table bordered">
                            <tr>
                                <th class="number-col">#</th>
                                <th>
                                    Descripción
                                </th>
                            </tr>
                            <tr v-for="(urbanizacion, index) in urbanizacionesTemp.body">
                                <td>
                                    {{index + 1}}
                                </td>
                                <td>
                                    {{getTerritorio(urbanizacion.Code)}}
                                </td>
                                <td>
                                    {{urbanizacion.Descripcion}}
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-primary" :disabled="loading"
                                v-on:click="saveAll()">Guardar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import vueXlsxTable from 'vue-xlsx-table';
    Vue.use(vueXlsxTable);
    // import VuePaginate from 'vue-paginate';
    // Vue.use(VuePaginate);

    export default {
        data(){
            return {
                urbanizaciones: [],
                urbanizacionesTemp: [],
                loading: false,
                territoriosVecinales: [],
                pagination: {
                    "total": 0,
                    "current_page": 0,
                    "per_page": 0,
                    "last_page": 0,
                    "from": 0,
                    "to": 0
                },
                paginaActual:1,
                pagina: "",
                offset: 5,
                // paginate: ['urbanizaciones'],
            }
        },
        mounted() {
            axios.get(route('territorio-vecinal.all')).then((response) => {
                this.territoriosVecinales = response.data.result.data;
            });
            // this.getUrbanizaciones();
            this.getResultadosEnSession();
        },
        computed: {
            isActived: function() {
                return this.pagination.current_page;
            },
            pagesNumber: function() {
                if(!this.pagination.to) {
                    return [];
                }

                var from = this.pagination.current_page - this.offset;
                if(from < 1) {
                    from = 1;
                }

                var to = from + (this.offset * 2); 
                if(to >= this.pagination.last_page) {
                    to = this.pagination.last_page;
                }

                var pagesArray = [];
                while(from <= to) {
                    pagesArray.push(from);
                    from++;
                }
                return pagesArray;
            },
        },
        methods: {
            getResultadosEnSession(){
              axios.get(route('urbanizacion.getPageSession')).then((response) => {
                  if (response.data.success){
                    console.log(response.data.paginaActual);
                    this.getUrbanizaciones(response.data.paginaActual);
                  } else {
                    alert(response.data.message);
                  }
              }).catch((error) => {
                  console.log(error.data);
              });
            },
            deleteTipoPersona(urbanizacion){
                let r = confirm('Estas seguro de eliminar este registro');
                if(r === true)
                {
                    axios.delete(route('urbanizacion.destroy', {id:urbanizacion.id})).then((response)=>{
                        if(response.data.success){
                            this.getUrbanizaciones();
                        }else{
                            alert(response.data.message);
                        }
                    }).catch((error)=>{
                        console.log(error.data);
                    });
                }
            },
            getUrbanizaciones(page){
              $('#mute').addClass('on').addClass('h100');
                axios.get(route('urbanizacion.all', {"page": page})).then((response) => {
                    this.urbanizaciones = response.data.urbanizaciones.data;
                    this.pagination = response.data.pagination;
                    $('#mute').removeClass('on');
                });
            },
            getEditUrl(id){
                return route('urbanizacion.edit',{id:id,pagina:this.paginaActual});
            },
            handleSelectedFile(convertedData) {
                this.urbanizacionesTemp = convertedData;
                $('#modalTemp').modal('show');
            },
            saveAll(){
                this.loading = true;

                axios.post(route('urbanizacion.store-all'), this.urbanizacionesTemp).then((response) => {
                    if (response.data.success) {
                        $('#modalTemp').modal('hide');
                        alert(response.data.message);
                        this.getUrbanizaciones();
                    }

                    this.loading = false;

                }).catch((error) => {
                    if (error.response.status == 422) {
                        this.errors = error.response.data.errors;
                    }

                    this.loading = false;

                })
            },
            getTerritorio(code){
                let territorio = '';
                this.territoriosVecinales.map(row =>{
                    if(row.id == code)
                    {
                        territorio = row.descripcion;
                    }
                })

                return territorio;
            },
            cambiarPagina: function(page) {
                this.pagination.current_page = page;
                this.paginaActual = page;
                this.getUrbanizaciones(page);
            },
        }
    }
</script>