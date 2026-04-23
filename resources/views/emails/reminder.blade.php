<x-mail::message>
# Halo, {{ $loan->user->name }}!

Ini adalah pesan pengingat otomatis dari sistem **Inventaris Tempat Ibadah**. 

Kami ingin mengingatkan bahwa batas waktu pengembalian barang yang Anda pinjam adalah **besok hari**. Mohon segera persiapkan barang-barang tersebut agar tidak terkena denda keterlambatan (Rp 10.000/hari).

**Rincian Peminjaman (ID: #{{ $loan->id }}):**
* **Tanggal Pinjam:** {{ \Carbon\Carbon::parse($loan->loan_date)->translatedFormat('d F Y') }}
* **Batas Pengembalian:** {{ \Carbon\Carbon::parse($loan->due_date)->translatedFormat('d F Y') }}

**Barang yang dipinjam:**
@foreach($loan->details as $detail)
- {{ $detail->item->name }} ({{ $detail->quantity }} unit)
@endforeach

<x-mail::button :url="route('login')">
Cek Dashboard Anda
</x-mail::button>

Terima kasih atas kerja samanya menjaga barang inventaris bersama.<br>
Hormat kami,<br>
**Pengurus Inventaris Ibadah**
</x-mail::message>