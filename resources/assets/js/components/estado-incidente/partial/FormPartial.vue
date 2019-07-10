<template>
    <div class="row">
        <div class="col-sm-9">
            <div class="form-group">
                <label>Descripcion</label>
                <input type="text" class="form-control" placeholder="Ingrese una descripción" name="descripcion" v-model="estadoIncidente.descripcion">
                <small id="passwordHelpBlock" class="form-text text-danger" v-if="errors.descripcion" v-for="error in errors.descripcion" >
                    {{error}}
                </small>
            </div>
        </div>
        <div class="col-sm-1">
            <div class="form-group">
                <label>Color</label>
                <input type="color" class="form-control" name="color" v-model="estadoIncidente.color" style="width: 50px; height: 40px;">
            </div>
        </div>
    </div>

</template>

<script>
    export default {
        props: ['estadoIncidente', 'errors']
    }
</script>