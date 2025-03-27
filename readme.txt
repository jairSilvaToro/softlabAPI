
# Nombre de tu soflabAPI 

## Descripción  
SoftlabAPI. La API permite, en su primera version, realizar operaciones CRUD (Crear, Leer, Actualizar, Eliminar) sobre los recursos de roles, empleados, empresas y representantes. 

## Instalación  
bash
git clone https://github.com/jairSilvaToro/softlabAPI.git
cd softlabAPI
npm install
php -S localhost:8000 -t public

## 🛠 Configuración de la Base de Datos

1. Crea una BD 'aquasoftlab' vacía en tu servidor SQL.
2. Restaura el dump:
   ```bash
   # MySQL
   mysql -u usuario -p mi_api_db < database/aquasoftlab.sql

 