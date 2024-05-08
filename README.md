# Cara Develop Secara Lokal
---
1. Clone repository
```
git clone https://github.com/Drajad-Kusuma-Adi/onstudy-backend.git
```
2. Masuk ke folder repository
```
cd onstudy-backend
```
3. Install semua depedency di Composer
```
composer install
```
4. Jalankan Docker Compose
```
docker compose up
```
5. Ganti nama file ``.env.example`` menjadi ``.env``
6. Jalankan server Laravel
```
php artisan serve
```
7. Laravel siap berjalan dalam environment development
