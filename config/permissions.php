<?php
/**
 * Created by PhpStorm.
 * User: icalvay
 * Date: 23/06/18
 * Time: 23:46
 */

return [
    [
        'id' => uniqid(),
        'name' => 'Registro de Usuarios',
        'description' => 'Crear, editar y eliminar usuarios',
        'models' => [
            [
                'name' => "User",
                'actions' => ["view", "create", "update", "delete"]
            ]
        ]
    ],
    [
        'id' => uniqid(),
        'name' => 'Registro de Tipo de Persona',
        'description' => 'Crear, editar y eliminar Tipo de ciudadano.',
        'models' => [
            [
                'name' => "TipoPersona",
                'actions' => ["view", "create", "update", "delete"]
            ]
        ]
    ],
    [
        'id' => uniqid(),
        'name' => 'Registro de Territorios Vecinales',
        'description' => 'Crear, editar y eliminar Territorio Vecinales.',
        'models' => [
            [
                'name' => "TerritorioVecinal",
                'actions' => ["view", "create", "update", "delete"]
            ]
        ]
    ],
    [
        'id' => uniqid(),
        'name' => 'Registro de Personas',
        'description' => 'Crear, editar y eliminar Personas.',
        'models' => [
            [
                'name' => "Persona",
                'actions' => ["view", "create", "update", "delete"]
            ]
        ]
    ],
    [
        'id' => uniqid(),
        'name' => 'Registro de Actividad Puntuación',
        'description' => 'Crear, editar y eliminar Actividad Puntuación.',
        'models' => [
            [
                'name' => "ActividadPuntuacion",
                'actions' => ["view", "create", "update", "delete"]
            ]
        ]
    ],
    [
        'id' => uniqid(),
        'name' => 'Registro de Alcalde Vecinal',
        'description' => 'Crear, editar y eliminar Alcalde Vecinal.',
        'models' => [
            [
                'name' => "AlcaldeVecinal",
                'actions' => ["view", "create", "update", "delete"]
            ]
        ]
    ],
    [
        'id' => uniqid(),
        'name' => 'Registro de Incidente',
        'description' => 'Crear, detalle de Incidente',
        'models' => [
            [
                'name' => "Incidente",
                'actions' => ["view", "create","update"]
            ]
        ]
    ],
    [
        'id' => uniqid(),
        'name' => 'Registro de Comite de Gestión',
        'description' => 'Crear, editar y eliminar Comite de Gestión.',
        'models' => [
            [
                'name' => "ComiteGestion",
                'actions' => ["view", "create", "update", "delete"]
            ]
        ]
    ],
    [
        'id' => uniqid(),
        'name' => 'Registro de Coordinación',
        'description' => 'Crear, editar y eliminar Coordinación.',
        'models' => [
            [
                'name' => "Coordinacion",
                'actions' => ["view", "create", "update", "delete"]
            ]
        ]
    ],
    [
        'id' => uniqid(),
        'name' => 'Registro de Directorio',
        'description' => 'Crear, editar y eliminar Directorio.',
        'models' => [
            [
                'name' => "Directorio",
                'actions' => ["view", "create", "update", "delete"]
            ]
        ]
    ],
    [
        'id' => uniqid(),
        'name' => 'Registro de Estado del Incidente',
        'description' => 'Crear, editar y eliminar Estado del Incidente.',
        'models' => [
            [
                'name' => "EdtadoIncidente",
                'actions' => ["view", "create", "update", "delete"]
            ]
        ]
    ],
    [
        'id' => uniqid(),
        'name' => 'Registro de Nivel de Agua',
        'description' => 'Crear, editar y eliminar Nivel de Agua.',
        'models' => [
            [
                'name' => "NivelAgua",
                'actions' => ["view", "create", "update", "delete"]
            ]
        ]
    ],
    [
        'id' => uniqid(),
        'name' => 'Registro de Nivel Ciudadano',
        'description' => 'Crear, editar y eliminar Nivel Ciudadano.',
        'models' => [
            [
                'name' => "NivelCiudadano",
                'actions' => ["view", "create", "update", "delete"]
            ]
        ]
    ],
    [
        'id' => uniqid(),
        'name' => 'Registro de Puntuación Persona',
        'description' => 'Crear, editar y eliminar Puntuación Persona.',
        'models' => [
            [
                'name' => "PuntuacionPersona",
                'actions' => ["view", "create", "update", "delete"]
            ]
        ]
    ],
    [
        'id' => uniqid(),
        'name' => 'Registro de Roles',
        'description' => 'Crear, editar y eliminar Roles.',
        'models' => [
            [
                'name' => "Rol",
                'actions' => ["view", "create", "update", "delete"]
            ]
        ]
    ],
    [
        'id' => uniqid(),
        'name' => 'Registro de Puntuación Persona',
        'description' => 'Crear, editar y eliminar Puntuación Persona.',
        'models' => [
            [
                'name' => "PuntuacionPersona",
                'actions' => ["view", "create", "update", "delete"]
            ]
        ]
    ],
    [
        'id' => uniqid(),
        'name' => 'Registro de Tipo de Incidente',
        'description' => 'Crear, editar y eliminar Tipo de Incidente.',
        'models' => [
            [
                'name' => "TipoIncidente",
                'actions' => ["view", "create", "update", "delete"]
            ]
        ]
    ],
    [
        'id' => uniqid(),
        'name' => 'Confirmar Ciudadanos',
        'description' => 'Confirmar Ciudadanos.',
        'models' => [
            [
                'name' => "Persona",
                'actions' => ["active"]
            ]
        ]
    ],
    [
        'id' => uniqid(),
        'name' => 'Atender Incidente',
        'description' => 'Registrar coordinaciones y cambiar de estado a incidentes.',
        'models' => [
            [
                'name' => "Incidente",
                'actions' => ["attention", "attentiondetalle"]
            ]
        ]
    ],
    [
        'id' => uniqid(),
        'name' => 'Nacionalidad',
        'description' => 'Registrar nacionalidades.',
        'models' => [
            [
                'name' => "Nacionalidad",
                'actions' => ["view", "create", "update", "delete"]
            ]
        ]
    ],
    [
        'id' => uniqid(),
        'name' => 'Log',
        'description' => 'Ver log de usuarios.',
        'models' => [
            [
                'name' => "Activity",
                'actions' => ["view"]
            ]
        ]
    ],
    [
        'id' => uniqid(),
        'name' => 'Botón de Pánico',
        'description' => 'Ver botón de pánico.',
        'models' => [
            [
                'name' => 'BotonPanico',
                'actions' => ['view']
            ]
        ]
    ],
    [
        'id' => uniqid(),
        'name' => 'Ubicación',
        'description' => 'Ver mapa de ubicaciones.',
        'models' => [
            [
                'name' => 'Ubicacion',
                'actions' => ['view']
            ]
        ]
    ]
];