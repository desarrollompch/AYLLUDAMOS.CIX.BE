<template>
    <div>
        <div class="col-sm-12 table-responsive">
            <table class="table table bordered">
                <thead>
                    <tr>
                        <th class="number-col">#</th>
                        <th>
                            Persona
                        </th>
                        <th>
                            Territorio Vecinal
                        </th>
                        <th class="action-col">
                            Opciones
                        </th>
                    </tr>
                </thead>
                <tbody v-if="alcaldesVecinales.length == 0">
                    <tr>
                        <td colspan="4">No existen alcaldes vecinales registrados</td>  
                    </tr>
                </tbody>
                <tbody v-else>
                    <tr v-for="(alcaldeVecinal, index) in alcaldesVecinales" :key="index">
                        <td>
                            {{ parseInt(index) + 1}}
                        </td>
                        <td>
                            {{alcaldeVecinal.persona.nombres}} {{alcaldeVecinal.persona.ape_paterno}} {{alcaldeVecinal.persona.ape_materno}}
                        </td>
                        <td>
                            {{alcaldeVecinal.territorio_vecinal.descripcion}}
                        </td>
                        <td class="botones-horizontal">
                            <a :href="getEdiUrl(alcaldeVecinal.id)" class="icono-editar"></a>
                            <a href="#" class="icono-eliminar" v-on:click="deleteAlcaldeVecinal(alcaldeVecinal)"></a>
                        </td>
                    </tr>
                </tbody>
                <tfoot>
                    <td colspan="4">
                        <ul class="paginate-links alcaldesVecinales pagination">
                            <li v-for="page in pagesNumber" v-bind:class="['number page-item', (page == isActived) ? 'active': '']">
                                <a href="#" @click.prevent="cambiarPagina(page)" class="page-link">{{ page }}</a>
                            </li>
                        </ul>
                    </td>
                </tfoot>

                    <!-- <paginate name="alcaldes" :list="alcaldesVecinales" :per="5" tag="tbody">
                    <tr v-for="(alcaldeVecinal, index) in paginated('alcaldes')">
                        <td>
                            {{index + 1}}
                        </td>
                        <td>
                            {{alcaldeVecinal.persona.nombres}} {{alcaldeVecinal.persona.ape_paterno}} {{alcaldeVecinal.persona.ape_materno}}
                        </td>
                        <td>
                            {{alcaldeVecinal.territorio_vecinal.descripcion}}
                        </td>
                        <td class="botones-horizontal">
                            <a :href="getEdiUrl(alcaldeVecinal.id)" class="btn disenio-boton-accion">
                                <i class="fa fa-pencil fa-lg" aria-hidden="true"></i>Editar
                            </a>
                            <a href="#" class="btn disenio-boton-accion" v-on:click="deleteAlcaldeVecinal(alcaldeVecinal)">
                                <i class="fa fa-minus fa-lg" aria-hidden="true"></i>Baja
                            </a>
                        </td>
                    </tr>
                    </paginate>                          -->

                  <!-- <tfoot>
                    <td colspan="4">
                         <paginate-links for="alcaldes"
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
    </div>
</template>

<script>
    // import VuePaginate from 'vue-paginate';
    // Vue.use(VuePaginate);

    export default {
        data(){
            return {
                alcaldesVecinales: [],
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
                offset: 5
                // paginate: ['alcaldes'],
            }
        },
        mounted() {
            // this.getAlcaldesVecinales();
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
              axios.get(route('alcalde-vecinal.getPageSession')).then((response) => {
                  if (response.data.success){
                    console.log(response.data.paginaActual);
                    this.getAlcaldesVecinales(response.data.paginaActual);
                    // this.getAlcaldesVecinales();
                  } else {
                    alert(response.data.message);
                  }
              }).catch((error) => {
                  console.log(error.data);
              });
            },
            deleteAlcaldeVecinal(alcaldeVecinal){
                let r = confirm('Estas seguro de dar de baja este registro');

                if(r === true)
                {
                    axios.delete(route('alcalde-vecinal.destroy', {id: alcaldeVecinal.id})).then((response)=>{
                        if(response.data.success){
                            this.getAlcaldesVecinales(this.paginaActual);
                        }else{
                            alert(response.data.message);
                        }
                    }).catch((error)=>{
                        console.log(error.data);
                    });
                }
            },
            getAlcaldesVecinales(page){
              $('#mute').addClass('on').addClass('h100');
                axios.get(route('alcalde-vecinal.all', {"page": page})).then((response) => {
                    // this.alcaldesVecinales = response.data.alcaldesVecinales;
                    this.alcaldesVecinales = response.data.alcaldesVecinales.data;
                    this.pagination = response.data.pagination;
                    $('#mute').removeClass('on');
                });
            },
            getEdiUrl(id){
                return route('alcalde-vecinal.edit', {id: id,pagina:this.paginaActual})
            },
            cambiarPagina: function(page) {
                this.pagination.current_page = page;
                this.paginaActual = page;
                this.getAlcaldesVecinales(page);
            },

        }
    }
</script>