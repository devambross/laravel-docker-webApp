# Laravel Docker WebApp - Sistema de Gestión de Eventos de Club

Sistema completo para la gestión de eventos, registro de asistencia y control de acceso para clubes sociales.

---

## Tabla de Contenidos / Table of Contents

- [Guía de Instalación en Español](#-guía-de-instalación-español)
  - [Windows](#windows-español)
  - [macOS](#macos-español)
  - [Linux](#linux-español)
- [Installation Guide in English](#-installation-guide-english)
  - [Windows](#windows-english)
  - [macOS](#macos-english)
  - [Linux](#linux-english)

---

# Guía de Instalación (Español)

## Windows (Español)

### Opción 1: Con Virtualización Habilitada (Recomendado)

**Requisitos:**
- Windows 10/11 Pro, Enterprise o Education (64-bit)
- Virtualización habilitada en BIOS (Intel VT-x o AMD-V)
- WSL 2 instalado

**Pasos:**

1. **Verificar si tienes virtualización habilitada:**
   - Abre el Administrador de Tareas (Ctrl + Shift + Esc)
   - Ve a la pestaña "Rendimiento"
   - Selecciona "CPU" y verifica que "Virtualización" diga "Habilitada"
   - Si no está habilitada, reinicia y accede a la BIOS (generalmente F2, F10, o DEL al iniciar)

2. **Instalar WSL 2:**
   ```powershell
   # Ejecutar PowerShell como Administrador
   wsl --install
   ```
   - Reinicia tu computadora cuando se solicite
   - Configura tu usuario y contraseña de Ubuntu cuando se abra

3. **Instalar Docker Desktop:**
   - Descarga Docker Desktop desde: https://www.docker.com/products/docker-desktop
   - Ejecuta el instalador
   - Durante la instalación, asegúrate de marcar "Use WSL 2 instead of Hyper-V"
   - Reinicia tu computadora
   - Abre Docker Desktop y espera a que inicie completamente

4. **Clonar el proyecto:**
   ```powershell
   # En PowerShell o CMD
   git clone https://github.com/devambross/laravel-docker-webApp.git
   cd laravel-docker-webApp
   ```

5. **Iniciar el proyecto:**
   ```powershell
   docker compose up -d --build
   ```

6. **Acceder a la aplicación:**
   - Abre tu navegador y ve a: http://localhost:8080

7. **Detener el proyecto:**
   ```powershell
   docker compose down
   ```

### Opción 2: Sin Virtualización (Windows Home o sin VT-x)

**Requisitos:**
- Windows 10/11 Home o versiones sin virtualización
- PHP 8.1 o superior
- Composer
- Node.js 16+
- MySQL o MariaDB

**Pasos:**

1. **Instalar PHP:**
   - Descarga PHP desde: https://windows.php.net/download/
   - Extrae en `C:\php`
   - Agrega `C:\php` a las variables de entorno PATH
   - Copia `php.ini-development` a `php.ini`
   - Edita `php.ini` y descomenta las extensiones necesarias:
     ```ini
     extension=pdo_mysql
     extension=mbstring
     extension=openssl
     extension=fileinfo
     ```

2. **Instalar Composer:**
   - Descarga desde: https://getcomposer.org/Composer-Setup.exe
   - Ejecuta el instalador y sigue las instrucciones

3. **Instalar Node.js:**
   - Descarga desde: https://nodejs.org/
   - Ejecuta el instalador (versión LTS recomendada)

4. **Instalar MySQL:**
   - Descarga XAMPP desde: https://www.apachefriends.org/
   - Instala solo MySQL
   - Inicia MySQL desde el panel de XAMPP

5. **Clonar y configurar el proyecto:**
   ```powershell
   git clone https://github.com/devambross/laravel-docker-webApp.git
   cd laravel-docker-webApp\src
   
   # Copiar archivo de configuración
   copy .env.example .env
   
   # Instalar dependencias
   composer install
   npm install
   
   # Generar clave de aplicación
   php artisan key:generate
   ```

6. **Configurar base de datos:**
   - Edita `src\.env`:
     ```env
     DB_CONNECTION=mysql
     DB_HOST=127.0.0.1
     DB_PORT=3306
     DB_DATABASE=laravel_club
     DB_USERNAME=root
     DB_PASSWORD=
     ```
   - Crea la base de datos:
     ```powershell
     # Desde XAMPP, abre phpMyAdmin (http://localhost/phpmyadmin)
     # Crea una base de datos llamada "laravel_club"
     ```

7. **Ejecutar migraciones:**
   ```powershell
   php artisan migrate
   ```

8. **Iniciar el servidor:**
   ```powershell
   php artisan serve
   ```

9. **Acceder a la aplicación:**
   - Abre tu navegador y ve a: http://localhost:8000

---

## macOS (Español)

### Opción 1: Con Virtualización (Recomendado - Mac Intel o Apple Silicon)

**Requisitos:**
- macOS 11 o superior
- Mac con chip Intel o Apple Silicon (M1/M2/M3)

**Pasos:**

1. **Instalar Homebrew (si no lo tienes):**
   ```bash
   /bin/bash -c "$(curl -fsSL https://raw.githubusercontent.com/Homebrew/install/HEAD/install.sh)"
   ```

2. **Instalar Docker Desktop:**
   ```bash
   brew install --cask docker
   ```
   - O descarga desde: https://www.docker.com/products/docker-desktop
   - Abre Docker Desktop desde Applications
   - Acepta los términos y espera a que inicie

3. **Clonar el proyecto:**
   ```bash
   git clone https://github.com/devambross/laravel-docker-webApp.git
   cd laravel-docker-webApp
   ```

4. **Iniciar el proyecto:**
   ```bash
   docker compose up -d --build
   ```

5. **Acceder a la aplicación:**
   - Abre tu navegador y ve a: http://localhost:8080

6. **Detener el proyecto:**
   ```bash
   docker compose down
   ```

### Opción 2: Sin Docker (Instalación Nativa)

**Requisitos:**
- macOS 10.15 o superior
- Homebrew instalado

**Pasos:**

1. **Instalar dependencias:**
   ```bash
   # Instalar PHP
   brew install php@8.2
   
   # Instalar Composer
   brew install composer
   
   # Instalar Node.js
   brew install node
   
   # Instalar MySQL
   brew install mysql
   brew services start mysql
   ```

2. **Configurar MySQL:**
   ```bash
   # Configurar contraseña root
   mysql_secure_installation
   
   # Crear base de datos
   mysql -u root -p
   CREATE DATABASE laravel_club;
   EXIT;
   ```

3. **Clonar y configurar el proyecto:**
   ```bash
   git clone https://github.com/devambross/laravel-docker-webApp.git
   cd laravel-docker-webApp/src
   
   # Copiar archivo de configuración
   cp .env.example .env
   
   # Instalar dependencias
   composer install
   npm install
   
   # Generar clave
   php artisan key:generate
   ```

4. **Configurar base de datos en .env:**
   ```bash
   nano .env
   ```
   Edita las líneas:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=laravel_club
   DB_USERNAME=root
   DB_PASSWORD=tu_contraseña
   ```

5. **Ejecutar migraciones:**
   ```bash
   php artisan migrate
   ```

6. **Iniciar servidor:**
   ```bash
   php artisan serve
   ```

7. **Acceder a la aplicación:**
   - Abre tu navegador y ve a: http://localhost:8000

---

## Linux (Español)

### Opción 1: Con Docker (Recomendado)

**Requisitos:**
- Distribución Linux moderna (Ubuntu 20.04+, Debian 11+, Fedora 35+, etc.)
- Virtualización habilitada (verifica con: `egrep -c '(vmx|svm)' /proc/cpuinfo` - debe ser > 0)

**Pasos para Ubuntu/Debian:**

1. **Actualizar sistema:**
   ```bash
   sudo apt update
   sudo apt upgrade -y
   ```

2. **Instalar Docker:**
   ```bash
   # Instalar dependencias
   sudo apt install -y ca-certificates curl gnupg lsb-release
   
   # Agregar clave GPG de Docker
   sudo mkdir -p /etc/apt/keyrings
   curl -fsSL https://download.docker.com/linux/ubuntu/gpg | sudo gpg --dearmor -o /etc/apt/keyrings/docker.gpg
   
   # Agregar repositorio
   echo \
     "deb [arch=$(dpkg --print-architecture) signed-by=/etc/apt/keyrings/docker.gpg] https://download.docker.com/linux/ubuntu \
     $(lsb_release -cs) stable" | sudo tee /etc/apt/sources.list.d/docker.list > /dev/null
   
   # Instalar Docker Engine
   sudo apt update
   sudo apt install -y docker-ce docker-ce-cli containerd.io docker-compose-plugin
   
   # Agregar tu usuario al grupo docker
   sudo usermod -aG docker $USER
   newgrp docker
   ```

3. **Verificar instalación:**
   ```bash
   docker --version
   docker compose version
   ```

4. **Clonar el proyecto:**
   ```bash
   git clone https://github.com/devambross/laravel-docker-webApp.git
   cd laravel-docker-webApp
   ```

5. **Iniciar el proyecto:**
   ```bash
   docker compose up -d --build
   ```

6. **Acceder a la aplicación:**
   - Abre tu navegador y ve a: http://localhost:8080

7. **Detener el proyecto:**
   ```bash
   docker compose down
   ```

**Pasos para Fedora/RHEL/CentOS:**

1. **Instalar Docker:**
   ```bash
   # Instalar dependencias
   sudo dnf -y install dnf-plugins-core
   
   # Agregar repositorio
   sudo dnf config-manager --add-repo https://download.docker.com/linux/fedora/docker-ce.repo
   
   # Instalar Docker
   sudo dnf install -y docker-ce docker-ce-cli containerd.io docker-compose-plugin
   
   # Iniciar Docker
   sudo systemctl start docker
   sudo systemctl enable docker
   
   # Agregar usuario al grupo
   sudo usermod -aG docker $USER
   newgrp docker
   ```

2. **Continuar con los pasos 3-7 de Ubuntu**

### Opción 2: Sin Docker (Instalación Nativa)

**Para Ubuntu/Debian:**

1. **Instalar dependencias:**
   ```bash
   # Actualizar sistema
   sudo apt update
   sudo apt upgrade -y
   
   # Instalar PHP y extensiones
   sudo apt install -y php8.2 php8.2-cli php8.2-common php8.2-mysql \
     php8.2-zip php8.2-gd php8.2-mbstring php8.2-curl php8.2-xml php8.2-bcmath
   
   # Instalar Composer
   curl -sS https://getcomposer.org/installer | php
   sudo mv composer.phar /usr/local/bin/composer
   
   # Instalar Node.js
   curl -fsSL https://deb.nodesource.com/setup_20.x | sudo -E bash -
   sudo apt install -y nodejs
   
   # Instalar MySQL
   sudo apt install -y mysql-server
   sudo systemctl start mysql
   sudo systemctl enable mysql
   ```

2. **Configurar MySQL:**
   ```bash
   sudo mysql_secure_installation
   
   # Crear base de datos
   sudo mysql -u root -p
   CREATE DATABASE laravel_club;
   CREATE USER 'laravel_user'@'localhost' IDENTIFIED BY 'tu_password';
   GRANT ALL PRIVILEGES ON laravel_club.* TO 'laravel_user'@'localhost';
   FLUSH PRIVILEGES;
   EXIT;
   ```

3. **Clonar y configurar proyecto:**
   ```bash
   git clone https://github.com/devambross/laravel-docker-webApp.git
   cd laravel-docker-webApp/src
   
   cp .env.example .env
   composer install
   npm install
   php artisan key:generate
   ```

4. **Configurar .env:**
   ```bash
   nano .env
   ```
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=laravel_club
   DB_USERNAME=laravel_user
   DB_PASSWORD=tu_password
   ```

5. **Ejecutar migraciones e iniciar:**
   ```bash
   php artisan migrate
   php artisan serve
   ```

6. **Acceder:** http://localhost:8000

---

# Installation Guide (English)

## Windows (English)

### Option 1: With Virtualization Enabled (Recommended)

**Requirements:**
- Windows 10/11 Pro, Enterprise, or Education (64-bit)
- Virtualization enabled in BIOS (Intel VT-x or AMD-V)
- WSL 2 installed

**Steps:**

1. **Check if virtualization is enabled:**
   - Open Task Manager (Ctrl + Shift + Esc)
   - Go to "Performance" tab
   - Select "CPU" and verify "Virtualization" shows "Enabled"
   - If not enabled, restart and access BIOS (usually F2, F10, or DEL during boot)

2. **Install WSL 2:**
   ```powershell
   # Run PowerShell as Administrator
   wsl --install
   ```
   - Restart your computer when prompted
   - Set up your Ubuntu username and password when it opens

3. **Install Docker Desktop:**
   - Download Docker Desktop from: https://www.docker.com/products/docker-desktop
   - Run the installer
   - During installation, make sure to check "Use WSL 2 instead of Hyper-V"
   - Restart your computer
   - Open Docker Desktop and wait for it to fully start

4. **Clone the project:**
   ```powershell
   # In PowerShell or CMD
   git clone https://github.com/devambross/laravel-docker-webApp.git
   cd laravel-docker-webApp
   ```

5. **Start the project:**
   ```powershell
   docker compose up -d --build
   ```

6. **Access the application:**
   - Open your browser and go to: http://localhost:8080

7. **Stop the project:**
   ```powershell
   docker compose down
   ```

### Option 2: Without Virtualization (Windows Home or without VT-x)

**Requirements:**
- Windows 10/11 Home or versions without virtualization
- PHP 8.1 or higher
- Composer
- Node.js 16+
- MySQL or MariaDB

**Steps:**

1. **Install PHP:**
   - Download PHP from: https://windows.php.net/download/
   - Extract to `C:\php`
   - Add `C:\php` to PATH environment variable
   - Copy `php.ini-development` to `php.ini`
   - Edit `php.ini` and uncomment necessary extensions:
     ```ini
     extension=pdo_mysql
     extension=mbstring
     extension=openssl
     extension=fileinfo
     ```

2. **Install Composer:**
   - Download from: https://getcomposer.org/Composer-Setup.exe
   - Run installer and follow instructions

3. **Install Node.js:**
   - Download from: https://nodejs.org/
   - Run installer (LTS version recommended)

4. **Install MySQL:**
   - Download XAMPP from: https://www.apachefriends.org/
   - Install only MySQL
   - Start MySQL from XAMPP control panel

5. **Clone and configure project:**
   ```powershell
   git clone https://github.com/devambross/laravel-docker-webApp.git
   cd laravel-docker-webApp\src
   
   # Copy configuration file
   copy .env.example .env
   
   # Install dependencies
   composer install
   npm install
   
   # Generate application key
   php artisan key:generate
   ```

6. **Configure database:**
   - Edit `src\.env`:
     ```env
     DB_CONNECTION=mysql
     DB_HOST=127.0.0.1
     DB_PORT=3306
     DB_DATABASE=laravel_club
     DB_USERNAME=root
     DB_PASSWORD=
     ```
   - Create database:
     ```powershell
     # From XAMPP, open phpMyAdmin (http://localhost/phpmyadmin)
     # Create a database named "laravel_club"
     ```

7. **Run migrations:**
   ```powershell
   php artisan migrate
   ```

8. **Start server:**
   ```powershell
   php artisan serve
   ```

9. **Access application:**
   - Open your browser and go to: http://localhost:8000

---

## macOS (English)

### Option 1: With Virtualization (Recommended - Intel or Apple Silicon Mac)

**Requirements:**
- macOS 11 or higher
- Mac with Intel chip or Apple Silicon (M1/M2/M3)

**Steps:**

1. **Install Homebrew (if you don't have it):**
   ```bash
   /bin/bash -c "$(curl -fsSL https://raw.githubusercontent.com/Homebrew/install/HEAD/install.sh)"
   ```

2. **Install Docker Desktop:**
   ```bash
   brew install --cask docker
   ```
   - Or download from: https://www.docker.com/products/docker-desktop
   - Open Docker Desktop from Applications
   - Accept terms and wait for it to start

3. **Clone the project:**
   ```bash
   git clone https://github.com/devambross/laravel-docker-webApp.git
   cd laravel-docker-webApp
   ```

4. **Start the project:**
   ```bash
   docker compose up -d --build
   ```

5. **Access the application:**
   - Open your browser and go to: http://localhost:8080

6. **Stop the project:**
   ```bash
   docker compose down
   ```

### Option 2: Without Docker (Native Installation)

**Requirements:**
- macOS 10.15 or higher
- Homebrew installed

**Steps:**

1. **Install dependencies:**
   ```bash
   # Install PHP
   brew install php@8.2
   
   # Install Composer
   brew install composer
   
   # Install Node.js
   brew install node
   
   # Install MySQL
   brew install mysql
   brew services start mysql
   ```

2. **Configure MySQL:**
   ```bash
   # Set root password
   mysql_secure_installation
   
   # Create database
   mysql -u root -p
   CREATE DATABASE laravel_club;
   EXIT;
   ```

3. **Clone and configure project:**
   ```bash
   git clone https://github.com/devambross/laravel-docker-webApp.git
   cd laravel-docker-webApp/src
   
   # Copy configuration file
   cp .env.example .env
   
   # Install dependencies
   composer install
   npm install
   
   # Generate key
   php artisan key:generate
   ```

4. **Configure database in .env:**
   ```bash
   nano .env
   ```
   Edit lines:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=laravel_club
   DB_USERNAME=root
   DB_PASSWORD=your_password
   ```

5. **Run migrations:**
   ```bash
   php artisan migrate
   ```

6. **Start server:**
   ```bash
   php artisan serve
   ```

7. **Access application:**
   - Open your browser and go to: http://localhost:8000

---

## Linux (English)

### Option 1: With Docker (Recommended)

**Requirements:**
- Modern Linux distribution (Ubuntu 20.04+, Debian 11+, Fedora 35+, etc.)
- Virtualization enabled (check with: `egrep -c '(vmx|svm)' /proc/cpuinfo` - should be > 0)

**Steps for Ubuntu/Debian:**

1. **Update system:**
   ```bash
   sudo apt update
   sudo apt upgrade -y
   ```

2. **Install Docker:**
   ```bash
   # Install dependencies
   sudo apt install -y ca-certificates curl gnupg lsb-release
   
   # Add Docker's GPG key
   sudo mkdir -p /etc/apt/keyrings
   curl -fsSL https://download.docker.com/linux/ubuntu/gpg | sudo gpg --dearmor -o /etc/apt/keyrings/docker.gpg
   
   # Add repository
   echo \
     "deb [arch=$(dpkg --print-architecture) signed-by=/etc/apt/keyrings/docker.gpg] https://download.docker.com/linux/ubuntu \
     $(lsb_release -cs) stable" | sudo tee /etc/apt/sources.list.d/docker.list > /dev/null
   
   # Install Docker Engine
   sudo apt update
   sudo apt install -y docker-ce docker-ce-cli containerd.io docker-compose-plugin
   
   # Add your user to docker group
   sudo usermod -aG docker $USER
   newgrp docker
   ```

3. **Verify installation:**
   ```bash
   docker --version
   docker compose version
   ```

4. **Clone the project:**
   ```bash
   git clone https://github.com/devambross/laravel-docker-webApp.git
   cd laravel-docker-webApp
   ```

5. **Start the project:**
   ```bash
   docker compose up -d --build
   ```

6. **Access the application:**
   - Open your browser and go to: http://localhost:8080

7. **Stop the project:**
   ```bash
   docker compose down
   ```

**Steps for Fedora/RHEL/CentOS:**

1. **Install Docker:**
   ```bash
   # Install dependencies
   sudo dnf -y install dnf-plugins-core
   
   # Add repository
   sudo dnf config-manager --add-repo https://download.docker.com/linux/fedora/docker-ce.repo
   
   # Install Docker
   sudo dnf install -y docker-ce docker-ce-cli containerd.io docker-compose-plugin
   
   # Start Docker
   sudo systemctl start docker
   sudo systemctl enable docker
   
   # Add user to group
   sudo usermod -aG docker $USER
   newgrp docker
   ```

2. **Continue with steps 3-7 from Ubuntu**

### Option 2: Without Docker (Native Installation)

**For Ubuntu/Debian:**

1. **Install dependencies:**
   ```bash
   # Update system
   sudo apt update
   sudo apt upgrade -y
   
   # Install PHP and extensions
   sudo apt install -y php8.2 php8.2-cli php8.2-common php8.2-mysql \
     php8.2-zip php8.2-gd php8.2-mbstring php8.2-curl php8.2-xml php8.2-bcmath
   
   # Install Composer
   curl -sS https://getcomposer.org/installer | php
   sudo mv composer.phar /usr/local/bin/composer
   
   # Install Node.js
   curl -fsSL https://deb.nodesource.com/setup_20.x | sudo -E bash -
   sudo apt install -y nodejs
   
   # Install MySQL
   sudo apt install -y mysql-server
   sudo systemctl start mysql
   sudo systemctl enable mysql
   ```

2. **Configure MySQL:**
   ```bash
   sudo mysql_secure_installation
   
   # Create database
   sudo mysql -u root -p
   CREATE DATABASE laravel_club;
   CREATE USER 'laravel_user'@'localhost' IDENTIFIED BY 'your_password';
   GRANT ALL PRIVILEGES ON laravel_club.* TO 'laravel_user'@'localhost';
   FLUSH PRIVILEGES;
   EXIT;
   ```

3. **Clone and configure project:**
   ```bash
   git clone https://github.com/devambross/laravel-docker-webApp.git
   cd laravel-docker-webApp/src
   
   cp .env.example .env
   composer install
   npm install
   php artisan key:generate
   ```

4. **Configure .env:**
   ```bash
   nano .env
   ```
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=laravel_club
   DB_USERNAME=laravel_user
   DB_PASSWORD=your_password
   ```

5. **Run migrations and start:**
   ```bash
   php artisan migrate
   php artisan serve
   ```

6. **Access:** http://localhost:8000

---

## 🔧 Troubleshooting / Solución de Problemas

### Docker Issues / Problemas con Docker

**"Cannot connect to Docker daemon"**
- **ES:** Asegúrate de que Docker Desktop esté ejecutándose
- **EN:** Make sure Docker Desktop is running

**"Port 8080 already in use"**
- **ES:** Cambia el puerto en `docker-compose.yml` (ej: `"8081:80"`)
- **EN:** Change the port in `docker-compose.yml` (e.g., `"8081:80"`)

### Database Issues / Problemas de Base de Datos

**"Access denied for user"**
- **ES:** Verifica credenciales en archivo `.env`
- **EN:** Check credentials in `.env` file

**"Database does not exist"**
- **ES:** Crea la base de datos manualmente o ejecuta `php artisan migrate`
- **EN:** Create database manually or run `php artisan migrate`

---

## Additional Documentation / Documentación Adicional

- [ARQUITECTURA.md](ARQUITECTURA.md) - System architecture / Arquitectura del sistema
- [INSTALACION.md](INSTALACION.md) - Detailed installation / Instalación detallada
- [FUNCIONALIDADES_IMPLEMENTADAS.md](FUNCIONALIDADES_IMPLEMENTADAS.md) - Features / Funcionalidades
- [EJEMPLOS_API.md](EJEMPLOS_API.md) - API examples / Ejemplos de API
- [TESTING.md](TESTING.md) - Testing guide / Guía de pruebas

---

## Support / Soporte

For issues or questions / Para problemas o preguntas:
- Open an issue / Abre un issue: https://github.com/devambross/laravel-docker-webApp/issues

---

## License / Licencia

This project is open source / Este proyecto es de código abierto.
