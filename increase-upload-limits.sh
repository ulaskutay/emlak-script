#!/bin/bash

# PHP Upload Limits Artırma Scripti
# Bu script PHP'nin dosya yükleme limitlerini artırır

PHP_INI="/opt/homebrew/etc/php/8.5/php.ini"

if [ ! -f "$PHP_INI" ]; then
    echo "Hata: PHP.ini dosyası bulunamadı: $PHP_INI"
    echo "Lütfen PHP.ini dosyasının konumunu kontrol edin."
    exit 1
fi

echo "PHP.ini dosyası bulundu: $PHP_INI"
echo ""
echo "Mevcut ayarlar:"
grep -E "upload_max_filesize|post_max_size" "$PHP_INI" | head -2

echo ""
echo "Ayarlar güncelleniyor..."

# Backup oluştur
cp "$PHP_INI" "${PHP_INI}.backup.$(date +%Y%m%d_%H%M%S)"
echo "Yedek oluşturuldu: ${PHP_INI}.backup.$(date +%Y%m%d_%H%M%S)"

# Ayarları güncelle
sed -i '' 's/^upload_max_filesize = .*/upload_max_filesize = 10M/' "$PHP_INI"
sed -i '' 's/^post_max_size = .*/post_max_size = 12M/' "$PHP_INI"

echo ""
echo "Güncellenmiş ayarlar:"
grep -E "upload_max_filesize|post_max_size" "$PHP_INI" | head -2

echo ""
echo "✓ PHP.ini dosyası güncellendi!"
echo ""
echo "Değişikliklerin etkili olması için PHP-FPM servisini yeniden başlatmanız gerekiyor:"
echo "  brew services restart php"
echo ""
echo "Veya web sunucusunu yeniden başlatın (eğer built-in server kullanıyorsanız, terminalde Ctrl+C ile durdurup tekrar başlatın)."

