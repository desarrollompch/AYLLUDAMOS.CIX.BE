<template>
    <div>
        <div class="col-sm-12 mb-15">
            <vue-xlsx-table @on-select-file="handleSelectedFile">
                Cargar Excel
            </vue-xlsx-table>
        </div>    
        <div class="limpiar-flotantes"></div>    
        <div class="col-sm-12 table-responsive">
            <table class="table table bordered">
                <thead>
                    <tr>
                        <th style="width: 100px">Codigo</th>
                        <th>
                            Descripción
                        </th>
                        <th class="action-col">
                            Opciones
                        </th>
                    </tr>
                </thead>
                <tbody v-if="territoriosVecinales.length == 0">
                    <tr>
                        <td colspan="3">No existen territorios vecinales registrados</td>
                    </tr>
                </tbody>
                <tbody v-else>
                    <tr v-for="(territorioVecinal, index) in territoriosVecinales" :key="index">
                        <!-- <td>{{ territorioVecinal.id }}</td> -->
                        <td>{{ parseInt(index) + 1 }}</td>
                        <td>{{ territorioVecinal.descripcion }}</td>
                        <td class="botones-horizontal">
                            <a :href="getEditUrl(territorioVecinal.id)" class="icono-editar"></a>
                            <a href="#" class="icono-eliminar" v-on:click="deleteTipoPersona(territorioVecinal)"></a>
                        </td>
                    </tr>
                </tbody>
                <tfoot>
                    <td colspan="3">
                        <ul class="paginate-links territorios pagination">
                            <li v-for="page in pagesNumber" v-bind:class="['number page-item', (page == isActived) ? 'active': '']">
                                <a href="#" @click.prevent="cambiarPagina(page)" class="page-link">{{ page }}</a>
                            </li>
                        </ul>
                    </td>
                </tfoot>
            </table>
        </div>
        <div class="modal fade" id="modalTemp" tabindex="-1" role="dialog" aria-labelledby="modalTemp"
            aria-hidden="true"
        >
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalTempLabel">Territorios Vecinales</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-labelledby="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body table-responsive">
                        <table class="table table-bordered">
                            <tr>
                                <th class="number-col">#</th>
                                <th>Descripción</th>
                            </tr>
                            <tr v-for="(territorio, index) in territoriosVecinalesTemp.body">
                                <td>{{ index + 1 }}</td>
                                <td>{{ territorio.Descripcion }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-primary" :disabled="loading" v-on:click="saveAll()">Guardar</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="limpiar-flotantes"></div>
    </div>
</template>

<script>
    import vueXlsxTable from 'vue-xlsx-table';
    Vue.use(vueXlsxTable);

    export default {
        data() {
            return {
                territoriosVecinales: [],
                territoriosVecinalesTemp: [],
                loading: false,
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
            }
        },
        mounted() {
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
              axios.get(route('territorio-vecinal.getPageSession')).then((response) => {
                  if (response.data.success){
                    this.getTerritoriosVecinales(response.data.paginaActual);
                  } else {
                    alert(response.data.message);
                  }
              }).catch((error) => {
                  console.log(error.data);
              });
            },
            deleteTipoPersona(territorioVecinal) {
                let r = confirm('Estas seguro de eliminar este registro');

                if (r === true) {
                    axios.delete(route('territorio-vecinal.destroy', {id: territorioVecinal.id})).then((response) => {
                        if (response.data.success) {
                            this.getTerritoriosVecinales(this.paginaActual);
                        } else {
                            alert(response.data.message);
                        }
                    }).catch((error) => {
                        console.log(error.data);
                    });
                }
            },
            getTerritoriosVecinales(page) {
              $('#mute').addClass('on').addClass('h100');
                axios.get(route('territorio-vecinal.all', {"page": page})).then((response) => {
                    this.territoriosVecinales = response.data.result.data;
                    this.pagination = response.data.pagination;
                    this.paginaActual = page;
                    $('#mute').removeClass('on');
                });
            },
            getEditUrl(id) {
                return route('territorio-vecinal.edit', {id: id,pagina:this.paginaActual});
            },
            handleSelectedFile(convertedData) {
                this.territoriosVecinalesTemp = convertedData;
                $('#modalTemp').modal('show');
            },
            saveAll(){
                this.loading = true;

                axios.post(route('territorio-vecinal.store-all'), this.territoriosVecinalesTemp).then((response) => {
                    if (response.data.success) {
                        $('#modalTemp').modal('hide');
                        alert(response.data.message);
                        this.getTerritoriosVecinales(1);
                    }

                    this.loading = false;

                }).catch((error) => {
                    if (error.response.status == 422) {
                        this.errors = error.response.data.errors;
                    }

                    this.loading = false;

                })
            },
            cambiarPagina: function(page) {
                this.pagination.current_page = page;
                this.paginaActual = page;
                this.getTerritoriosVecinales(page);
            },
        }
    }
</script>