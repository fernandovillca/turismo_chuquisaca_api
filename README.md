# TURISMO CHUQUISACA API

## DESCRIPCION

    Este repositorio contiene el desarrollo de una API RESTful que actúa como el backend de una aplicación de turismo, diseñada para la gestión integral de la información turística del departamento de Chuquisaca, en Bolivia.

## ESTRUCTURA

    |-CONTROLLERS: Manejar la comunicación HTTP, puerta de entrada de las peticiones HTTP
        - Recibir la petición HTTP (Request)
        - Validar datos usando FormRequests
        - Llamar al Service apropiado
        - Transformar la respuesta del Service a JSON usando Resources
        - Retornar la respuesta HTTP (Response)
        - Manejar códigos de estado HTTP (200, 201, 404, etc.)

    |-SERVICES: Responsable de la Lógica de negocio y orquestación. Es el cerebro que toma decisiones.
        - Implementar reglas de negocio
        - Validaciones complejas (más allá de las básicas)
        - Coordinar operaciones entre múltiples Repositories
        - Transformar y preparar datos
        - Manejar transacciones complejas
        - Lanzar excepciones de negocio
        - Realizar cálculos y procesamiento de datos

    |-REPOSITORIES: Acceso a datos, es la única capa que habla con la base de datos. Abstrae las consultas.
        - Consultas a la base de datos (Eloquent, Query Builder)
        - CRUD básico (Create, Read, Update, Delete)
        - Búsquedas y filtros
        - Paginación
        - Relaciones entre modelos
        - Consultas SQL complejas
        - Cacheo de datos
