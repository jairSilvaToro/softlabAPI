**1\. Introducción**  
Esta documentación proporciona una guía completa para interactuar con la API REST de SoftlabAPI. La API permite realizar operaciones CRUD (Crear, Leer, Actualizar, Eliminar) sobre los recursos de roles, empleados, empresas y representantes.

### **1.1. Propósito**

### La API está diseñada para permitir a los desarrolladores integrar la gestión de de roles, autenticación, empresas, representantes y empleados en sus aplicaciones.

**1.2. Audiencia**  
Esta documentación está dirigida a desarrolladores que deseen integrar la API en sus sistemas.

### **1.3. Requisitos**

* Clave de API (API Key) para autenticación.  
* Conocimientos básicos de HTTP y REST.

## **2\. Autenticación**

GET /login HTTP/1.1  
Host: api.softlab.com  
Authorization: Bearer tu\_api\_key

## **3\. Endpoints**

A continuación se describen los endpoints disponibles en la API.

### **3.1. Obtener todos los roles**

* **Descripción**: Retorna una lista de todos los usuarios registrados.

* **URL**: `/roles`  
* **Método**: `GET`  
* **Parámetros**: Ninguno.


**Respuesta Exitosa**:

* **Código**: `200 OK`  
* **Cuerpo** `(body)`  
  `{`  
      `"data": [`  
          `{`  
              `"cod_rol": 1,`  
              `"nombre_rol": "admin"`  
          `},`  
          `{`  
              `"cod_rol": 3,`  
              `"nombre_rol": "editor"`  
          `}`  
      `],`  
      `"pagination": {`  
          `"current_page": 1,`  
          `"per_page": 10,`  
          `"total": 2,`  
          `"total_pages": 1`  
      `}`  
  `}`


### **3.2. Crear un nuevo rol**

* **Descripción**: Crea un nuevo rol en el sistema.  
* **URL**: /roles  
* **Método**: POST  
* **Payload**:  
  * {  
  *     "rol": "editor"  
  * }  
      
* **Respuesta Exitosa**:  
  * **Código**: 200  
  * **Cuerpo (body)**: 1

    	**Respuesta de Error**:

		**Código:** `400 Bad Request`

		**Cuerpo:**

	{

    "error": "No se permite el campo 'rol' vacio"

}

### **3.3. Eliminar un rol**

* **Descripción**: Eliminar un rol en el sistema.  
* **URL**: /roles/{cod\_rol}  
* **Método**: DELETE  
* **Parámetros**:  
  * cod\_rol (entero, requerido): Código Del rol por eliminar  
      
* **Respuesta Exitosa**:  
  * **Código**: 200  
  * **Cuerpo (body)**: 1

           **Respuesta de Error:**

	**Código:** 400 bad request

**Cuerpo(body):**

{

    "error": "No se pudo eliminar el rol"

}

### **3.4. Obtener todos los empleados**

* **Descripción**: Retorna una lista de todos los empleados registrados.

* **URL**: `/`empleados  
* **Método**: `GET`  
* **Parámetros**: Ninguno.

**Respuesta Exitosa**:

* **Código**: `200 OK`  
* **Cuerpo** `(body)`  
  	{

      "data": \[  
          {  
              "cedula\_empleado": "1234567892",  
              "nombre": "fulano",  
              "apellido": "detal",  
              "telefono": "3112345678",  
              "rol": null  
          },  
          {  
              "cedula\_empleado": "1234567893",  
              "nombre": "eliana",  
              "apellido": "sierra",  
              "telefono": "3112345678",  
              "rol": null  
          }  
      \],  
      "pagination": {  
          "current\_page": 1,  
          "per\_page": 10,  
          "total": 2,  
          "total\_pages": 1  
      }  
  }

  


### **3.5. Obtener empleado por cédula**

	**Descripción**: Retorna un empleado por cédula.

* **URL**: `/`empleados/{**cedula\_empleado**}  
* **Método**: `GET`  
* **Parámetros**: **cédula empleado** (string, requerido): cédula del empleado  
* **Respuesta Exitosa**:   
  	**Código**: `200 OK`  
  **Cuerpo** `(body):`  
  	`[`

      `{`  
          `"cedula_empleado": "1234567892",`  
          `"nombre": "fulano",`  
          `"apellido": "detal",`  
          `"telefono": "3112345678",`  
          `"rol": null`  
      `}`  
  `] o []`

### **3.6. Ingresar nuevo empleado.**

	**Descripción**: Retorna una lista de todos los empleados registrados.

* **URL**: `/`empleados  
* **Método**: `GET`  
* **Parámetros**: Ninguno.  
* **payload**:  
  	{

      "cedula\_empleado": "1234567811",  
      "cod\_rol": null,  
      "nombre": "dahiana",  
      "apellido": "londono",  
      "telefono": "3112345681"  
  }  
  


  

