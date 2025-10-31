# 🏢 Portal de Empleo para Alumnos y Empresas

**Proyecto Integral – 2º CFGS Desarrollo de Aplicaciones Web (DAW)**  

Este proyecto consiste en el desarrollo de un **portal de empleo** donde **alumnos** y **empresas** pueden interactuar.  
Los estudiantes pueden buscar ofertas acordes a su perfil académico, y las empresas pueden publicar vacantes y gestionar candidatos.  
El sistema cuenta con tres roles principales: **Administrador**, **Empresario** y **Alumno**, cada uno con sus propias funcionalidades.

---

## 📋 Objetivos del Proyecto

- Desarrollar un sistema de gestión de usuarios con tres roles diferenciados.  
- Implementar operaciones **CRUD** (Crear, Leer, Actualizar y Eliminar) para la gestión de alumnos y empresas.  
- Crear un **sistema de ofertas de empleo** y **notificaciones por correo electrónico**.  
- Permitir la **gestión de CVs en formato PDF**.  
- Diseñar una interfaz **intuitiva, accesible y responsive** sin frameworks de estilos.  

---

## ⚙️ Tecnologías Utilizadas

| Tipo | Tecnologías |
|------|--------------|
| **Frontend (cliente)** | HTML5, CSS3, JavaScript *(opcionalmente React o Vue.js)* |
| **Backend (servidor)** | PHP |
| **Base de Datos** | MySQL / MariaDB |
| **Correo Electrónico** | PHPMailer o similar |
| **Diseño de Interfaces** | Figma / Adobe XD |

---

## 👥 Roles y Funcionalidades

### 👨‍💼 Administrador
- Gestiona usuarios (alumnos y empresas).
- Aprueba o rechaza registros de empresas.
- Aprueba, edita o elimina ofertas publicadas.
- Genera informes y estadísticas.
- Envía notificaciones por correo.

### 🏭 Empresario
- Se registra (validación previa del administrador).
- Crea, edita y elimina ofertas de empleo.
- Consulta candidatos postulados y descarga sus CVs en PDF.
- Recibe notificaciones cuando un alumno se postula.

### 🎓 Alumno
- Crea y actualiza su perfil y su CV en PDF.
- Consulta y filtra ofertas de empleo relacionadas con su perfil.
- Se postula a ofertas y gestiona su historial de candidaturas.
- Recibe notificaciones cuando hay ofertas relevantes.

---

## 🧭 Flujo de Trabajo y Pantallas Principales

1. **Landing Page**: muestra empresas registradas y acceso al portal.  
2. **Registro y Login**: permite el alta de alumnos y empresarios.  
3. **Panel de Administración**: gestión de empresas, alumnos y ofertas.  
4. **Panel de Empresario**: publicación y seguimiento de ofertas.  
5. **Panel de Alumno**: exploración y postulación a ofertas.  

---

## ✉️ Notificaciones Automáticas

- **A empresarios:** cuando un alumno se postula a una oferta.  
- **A alumnos:** cuando se publica una oferta relacionada con su perfil.  

---
