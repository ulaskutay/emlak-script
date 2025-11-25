# EstateFlow - Emlak Yönetim Paneli

Modern, özellik dolu bir emlak yönetim admin paneli. Laravel 11 ve Tailwind CSS ile geliştirilmiştir.

## 🚀 Özellikler

- **Dashboard** - İstatistikler ve son ilanlar
- **İlan Yönetimi** - İlan oluşturma, düzenleme, silme ve filtreleme
- **Talep Yönetimi** - Durum takibi ve emlakçı atama
- **Müşteri Yönetimi** - CRM özellikleri
- **Emlakçı Yönetimi** - Emlakçı istatistikleri
- **Takvim** - Randevu yönetimi
- **Ayarlar** - Sistem ayarları (Sadece Admin)
- **Rol Tabanlı Erişim** - Admin ve Emlakçı rolleri
- **Türkçe Dil Desteği**

## 📋 Gereksinimler

- PHP 8.2 veya üzeri
- Composer
- Node.js ve NPM
- MySQL 5.7 veya üzeri
- macOS, Linux veya Windows

## 🛠️ Kurulum

### 1. Projeyi İndirin

Projeyi indirdiyseniz, proje dizinine gidin:

```bash
cd "/Users/eticajans/Desktop/Etic Ajans/Projeler/Mobil Uygulama/Emlak Script"
```

### 2. PHP ve Composer Kurulumu (macOS)

Eğer PHP yüklü değilse:

```bash
# Homebrew ile PHP yükleyin
brew install php@8.3

# PHP'yi PATH'e ekleyin (kalıcı olması için ~/.zshrc dosyasına eklenir)
echo 'export PATH="/opt/homebrew/opt/php@8.3/bin:$PATH"' >> ~/.zshrc
echo 'export PATH="/opt/homebrew/opt/php@8.3/sbin:$PATH"' >> ~/.zshrc

# Yeni terminal penceresi açın veya şu komutu çalıştırın:
export PATH="/opt/homebrew/opt/php@8.3/bin:$PATH"

# Composer yükleyin
brew install composer
```

### 3. MySQL Kurulumu ve Başlatma (macOS)

Eğer MySQL yüklü değilse:

```bash
# MySQL yükleyin
brew install mysql

# MySQL servisini başlatın
brew services start mysql

# Veritabanını oluşturun
/opt/homebrew/opt/mysql/bin/mysql -u root -e "CREATE DATABASE IF NOT EXISTS estateflow CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
```

### 4. Bağımlılıkları Yükleyin

```bash
# PHP bağımlılıklarını yükleyin
composer install

# Node.js bağımlılıklarını yükleyin
npm install
```

### 5. Ortam Değişkenlerini Ayarlayın

`.env` dosyasını oluşturun ve düzenleyin:

```bash
# .env.example dosyasından kopyalayın (eğer yoksa oluşturulacak)
cp .env.example .env

# Uygulama anahtarını oluşturun
php artisan key:generate
```

`.env` dosyasında veritabanı ayarlarını yapın:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=estateflow
DB_USERNAME=root
DB_PASSWORD=
```

**Not:** Eğer MySQL root kullanıcısı için şifre ayarladıysanız, `DB_PASSWORD` alanına şifrenizi yazın.

**Adres ve Harita:** Proje ücretsiz OpenStreetMap/Nominatim servislerini kullanmaktadır. Herhangi bir API anahtarı gerekmez.

### 6. Veritabanını Oluşturun

Migration'ları çalıştırın ve demo verileri ekleyin:

```bash
php artisan migrate --seed
```

### 7. Storage Link'ini Oluşturun

```bash
php artisan storage:link
```

### 8. Cache'i Temizleyin

```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
```

## ▶️ Projeyi Başlatma

### Geliştirme Modu

1. **PHP PATH'ini ayarlayın** (Yeni terminal açıldığında):

```bash
export PATH="/opt/homebrew/opt/php@8.3/bin:$PATH"
```

2. **Laravel sunucusunu başlatın**:

```bash
php artisan serve
```

Sunucu `http://localhost:8000` adresinde başlayacaktır.

3. **Asset'leri derleyin** (ayrı bir terminal penceresinde):

```bash
npm run dev
```

Veya production için:

```bash
npm run build
```

### Tarayıcıda Açma

Tarayıcınızda şu adrese gidin:
```
http://localhost:8000/login
```

## 🔐 Giriş Bilgileri

Seeder çalıştırıldıktan sonra aşağıdaki kullanıcılarla giriş yapabilirsiniz:

