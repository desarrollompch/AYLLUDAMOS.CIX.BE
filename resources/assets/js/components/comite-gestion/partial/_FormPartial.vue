<template>
    <div class="row">
        <div class="col-sm-5">
            <div class="form-group">
                <label for="territorio_vecinal_id">Territorio Vecinal</label>
                <select style="margin-top:17px" name="territorio_vecinal_id" id="territorio_vecinal_id" class="form-control"
                        v-model="comiteGestion.territorio_vecinal_id">
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
                <search-persona :persona="comiteGestion.persona" @onSelect="setPersonaId"></search-persona>
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
        props: ['comiteGestion', 'errors'],
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
                this.comiteGestion.persona_id = data;
            }
        }
    }
</script>