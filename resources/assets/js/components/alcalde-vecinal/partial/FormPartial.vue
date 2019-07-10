<template>
    <div class="row col-md-12">
        <div class="col-sm-5">
            <div class="form-group">
                <label for="territorio_vecinal_id">Territorio Vecinal</label>
                <select name="territorio_vecinal_id" id="territorio_vecinal_id" style="margin-top:17px" class="form-control"
                        v-model="alcaldeVecinal.territorio_vecinal_id">
                    <option value="">Selecione..</option>
                    <option v-for="territorio in territoriosVecinales" :value="territorio.id">
                        {{territorio.descripcion}}
                    </option>
                </select>
                <small class="form-text text-danger" v-if="errors.territorio_vecinal_id"
                       v-for="error in errors.territorio_vecinal_id">
                    {{error}}
                </small>
            </div>
        </div>
        <div class="col-sm-7">
            <div class="form-group">
                <label>
                    Persona
                </label>
                <search-persona :persona="alcaldeVecinal.persona" @onSelect="setPersonaId"></search-persona>
                <small class="form-text text-danger" v-if="errors.persona_id"
                       v-for="error in errors.persona_id">
                    {{error}}
                </small>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['alcaldeVecinal', 'errors'],
        data() {
            return {
                territoriosVecinales: []
            }
        },
        mounted() {
            axios.get(route('territorio-vecinal.all-sin-paginado')).then((response) => {
                this.territoriosVecinales = response.data.territoriosVecinales;
            });
        },
        methods: {
            setPersonaId(data) {
                this.alcaldeVecinal.persona_id = data;
            }
        }
    }
</script>