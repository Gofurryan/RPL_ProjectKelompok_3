<x-mail::message>
# Halo, {{ $user->name }}!

Anda menerima email ini karena kami menerima permintaan pengaturan ulang kata sandi untuk akun Inventaris Ibadah Anda.

<x-mail::button :url="$url" color="primary">
Atur Ulang Kata Sandi
</x-mail::button>

Tautan pengaturan ulang kata sandi ini akan kedaluwarsa dalam 60 menit.
Jika Anda tidak meminta pengaturan ulang kata sandi, abaikan saja email ini.

Hormat kami,<br>
**Pengurus Inventaris Ibadah**
</x-mail::message>