**Respuesta Exitosa**:

	**Código**: `200 OK`

**Cuerpo** `(body): 1(int)`

 	**Respuesta de Error:**

	**Código:** 400 bad request

**Cuerpo(body):**

{

  "error": "el empleado no pudo ser creado"

 }

### **3.7. Editar empleado.**

* **URL**: `/`empleado  
* **Método**: `GET`  
* **Parámetros**: Ninguno.  
* **payload**:  
  	{

      "cedula\_empleado": "1234567891",  
      "cod\_rol": 1,  
      "nombre": "nathaly giselle",  
      "apellido": "alzate",  
      "telefono": "3104551908"  
  }

	**Respuesta Exitosa**: 

	**Código**: `200 OK`

	**Cuerpo** `(body): 1`

**Respuesta Errónea**: 

	**Código**: `200 OK`

	**Cuerpo** `(body):` 

`{`

    `"error": "El empleado no pudo ser actualizado"`

`}`

**3.8. Eliminar empleado.**

* **URL**: `/`empleado  
* **Método**: `DELETE`  
* **Parámetros**: **cédula empleado** (string, requerido): cédula del empleado.

	**Respuesta Exitosa**: 

	**Código**: `200 OK`

	**Cuerpo** `(body): 1`

**Respuesta Errónea**: 

	**Código**: `200 OK`

	**Cuerpo** `(body):` 

`{`

    `"error": "El empleado no pudo ser eliminado"`

`}`

**3.9. Obtener todas las empresas**

	**Descripción**: Retorna a todas las empresas con su representante legal.

* **URL**: `/empresas`  
* **Método**: `GET`  
* **Parámetros**: ninguno.

	**Respuesta Exitosa**: 

	**Código**: `200 OK`

	**Cuerpo** `(body):` 

	`{`

    `"data": [`

        `{`

            `"cedula_empleado": "1234567811",`

            `"nombre": "dahiana",`

            `"apellido": "londono",`

            `"telefono": "3112345681",`

            `"rol": null`

        `},`

        `{`

            `"cedula_empleado": "1234567892",`

            `"nombre": "fulano",`

            `"apellido": "detal",`

            `"telefono": "3112345678",`

            `"rol": "admin"`

        `},`

        `{`

            `"cedula_empleado": "1234567893",`

            `"nombre": "eliana",`

            `"apellido": "sierra",`

            `"telefono": "3112345678",`

            `"rol": null`

        `}`

    `],`

    `"pagination": {`

        `"current_page": 1,`

        `"per_page": 10,`

        `"total": 3,`

        `"total_pages": 1`

    `}`

`}`

### **3.10. Obtener empresa por Código**

	**Descripción**: Retorna una empresa por código.

* **URL**: `/`empresas/{**cod\_empresa**}  
* **Método**: `GET`  
* **Parámetros**: **cod\_empresa**(string, requerido): Código de la empresa  
* **Respuesta Exitosa**:   
  	**Código**: `200 OK`  
  **Cuerpo** `(body):`  
  	`[`

      `{`  
          `"cod_empresa": "emp003",`  
          `"nombre_empresa": "elevatetech",`  
          `"direccion": "carrera 8 # 100",`  
          `"telefono": "3112345680",`  
          `"correo_electronico": "elevatetech@gmail.com",`  
          `"cedula_reprelegal": "1234567801"`  
      `}`  
  `] o []`


### **3.11. Ingresar nueva empresa.**

	**Descripción**: Ingresa una nueva empresa al sistema.

* **URL**: `/`empresas  
* **Método**: `POST`  
* **Parámetros**: Ninguno.  
* **payload**:  
  	{

      "cod\_empresa": "emp003",  
      "nombre\_empresa": "elevatetech",  
      "direccion": "carrera 8 \# 100",  
      "telefono": "3112345680",  
      "correo\_electronico": "elevatetech@gmail.com",  
      "cedula\_reprelegal": "1234567801"  
  }


**Respuesta Exitosa**:

	**Código**: `200 OK`

**Cuerpo** `(body): 1(int)`

 	**Respuesta de Error:**

	**Código:** 400 bad request

**Cuerpo(body):**

{

  "error": "La empresa no pudo ser creada"

 }

**3.12. Editar Empresa.**  
	**Descripción**: Actualiza una empresa en el sistema.

* **URL**: `/`empresas  
* **Método**: `PUT`  
* **Parámetros**: Ninguno.  
    
    
* **payload**:  
  	{

      "cod\_empresa": "emp002",  
      "nombre\_empresa": "buscalibre",  
      "direccion": "carrera 8 \# 100",  
      "telefono": "3112345680",  
      "correo\_electronico": "buscalibre@gmail.com",  
      "cedula\_reprelegal": "1234567800"  
  }


	**Respuesta Exitosa**: 

	**Código**: `200 OK`

	**Cuerpo** `(body): 1`

