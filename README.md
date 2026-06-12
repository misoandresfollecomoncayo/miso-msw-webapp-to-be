# miso-msw-webapp-php

## Información Académica

Universidad de los Andes<br/>
Facultad de Ingeniería de Sistemas y Computación<br/>
Maestría en Ingeniería de Software<br/>
Curso: Modernización de Software<br/>

Estudiantes:
* Andrés Folleco Moncayo (oa.folleco41@uniandes.edu.co)
* Edwin Cruz Silva (e.cruzs@uniandes.edu.co)
* Omar Pava Pérez (o.pava@uniandes.edu.co)
* Pablo Rivera Herrera (p.riverah@uniandes.edu.co)

## Descripción del Repositorio

Este repositorio contiene el código fuente de la aplicación en tecnología legada que se pretende modernizar para este curso de Maestría.

## Motivación

La plataforma de Software a modernizar, constituye el “core” del negocio: concentra 10 años de conocimiento operativo (agente de compras, envíos, clientes, facturación, inventario y reportes) y es crítica para la operación en seis países. Esto la convierte en Software legado, porque es un activo de valor heredado que no se puede perder. La motivación nace de barreras de mantenimiento que ya aparecen o aparecerán pronto:

* Obsolescencia tecnológica: PHP de hace una década (ya en fin de soporte) implica riesgos de seguridad, falta de parches y dependencias desactualizadas. Es técnicamente cada vez más difícil sostenerlo.
* Deuda técnica acumulada: 10 años de cambios suelen fusionar el código con el negocio y volverlo un monolito difícil de modificar, con código posiblemente muerto y baja modularidad.
* Imposibilidad técnica de atender nuevos requerimientos: El caso más fuerte en este dominio es la facturación electrónica obligatoria por país (DIAN en Colombia, SRI en Ecuador, CFDI en México, etc.), que cambia con frecuencia por regulación. A esto se suman integraciones con transportadoras/carriers, rastreo en tiempo real, canales móviles y crecimiento de las ventas web.
* Escalabilidad y disponibilidad: La operación multi-país con sede principal en Miami exige desempeño y alta disponibilidad que un monolito PHP sobre infraestructura tradicional difícilmente garantiza.
* Escasez de talento y dependencia: Cada vez es más difícil conseguir desarrolladores dispuestos a mantener una base PHP antigua, lo que amenaza la sostenibilidad del sistema.

En síntesis: el mantenimiento se vuelve costoso y limitante, pero el valor de negocio embebido es demasiado alto para descartarlo, por eso conviene modernizar, no reemplazar.

Tecnología de origen: Tecnología de origen: PHP (con MySQL como motor de base de datos; stack LAMP).

## Características de la Tecnología Legada

* Lenguaje interpretado y de tipado débil: PHP no se compila anticipadamente a binario nativo; en cada ejecución su código fuente se transforma en un código intermedio (opcodes) que ejecuta una máquina virtual. Su tipado dinámico y débil permite escribir rápido, pero facilita errores difíciles de detectar.
* Modelo de ejecución "shared-nothing": Cada petición HTTP arranca el script desde cero, lo ejecuta y muere. No hay estado en la memoria entre peticiones. Esto lo hace simple y escalable horizontalmente, pero ineficiente frente a frameworks modernos que mantienen procesos vivos.
* Acoplamiento con HTML: El código PHP se incrusta directamente dentro del HTML (<?php ... ?>). En proyectos antiguos esto produjo el famoso "código espagueti": lógica de negocio, consultas SQL y presentación mezcladas en el mismo archivo, muy difícil de mantener.
* Evolución desigual: PHP arrastra una biblioteca de funciones inconsistente (nombres y orden de parámetros heredados de décadas). Versiones antiguas (5.x e inferiores) carecen de tipado, manejo moderno de errores y rendimiento; PHP 7 y 8 mejoraron enormemente, pero mucho código legado sigue en versiones obsoletas y sin soporte de seguridad.
* Deuda de seguridad: El código antiguo suele tener vulnerabilidades clásicas: inyección SQL, XSS y uso de extensiones deprecadas como mysql_* (eliminada en PHP 7).

No queremos decir que PHP sea obsoleto, sino que el problema radica en que los sistemas construidos con prácticas antiguas: versiones sin soporte, arquitectura sin separación de capas, ausencia de pruebas automatizadas, dependencias muertas y vulnerabilidades acumuladas.

## Arquitectura de la Tecnología Legada

<img width="2413" height="2359" alt="Diagrama PHP" src="https://github.com/user-attachments/assets/e13639a9-bae6-4137-9ab8-c34cb43aa14f" />

