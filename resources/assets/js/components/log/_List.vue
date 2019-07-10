<template>
    <div class="row">
        <div class="col-sm-12 table-responsive">
            <div class="row">
                <div class="col-sm-3">
                    <label>
                        Fecha
                    </label>
                    <datepicker v-model="fecha" :input-class="'form-control'" id="fecha_search"
                                :language="languages['es']" :format="'dd/MM/yyyy'" @input="searchFilter()"
                                :typeable="true"
                    ></datepicker>
                </div>
                <div class="col-sm-4">
                    <label>
                        Correo
                    </label>
                    <search-user @onSelectItem="setUser" style="margin-top: -17px;"></search-user>
                </div>
                <div class="cols-sm-3">
                    <br>
                    <a :href="getExportUrl()" class="btn btn-info">
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
              <paginate name="logs" :list="logs" :per="5" tag="tbody">
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
              axios.get(route('log.all'))
              .then((response) => {
                  this.logs = response.data.activities.data;
              });
            },
            dateFormat(date) {
                return moment(date).format('DD/MM/YYYY , h:mm:ss a');
            },

            searchFilter() {
                this.fecha = moment(this.fecha).format('YYYY-MM-DD');

                if (this.fecha == 'Invalid date') {
                    this.fecha = '';
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
                this.searchFilter();
            },
            getExportUrl() {
                return route('log.export', {date:this.fecha, 'user-id': this.user_id});
            }
        }
    }
</script>