**Respuesta Errónea**: 

	**Código**: `400 Bad request`

	**Cuerpo** `(body):` 

`{`

    `"error": "La empresa no pudo ser actualizada"`

`}`

**3.13. Obtener todos los representantes**

	**Descripción**: Retorna todos los representantes legales.

* **URL**: `/`representantes  
* **Método**: `GET`  
* **Parámetros**: ninguno.

	**Respuesta Exitosa**: 

		**Código**: `200 OK`

	**Cuerpo** `(body):` 

		`{`

    `"data": [`

        `{`

            `"cedula_reprelegal": "1234567800",`

            `"nombre": "john smith",`

            `"telefono": "3000000001"`

        `},`

        `{`

            `"cedula_reprelegal": "1234567801",`

            `"nombre": "Anders Doe",`

            `"telefono": "3000000002"`

        `}`

    `],`

    `"pagination": {`

        `"current_page": 1,`

        `"per_page": 10,`

        `"total": 2,`

        `"total_pages": 1`

    `}`

`}`

### **3.14. Obtener representante por cédula**

	**Descripción**: Muestra un representante junto con las empresas a las que representa.

* **URL**: `/`empresas/{**cedula\_reprelegal**}  
* **Método**: `GET`  
* **Parámetros** : cedula\_reprelegal (string, requerido): cédula del representante legal de la empresa legal  
* **Respuesta Exitosa**:   
  	**Código**: `200 OK`  
  **Cuerpo** `(body):`  
  	`{`

      `"cedula_reprelegal": "1234567800",`  
      `"nombre": "john smith",`  
      `"telefono": "3000000001",`  
      `"empresas": [`  
          `{`  
              `"nombre_empresa": "panquesito",`  
              `"cod_empresa": "emp001"`  
          `},`  
          `{`  
              `"nombre_empresa": "buscalibre",`  
              `"cod_empresa": "emp002"`  
          `}`  
      `]`  
  `} o []`


### **3.15. Ingresar nuevo representante legal.**

	**Descripción**: Ingresa un nuevo representante legal en el sistema.

* **URL**: `/`representantes  
* **Método**: `POST`  
* **Parámetros**: Ninguno.  
* **payload**:  
  	{

      "cedula\_reprelegal": "1234567801",  
      "nombre": "john Doe",  
      "telefono": "3000000002"  
  }

	**Respuesta Exitosa**:  
	**Código**: `200 OK`

**Cuerpo** `(body): 1(int)`

**Respuesta Errónea**: 

	**Código**: `400 Bad request`

	**Cuerpo** `(body):` 

`{`

    `"error": "el representante no pudo ser creado"`

`}`

**3.12. Editar Representante legal.**  
	**Descripción**: Actualiza un representante legal en el sistema.

* **URL**: `/`representantes  
* **Método**: `PUT`  
* **Parámetros**: Ninguno.  
    
    
* **payload**:  
  	{

      "cedula\_reprelegal": "1234567900",  
      "nombre": "juan de tal",  
      "telefono": "3000000100"  
  }


	**Respuesta Exitosa**: 

	**Código**: `200 OK`

	**Cuerpo** `(body): 1`

**Respuesta Errónea**: 

	**Código**: `400 Bad request`

	**Cuerpo** `(body):` 

`{`

    `"error": "La empresa no pudo ser actualizada"`

`}`

##  **4\. Ejemplos de Solicitudes**

	**4.1 Obtener todos los roles**

	GET /roles  HTTP/1.1  
Host: api.ejemplo.com  
Authorization: Bearer api\_ke**y**

### **4.2. Crear un nuevo rol**

 	POST /roles HTTP/1.1  
Host: api.ejemplo.com  
Authorization: Bearer tu\_api\_key  
Content-Type: application/json

### **4.3. Eliminar un rol**

DELETE /roles/1  HTTP/1.1  
Host: api.ejemplo.com  
Authorization: Bearer tu\_api\_key

### **4.4. Obtener todos los empleados**

	GET /empleados HTTP/1.1  
Host: api.ejemplo.com  
Authorization: Bearer tu\_api\_key

### **4.5. Obtener empleado por cédula**

	GET /empleados/1234567892  HTTP/1.1  
Host: api.ejemplo.com  
Authorization: Bearer tu\_api\_key

### **4.6. Ingresar nuevo empleado.**

	POST /empleados  HTTP/1.1  
Host: api.ejemplo.com  
Authorization: Bearer tu\_api\_key  
Content-Type: application/json

{  
    "cedula\_empleado": "1234567811",  
    "cod\_rol": null,  
    "nombre": "dahiana",  
    "apellido": "londono",  
    "telefono": "3112345681"  
}

	