### Admin Kullanıcı
- **Email:** `admin@estateflow.com`
- **Şifre:** `password`

### Emlakçı Kullanıcılar
- **Email:** `agent1@example.com`, `agent2@example.com`, vb.
- **Şifre:** `password`

**Not:** 5 adet emlakçı kullanıcısı oluşturulur.

## 📁 Proje Yapısı

```
app/
├── Http/
│   ├── Controllers/      # Tüm controller'lar
│   ├── Middleware/       # Özel middleware'ler
│   └── Requests/         # Form request validatörleri
├── Models/               # Eloquent modelleri
└── Policies/             # Yetkilendirme politikaları

database/
├── migrations/           # Veritabanı migration'ları
├── seeders/             # Veri seed'leri
└── factories/           # Model factory'leri

resources/
├── views/               # Blade şablonları
│   ├── layouts/         # Ana layout
│   ├── dashboard/       # Dashboard görünümleri
│   ├── listings/        # İlan görünümleri
│   └── ...
└── css/                 # Tailwind CSS
```

## 🎨 Özellikler Detayı

### Dashboard
- Toplam ilan sayısı
- Aktif ilan sayısı
- Aylık satılan/kiralanan sayısı
- Bugünün yeni talepleri
- Son ilanlar tablosu
- En iyi emlakçılar widget'ı (sadece admin)

### İlanlar
- Tam CRUD işlemleri
- Durum, tip, emlakçı ve arama ile filtreleme
- Çoklu fotoğraf yükleme
- Kapak fotoğrafı seçimi
- Renkli durum rozetleri
- Formatlanmış fiyat gösterimi

### Rol Tabanlı Erişim
- **Admin:** Tüm özelliklere tam erişim
- **Emlakçı:** Sadece kendi ilanlarına, taleplerine ve müşterilerine erişim

## 🔧 Sorun Giderme

### PHP Komutu Bulunamıyor

```bash
# PHP'yi PATH'e ekleyin
export PATH="/opt/homebrew/opt/php@8.3/bin:$PATH"

# Kalıcı olması için ~/.zshrc dosyasına ekleyin
echo 'export PATH="/opt/homebrew/opt/php@8.3/bin:$PATH"' >> ~/.zshrc
source ~/.zshrc
```

### MySQL Bağlantı Hatası

```bash
# MySQL'in çalıştığını kontrol edin
brew services list | grep mysql

# MySQL'i başlatın
brew services start mysql

# Veritabanının var olduğunu kontrol edin
/opt/homebrew/opt/mysql/bin/mysql -u root -e "SHOW DATABASES;"
```

### Permission Hatası

```bash
# Storage dizinlerine yazma izni verin
chmod -R 775 storage
chmod -R 775 bootstrap/cache
```

### Cache Sorunları

```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear
```

## 📝 Kullanışlı Komutlar

```bash
# Migration'ları çalıştır
php artisan migrate

# Seeder'ları çalıştır
php artisan db:seed

# Migration ve seeder'ı birlikte çalıştır
php artisan migrate --seed

# Route listesini görüntüle
php artisan route:list

# Tinker ile veritabanına eriş
php artisan tinker

# Asset'leri derle (development)
npm run dev

# Asset'leri derle (production)
npm run build
```

## 🌐 Çoklu Dil Desteği

Dil dosyaları `lang/` dizininde bulunur. Yeni bir dil eklemek için:

1. `lang/` dizininde yeni bir klasör oluşturun (örn: `lang/en/`)
2. `lang/tr/` dizinindeki dosyaları kopyalayın ve çevirin
3. `.env` dosyasında `APP_LOCALE` değerini değiştirin

## 🔒 Güvenlik

- Üretim ortamında `.env` dosyasında `APP_DEBUG=false` olarak ayarlayın
- Güçlü bir `APP_KEY` kullanın
- MySQL root şifresi ayarlayın
- HTTPS kullanın

## 📚 Gelişmiş Özellikler

Proje, gelecekte çoklu acente desteği eklemek için yapılandırılmıştır:

1. İlgili tablolara `agency_id` ekleyin
2. Modellerde scope filtreleri ekleyin
3. Politikaları güncelleyin

## 🐛 Hata Bildirimi

Herhangi bir hata bulursanız, lütfen proje deposunda issue oluşturun.

## 📄 Lisans

MIT Lisansı

## 👥 Katkıda Bulunanlar

Bu proje EstateFlow ekibi tarafından geliştirilmiştir.

## 📞 Destek

Sorularınız için issue açabilir veya e-posta gönderebilirsiniz.

---

**İyi çalışmalar! 🎉**

