# Selamat datang di ILKOM 2017 BOT

Ilkom 2017 Bot merupakan bot telegram sebagai portal informasi Mahasiswa Ilkom UNNES. Dikembangkan untuk memudahkan mahasiswa dalam mencari informasi perkuliahan, beasiswa, dan jadwal.

Di bot ini memungkinkan mahasiswa untuk menambahkan info umum, info seputar akademik, info seputar non akademik, lomba, seminar, workshop dan sebagainya serta info-info tentang beasiswa perkuliahan.

Untuk mahasiswa ilkom seluruhnya sangat terbuka untuk menambahkan fitur sendiri di sini, melaporkan bug dan sebagainya.

# Bagaimana berkontribusi menambah perintah

1. Rencanakan dulu perintahnya mau tentang apa. Disarankan seputar pendidikan, untuk hiburan juga tidak apa-apa asal wajar.
2. Pilih kode perintah misal /perintah.
3. Buat file php dengan nama NamaperintahCommand.php (Huruf pertama kapital, huruf pada tulisan Command juga kapital).
4. Disini ada tiga tipe perintah, yaitu admin, sistem, dan user.
5. Lalu bagaimana dengan isinya? Disarankan paham tentang PHP dan pengolahan Json. Bacalah dengan teliti penjelasan di sini [Create your own command - php-telegram-bot](https://github.com/php-telegram-bot/core/wiki/Create-your-own-commands)
6. Silahkan pull request di repositori ini.

# Library

Seluruh kode program Bot dari [php-telegram-bot](https://github.com/php-telegram-bot)

# API

Bot ini menggunakan beberapa API, diantaranya
- Ilkom Unnes API = Informasi umum, informasi beasiswa, dan informasi jadwal. (Homepage on progress)
- [Github API](https://api.github.com) = Informasi pencarian repositori dan informasi user github.
- [Kateglo API](http://kateglo.com) = Fitur KBBI.
- [Dyseo API](https://dyseo.herokuapp.com) = Fitur Translate.
- IT Book API = Fitur pencarian buku kategori IT