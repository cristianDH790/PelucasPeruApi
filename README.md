<h1 align="center">⚙️ API Backend — Pelucas Perú (CodeIgniter 4)</h1>

<img width="1898" height="957" alt="imagen" src="https://github.com/user-attachments/assets/64347313-fc2f-421b-945e-cbc37659ee86" />


<p align="center">
  Este proyecto es una <strong>API RESTful</strong> desarrollada con <strong>CodeIgniter 4</strong>, diseñada para proporcionar la infraestructura backend del sistema <strong>Pelucas Perú</strong>.  
  Se encarga de gestionar datos de usuarios, productos, pedidos, configuraciones y más, garantizando una comunicación segura y eficiente con el frontend desarrollado en Angular.
</p>

---

## ⚡ Requisitos Previos

> Antes de ejecutar este proyecto, asegúrate de tener las siguientes herramientas instaladas 🧰

| 🧩 Herramienta       | 💻 Versión Recomendada | 🌐 Enlace Oficial |
|----------------------|------------------------|------------------|
| 🐘 **PHP**           | 8.2+                   | [php.net](https://www.php.net/) |
| 🧱 **Composer**      | 2.x                    | [getcomposer.org](https://getcomposer.org/) |
| 🗃️ **MySQL / MariaDB** | 10.4+               | [mariadb.org](https://mariadb.org/) |
| 🚀 **CodeIgniter 4** | Última versión estable | [codeigniter.com](https://codeigniter.com/) |

---

### 🧪 Verificar Instalación

Comprueba si tienes instaladas las dependencias básicas:

```bash
php -v
composer -V

# Clonar el repositorio
git clone https://github.com/cristianDH790/PelucasPeruApi.git

# Entrar al proyecto
cd PelucasPeruApi

# Instalar dependencias
composer install

# Copiar el archivo de entorno
cp env .env
```
## ⚙️ Configuración del Entorno

Edita el archivo `.env` para configurar tu base de datos:

```env
CI_ENVIRONMENT = development

database.default.hostname = localhost
database.default.database = pelucasperu_db
database.default.username = root
database.default.password = 
database.default.DBDriver = MySQLi

```
## 🔗 Proyecto Relacionado (Frontend)

Este backend funciona junto con el panel administrador desarrollado en Angular:  
👉 [PelucasPeruAngular](https://github.com/cristianDH790/PelucasPeruAngular)

## 📸 Vista en Producción

✨ Puedes ver el sistema en línea aquí:  
👉 [https://pelucasperu.com/](https://pelucasperu.com/)

---

<div align="center">

🧑‍💻 Desarrollado con ❤️ por **Cristian DH**  
📜 Proyecto backend oficial para Pelucas Perú

</div>


