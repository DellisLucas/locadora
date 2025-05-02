# 🚗 Locadora de Veículos – API Laravel

- Autenticação via JWT
- CRUD de veículos, clientes e aluguéis
- Busca com Elasticsearch
- Integração com serviço Python para relatórios
- Fila de jobs com Laravel Queues
- Testes automatizados

---

## ✅ Requisitos

- PHP 8.2+
- Composer
- PostgreSQL ou MySQL
- Elasticsearch 7.17+
- Docker (opcional para Elasticsearch ou Python)
- Python 3.10+
- Laravel 12.x

---

## ⚙️ Setup do Projeto Laravel

```bash
git clone https://github.com/seu-usuario/locadora-api.git
cd locadora-api

# Instalar dependências
composer install

# Configurar variáveis de ambiente
cp .env.example .env
php artisan key:generate

# Configure o banco no .env e rode:
php artisan migrate

# Gerar chave JWT
php artisan jwt:secret
```

---

## 🔁 Fila (Queue) para Elasticsearch

```bash
php artisan queue:table
php artisan migrate
php artisan queue:work
```

---

## 🔍 Elasticsearch com Docker (opcional)

```bash
docker run -p 9200:9200 -e "discovery.type=single-node" elasticsearch:7.17.10
```

No `.env`:
```env
ELASTICSEARCH_HOST=http://localhost:9200
QUEUE_CONNECTION=database
```

---

## 📊 Serviço Python de Relatório (opcional)

```bash
cd relatorio-python
python -m venv venv
venv\Scripts\activate    # Windows
# source venv/bin/activate  # Linux/Mac

pip install -r requirements.txt
uvicorn main:app --reload --port 8001
```

No `.env` do Laravel:

```env
REPORT_SERVICE_URL=http://localhost:8001
```

---

## 🚀 Rotas da API (via Postman ou curl)

### 🧑 Autenticação

```bash
POST /api/register
POST /api/login
POST /api/logout
```

### 🚗 Veículos

```bash
GET /api/vehicles
POST /api/vehicles
PUT /api/vehicles/{id}
DELETE /api/vehicles/{id}
GET /api/vehicles/search?q=uno
```

### 👤 Clientes

```bash
GET /api/customers
POST /api/customers
PUT /api/customers/{id}
DELETE /api/customers/{id}
```

### 📅 Aluguéis

```bash
POST /api/rentals
POST /api/rentals/{id}/start
POST /api/rentals/{id}/end
GET  /api/rentals
GET  /api/rentals/{id}
```

### 📈 Relatórios (Python + Laravel)

```bash
GET /api/reports/revenue?start=2024-01-01&end=2024-12-31
```

---

## ✅ Testes

```bash
php artisan test
```

---

## 📁 Organização do Projeto

```
app/
 ├── Http/
 │    └── Controllers/
 ├── Jobs/
 ├── Models/
 └── Providers/

tests/
 └── Feature/
      ├── AuthControllerTest.php
      ├── VehicleControllerTest.php
      ├── RentalControllerTest.php
      ├── CustomerControllerTest.php
      └── ReportControllerTest.php

relatorio-python/
 ├── main.py
 ├── requirements.txt
 └── ...
```

---

## 📌 Observações

- A API usa autenticação JWT (via `tymon/jwt-auth`)
- O Elasticsearch está integrado com fila de jobs
- O serviço de relatório em Python retorna agregações de alugueis
- Testes de funcionalidades principais incluídos

---

## 👨‍💻 Autor

[Lucas Dellis]