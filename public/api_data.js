define({ "api": [
  {
    "type": "get",
    "url": "/listarIncidencias",
    "title": "Listar Incidencias",
    "group": "Incidencias",
    "version": "0.0.0",
    "filename": "./app/Http/Controllers/IncidenteController.php",
    "groupTitle": "Incidencias",
    "name": "GetListarincidencias"
  },
  {
    "type": "get",
    "url": "/listarPersona/:id",
    "title": "Selecciona una persona",
    "group": "Personas",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "id",
            "optional": false,
            "field": "Id",
            "description": "<p>de persona</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "./app/Http/Controllers/PersonaController.php",
    "groupTitle": "Personas",
    "name": "GetListarpersonaId"
  },
  {
    "type": "get",
    "url": "/listarPersonas",
    "title": "Listar personas",
    "group": "Personas",
    "version": "0.0.0",
    "filename": "./app/Http/Controllers/PersonaController.php",
    "groupTitle": "Personas",
    "name": "GetListarpersonas"
  },
  {
    "type": "post",
    "url": "login",
    "title": "",
    "group": "Users",
    "version": "0.0.0",
    "filename": "./app/Http/Controllers/UsuarioController.php",
    "groupTitle": "Users",
    "name": "PostLogin"
  }
] });
