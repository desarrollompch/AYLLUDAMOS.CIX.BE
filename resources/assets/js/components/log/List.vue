<template>
    <div class="row">
        <div class="col-sm-12 table-responsive">
            <div class="row">
                <div class="col-sm-3">
                    <label>
                        Fecha
                    </label>
                    <datepicker v-model="fecha" :input-class="'form-control'" id="fecha"
                                :language="languages['es']" :format="'dd/MM/yyyy'"
                                placeholder="Seleccionar Fecha"
                    ></datepicker>
                </div>
                <div class="col-sm-3">
                    <label>
                        Correo
                    </label>
                    <search-user @onSelectItem="setUser" style="margin-top: -17px;"></search-user>
                </div>
                <div class="col-sm-3" style="margin-top:10px;">
                    <br>
                    <a href="#" class="btn disenio-boton-accion" v-on:click="searchFilter()">
                        Consultar
                    </a>
                </div>
                <div class="col-sm-3" style="margin-top:10px; margin-left:-160px">
                    <br>
                    <a :href="getExportUrl()" class="btn disenio-boton-accion">
                        Exportar a Excel
                    </a>
                </div>
            </div>
            <br>
            <table class="table table bordered">
              <thead>
                <tr>
                    <th class="number-col">#</th>
                    <th>
                        Fecha
                    </th>
                    <th>
                        Acción
                    </th>
                    <th>
                        Usuario
                    </th>
                </tr>
              </thead>
              <paginate name="logs" :list="logs" :per="10" tag="tbody">
                <tr v-if="logs.length == 0">
                    <td colspan="4">No existen registros en el log de usuarios</td>
                </tr>
                <tr v-for="(log, index) in paginated('logs')">
                    <td>
                        {{index + 1}}
                    </td>
                    <td>
                        {{dateFormat(log.created_at)}}
                    </td>
                    <td v-html="log.description">

                    </td>
                    <td>
                        {{log.causer.email}}
                    </td>
                </tr>
              </paginate>                      

                <tfoot>
                    <td colspan="4">
                        <paginate-links for="logs"
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
            <br>

        </div>
    </div>
</template>

<script>
    import Datepicker from 'vuejs-datepicker';
    import * as lang from "vuejs-datepicker/src/locale";
    import * as moment from 'moment';
    import VuePaginate from 'vue-paginate';
    Vue.use(VuePaginate);

    export default {
        components: {
            Datepicker
        },
        data() {
            return {
                logs: [],
                paginate: ['logs'],
                languages: lang,
                fecha: '',
                fechaconsulta:'',
                user_id:''
            }
        },
        mounted(){
          $('#mute').removeClass('on');
        },
        computed: {
            isActived: function () {
                return this.pagination.current_page;
            },
            pagesNumber: function () {
                if (!this.pagination.to) {
                    return [];
                }

                var from = this.pagination.current_page - this.offset;
                if (from < 1) {
                    from = 1;
                }

                var to = from + (this.offset * 2);
                if (to >= this.pagination.last_page) {
                    to = this.pagination.last_page;
                }

                var pagesArray = [];
                while (from <= to) {
                    pagesArray.push(from);
                    from++;
                }

                return pagesArray;
            }
        },
        mounted() {
          this.getLogs()
        },
        methods: {
            getLogs(){
                axios.get(route('log.all',{date:this.fechaconsulta, user_id:this.user_id}))
                  .then((response) => {
                        this.logs = response.data.activities.data;
                });
            },
            dateFormat(date) {
                return moment(date).format('DD/MM/YYYY , h:mm:ss a');
            },

            searchFilter() {
                this.fechaconsulta = moment(this.fecha).format('DD/MM/YYYY');
                if(this.fechaconsulta == 'Invalid date')
                {
                    this.fecha = '';
                    this.fechaconsulta = '';
                }
                this.getLogs();
            },
            setUser(data){
                if(data.id)
                {
                    this.user_id = data.id;
                }else{
                    this.user_id = '';
                }
            },
            getExportUrl() {
                return route('log.export', {date:this.fechaconsulta, 'user-id': this.user_id});
            }
        }
    }
</script>