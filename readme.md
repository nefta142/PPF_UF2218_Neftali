# Explicacion sencilla
Esta es una Web donde podremos:
- **Dar de alta** nuevos coches.
- **Modificar** los datos de un coche si hubo un error o si ha cambiado alguna información.
- **Eliminar** coches del registro.
- **Buscar** nos permite filtar solo por marca y modelo.
- Además, incluye un **buscador general** que permite **filtrar fácilmente** el coche que estamos buscando.

---

# Explicación técnica

Dentro de la carpeta principal del proyecto encontrarás **7 archivos PHP** y dos **subcarpetas llamadas `files` `control`**.

La carpeta `files` contiene:

- `coches.xml`: el archivo donde se almacenan todos los registros de los coches.
- `coches.xsd`: define la estructura y validaciones que debe cumplir el XML (por ejemplo, el formato de la matrícula, el rango de puertas, etc.).
- `coches.xsl`: incluye un diseño básico pensado para transformar el XML, aunque actualmente no está en uso.

---

La carpeta `control` contiene:

- `coches.xml`: el archivo donde se almacenan todos los registros de los usuarios.
- `coches.xsd`: define la estructura y validaciones que debe cumplir el XML.

---

Fuera de las subcarpetas, se encuentran los archivos `.php`, que manejan la lógica de inserción, modificación, eliminación, visualización y buscador de los datos. Cada archivo está comentado para facilitar su comprensión.

---

# Método de utilización 

### Requisitos:

- Tener instalado:
  - **XAMPP** (Windows)
  - **LAMP** (Linux)
- Asegúrate de que la carpeta `files/` tenga permisos de escritura.

---

### 🧰 Configuración paso a paso

#### 1. Copiar el proyecto

- En **XAMPP** (Windows):
  Copia la carpeta del proyecto a:  
  `C:\xampp\htdocs\gestion_coches`

- En **LAMP** (Linux):
  Copia la carpeta a:  
  `/var/www/html/gestion_coches`

#### 2. Dar permisos a la carpeta `files/` (Linux)

Abre terminal en la carpeta del proyecto y ejecuta:

```bash
sudo chmod -R 755 files/
sudo chown -R www-data:www-data files/
