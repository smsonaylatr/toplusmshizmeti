---
description: Tüm projelerde varsayılan kullanıcı bilgilerini oluşturma kuralı
---

# Varsayılan Kullanıcı Bilgileri

Tüm projelerde aşağıdaki varsayılan kullanıcı bilgileri kullanılmalıdır:

## Admin Kullanıcı
- **Email:** admin@admin.com
- **Kullanıcı Adı / Name:** Admin
- **Şifre:** admin123
- **Rol:** Admin (is_admin: true veya role: admin)

## Customer Kullanıcı
- **Email:** customer@customer.com
- **Kullanıcı Adı / Name:** Customer
- **Şifre:** customer123
- **Rol:** Normal kullanıcı (is_admin: false veya role: customer)

## Uygulama Kuralları

1. Yeni bir proje oluşturulduğunda veya seed dosyası yazılırken her zaman bu iki kullanıcıyı oluştur.
2. `updateOrCreate` kullan ki tekrarlanan seed'lerde hata oluşmasın.
3. Şifreler `Hash::make()` veya framework'ün uygun hash fonksiyonu ile hashlenmelidir.
4. Login sayfalarında email alanı kullanılıyorsa email ile, username alanı kullanılıyorsa:
   - Admin username: `admin`
   - Customer username: `customer`

## Örnek Laravel Seeder

```php
// Admin kullanıcı
User::updateOrCreate(
    ['email' => 'admin@admin.com'],
    [
        'name' => 'Admin',
        'password' => Hash::make('admin123'),
        'is_admin' => true,
    ]
);

// Customer kullanıcı
User::updateOrCreate(
    ['email' => 'customer@customer.com'],
    [
        'name' => 'Customer',
        'password' => Hash::make('customer123'),
        'is_admin' => false,
    ]
);
```
