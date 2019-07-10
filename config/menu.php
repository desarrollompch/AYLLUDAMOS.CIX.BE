<?php
/**
 * Created by PhpStorm.
 * User: icalvay
 * Date: 23/06/18
 * Time: 23:46
 */

return [
    [
        'id' => md5('1'.microtime()),
        'module' => 'Usuarios',
        'routes' => [
            [
                'name' => 'Usuarios',
                'model' => 'User',
                'action' => 'view',
                'route' => 'user.index'
            ],
            [
                'name' => 'Roles',
                'model' => 'Rol',
                'action' => 'view',
                'route' => 'rol.index'
            ]
        ]
    ],
    [
        'id' => md5('2'.microtime()),
        'module' => 'Grupos Vecinales',
        'routes' => [
            [
                'name' => 'Territorios Vecinales',
                'model' => 'TerritorioVecinal',
                'action' => 'view',
                'route' => 'territorio-vecinal.index'
            ],
            [
                'name' => 'Urbanizaciones',
                'model' => 'Urbanizacion',
                'action' => 'view',
                'route' => 'urbanizacion.index'
            ],
            [
                'name' => 'Alcaldes Vecinales',
                'model' => 'AlcaldeVecinal',
                'action' => 'view',
                'route' => 'alcalde-vecinal.index'
            ],
            [
                'name' => 'Comites de Gestión',
                'model' => 'ComiteGestion',
                'action' => 'view',
                'route' => 'comite-gestion.index'
            ]
        ]
    ],
    [
        'id' => md5('3'.microtime()),
        'module' => 'Persona',
        'routes' => [
            [
                'name' => 'Tipo de Personas',
                'model' => 'TipoPersona',
                'action' => 'view',
                'route' => 'tipo-persona.index'
            ],
            [
                'name' => 'Personas',
                'model' => 'Persona',
                'action' => 'view',
                'route' => 'persona.index'
            ],[
                'name' => 'Aprobar Ciudadano',
                'model' => 'Persona',
                'action' => 'active',
                'route' => 'persona.inactive'
            ],
            [
                'name' => 'Nacionalidades',
                'model' => 'Nacionalidad',
                'action' => 'view',
                'route' => 'nacionalidad.index'
            ]
        ]
    ],
    [
        'id' => md5('4'.microtime()),
        'module' => 'Sitema de Puntuación',
        'routes' => [
            [
                'name' => 'Niveles',
                'model' => 'NivelCiudadano',
                'action' => 'view',
                'route' => 'nivel-ciudadano.index'
            ],
            [
                'name' => 'Puntos por Actividad',
                'model' => 'ActividadPuntuacion',
                'action' => 'view',
                'route' => 'actividad-puntuacion.index'
            ]
        ]
    ],
    [
        'id' => md5('5'.microtime()),
        'module' => 'Incidencias',
        'routes' => [
            [
                'name' => 'Tipo de Incidente',
                'model' => 'TipoIncidente',
                'action' => 'view',
                'route' => 'tipo-incidente.index'
            ],
            [
                'name' => 'Estado de Incidente',
                'model' => 'EstadoIncidente',
                'action' => 'view',
                'route' => 'estado-incidente.index'
            ],
            [
                'name' => 'Niveles de Agua',
                'model' => 'NivelAgua',
                'action' => 'view',
                'route' => 'nivel-agua.index'
            ],
            [
                'name' => 'Tipo de Obstaculo',
                'model' => 'TipoObstaculo',
                'action' => 'view',
                'route' => 'tipo-obstaculo.index'
            ], [
                'name' => 'Coordinación',
                'model' => 'Coordinacion',
                'action' => 'view',
                'route' => 'coordinacion.index'
            ],
            [
                'name' => 'Incidentes',
                'model' => 'Incidente',
                'action' => 'view',
                'route' => 'incidente.index'
            ],
            [
                'name' => 'Atención Incidentes',
                'model' => 'Incidente',
                'action' => 'attention',
                'route' => 'incidente.attention'
            ]

        ]
    ],
    [
      'id' => md5('6'.microtime()),
      'module' => 'Botón de pánico',
      'model' => 'BotonPanico',
      'action' => 'view',
      'route' => 'botonpanico.index'
    ],
    [
        'id' => md5('7'.microtime()),
        'module' => 'Directorio Telefonico',
        'model' => 'Directorio',
        'action' => 'view',
        'route' => 'directorio.index'
    ],
    [
        'id' => md5('8'.microtime()),
        'module' => 'Configuración',
        'model' => 'Configuracion',
        'action' => 'view',
        'route' => 'configuracion.index'
    ],
    [
        'id' => md5('9'.microtime()),
        'module' => 'Log Usuarios',
        'model' => 'Activity',
        'action' => 'view',
        'route' => 'log.index'
    ],
    [
        'id' => md5('10'.microtime()),
        'module' => 'Ubicación',
        'model' => 'Ubicacion',
        'action' => 'view',
        'route' => 'ubicacion.listar-ubicaciones'
    ]
];