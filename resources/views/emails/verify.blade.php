<x-mail::message>
# Halo, {{ $user->name }}!

Selamat datang di sistem **Inventaris Ibadah**. 

Terima kasih telah mendaftar. Untuk memastikan keamanan dan mengaktifkan akun Anda sepenuhnya, silakan klik tombol di bawah ini untuk memverifikasi alamat email Anda.

<x-mail::button :url="$url" color="success">
Verifikasi Email Saya
</x-mail::button>

*Catatan: Tautan verifikasi ini akan kedaluwarsa dalam 60 menit.*

Jika Anda tidak merasa mendaftar di sistem kami, Anda bisa mengabaikan email ini.

Hormat kami,<br>
**Pengurus Inventaris Ibadah**
</x-mail::message>