* Cliente - Navegador (presentación): No ejecuta lógica de negocio, solo renderiza el HTML, CSS y JavaScript que recibe y captura la interacción del usuario para devolverla como peticiones HTTP. Su única relación con el resto del sistema es ese protocolo.
* Apache - Capa SAPI: Apache es el servidor web que recibe la petición; al detectar un recurso .php lo delega al intérprete a través de la SAPI (Server API). La SAPI es la frontera que abstrae el anfitrión del lenguaje, de modo que el mismo núcleo de PHP funcione bajo mod_php (incrustado en Apache), PHP-FPM (FastCGI), CGI o CLI. Es el punto exacto donde la lógica se conecta con la presentación.
* PHP Core: El núcleo de control del lenguaje. Provee los streams de entrada/salida (archivos y red), la librería estándar de funciones y las comprobaciones de seguridad (open_basedir, etc.). Comunica la SAPI por arriba con el Motor Zend por abajo.
* Motor Zend: El corazón del intérprete; ejecuta un canal de compilación, no una interpretación ingenua línea a línea. Sus subcomponentes:
  * Lexer / Parser: el lexer trocea el código fuente en tokens; el parser los organiza en un árbol sintáctico (AST).
  * Compilador: transforma el AST en opcodes, el código intermedio que la máquina virtual entiende.
  * OPcache: almacén lateral que guarda los opcodes ya compilados para que, en las peticiones siguientes, el motor omita lexer-parser-compilador y los entregue directamente a la máquina virtual (incorporado de fábrica desde PHP 5.5). Su ausencia o mala configuración es causa típica de bajo rendimiento en aplicaciones antiguas.
  * Zend VM: ejecuta los opcodes, gestiona memoria, ámbito de variables (zval) y despacho de funciones, y construye el HTML final que se devolverá.
* Extensiones de acceso a datos: Bibliotecas enganchadas al Motor Zend que traducen las llamadas PHP a sentencias SQL y abren conexiones a la base de datos. Las vigentes son mysqli y PDO_MySQL; la antigua mysql_* —muy presente en código legado y vulnerable a inyección SQL— fue eliminada en PHP 7. Es la unión que conecta la lógica con los datos.
* Motor de BD SQL: El DBMS -MySQL en el stack legado típico- que almacena la información estructurada en tablas relacionadas por claves. Internamente se organiza en su propia capa de conexión (autenticación e hilos), capa SQL (parser y optimizador), motor de almacenamiento intercambiable (InnoDB, transaccional y ACID, o MyISAM, sin transacciones) y el sistema de ficheros donde residen tablas, índices y logs.

### Fuentes de información

* https://www.phpinternalsbook.com/php7/extensions_design/php_lifecycle.html
* https://aws.amazon.com/what-is/lamp-stack
* https://learnomate.org/mysql-architecture-layers-guide/

## Aplicación Legada

### Descripción

La aplicación da soporte al modelo de negocio de "casillero internacional" y compras asistidas (Personal Shopper). Muchos consumidores y pequeños comercios de América Latina desean adquirir productos en tiendas de Estados Unidos, pero se enfrentan a tres barreras: numerosos comercios no realizan envíos internacionales; cuando los hacen, el costo y los tiempos son altos y poco predecibles; y la importación exige gestionar consolidación, documentación aduanera y facturación conforme a la regulación de cada país. Además, una parte de esos clientes no dispone de medios de pago estadounidenses para comprar por sí mismos. La plataforma resuelve esa fricción end-to-end: convierte una dirección en Miami y un casillero virtual en el punto de entrada a Estados Unidos para clientes de seis países, y centraliza toda la cadena operativa —compra, recepción, inventario, envío, facturación y reportería— en un único sistema.

A cada cliente se le asigna un casillero virtual (un código) vinculado a la dirección de bodega en Miami; las compras llegan a nombre de ese código y quedan trazadas a su propietario. Sobre esa base, los módulos encadenan el flujo de negocio: el agente de compras (Personal Shopper) registra las adquisiciones hechas a nombre del cliente y las asocia a su casillero; la recepción e inventario incorpora la mercancía recibida en bodega, siempre ligada al casillero dueño; la gestión de envíos consolida los artículos, registra pagos electrónicos o manuales, ofrece trazabilidad del estado y permite la anulación; la gestión de facturas emite los comprobantes en español o inglés según la regulación del país de destino; y la capa de reportes da visibilidad operativa (compras, clientes por país, inventario, movimiento diario, pagos y recepción de mercancía). Todo ello bajo autenticación y autorización por roles, con tableros que muestran a cada rol los indicadores relevantes para su función. En conjunto, el sistema traduce una operación logística internacional compleja en un proceso administrable desde una sola plataforma web.

### Funcionalidades

* Autenticación y autorización de usuarios: Cada usuario tiene su usuario y contraseña para ingresar a la plataforma, con permisos granulares y un rol asignado.
* Dashboard con KPI’s por rol de usuario: Al iniciar sesión, cada usuario visualiza un tablero con los indicadores relevantes para su función a partir de los módulos operativos.
* Agente de compras: Gestiona el servicio de "Personal Shopper" de la empresa. Permite registrar y administrar (CRUD) las compras realizadas a nombre del cliente y asignarlas a su casillero correspondiente.
* Gestión de envíos: Permite consultar y editar envíos, registrar pagos electrónicos o manuales, hacer seguimiento de su trazabilidad y anularlos cuando corresponda.
* Gestión de clientes: Administra el ciclo completo (CRUD) de los clientes; a cada uno se le asocia un casillero (código) virtual que lo identifica en la operación.
* Gestión de facturas: Genera las facturas de los clientes en español o inglés, conforme a la regulación tributaria de cada país de destino.
* Gestión de inventario: Controla el inventario en bodega mediante operaciones CRUD; cada artículo queda vinculado al cliente/casillero propietario.
* Reportes: Ofrece informes de agente de compras, clientes por país, inventario, movimiento diario, pagos de ventas y recepción de mercancía, cada uno con sus propios parámetros y filtros.

### Líneas de código fuente

La aplicación está compuesta por 21.997 líneas de código PHP, superando holgadamente el mínimo de 2.000 líneas establecido por el curso como requisito de tamaño.

