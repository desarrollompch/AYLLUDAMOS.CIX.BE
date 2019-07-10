<table>
    <tr>
        <th style="font-weight: bold;text-align: center" colspan="2">
            {{$titulo}}
        </th>
    </tr>
    <tr>
        <th colspan="2">
            <a href="{{route('incidente.detalle', ['incidente'=>$incidente->id,'pagina'=>1])}}">
                Ver
            </a>
        </th>
    </tr>
</table>