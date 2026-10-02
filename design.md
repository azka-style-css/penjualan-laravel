# Design — Kasir Simple · Toko RPL Jaya

A locked design system for this app. Every page redesign reads this file before
emitting code. Do not regenerate per page — extend or amend this file when the
system needs to grow.

## Genre

modern-minimal — panel instrumen kasir: tenang, data-dulu, satu aksen.

## Macrostructure family

- App pages: **Workbench** — shell sidebar gelap (N3 side-rail; drawer di mobile),
  konten paper terang. Variasi knob per halaman:
  - Dashboard → stat strip terbuka ber-hairline; angka Total Penjualan terbesar
    (aksen teal) sebagai hero tipografis, dua stat lain sekunder.
  - Transaksi Baru → two-pane workbench: picker + keranjang kiri, ringkasan bayar
    sticky kanan dipisah hairline vertikal (bukan kartu).
  - Detail Transaksi → artefak struk: kolom sempit mono ala struk thermal.
- Marketing pages: tidak ada (aplikasi internal).
- Content pages: tidak ada.

## Theme

Jangkar: teal brand yang sudah ada (`#0f766e`). Seluruh warna lewat token —
tidak ada hex inline di Blade.

- `--color-paper`   oklch(97.5% 0.005 185)  — latar konten
- `--color-paper-2` oklch(95.5% 0.007 185)  — hover baris, addon input
- `--color-surface` oklch(99.5% 0.003 185)  — kartu
- `--color-rule`    oklch(90.5% 0.006 185)  — border hairline
- `--color-ink`     oklch(21% 0.02 220)     — teks utama + sidebar
- `--color-ink-2`   oklch(30% 0.015 220)    — teks sekunder
- `--color-muted`   oklch(46% 0.012 210)    — label, placeholder
- `--color-accent`  oklch(51% 0.096 186)    — teal brand: nav aktif, CTA primer
- `--color-accent-dark` oklch(44% 0.08 187) — hover primer, teks di atas soft
- `--color-accent-soft` oklch(94% 0.025 185)
- `--color-focus`   oklch(60% 0.118 184)
- `--color-danger`  oklch(57% 0.19 27) · `--color-warning` oklch(68% 0.16 65)
- Sidebar: `--color-sidebar` = ink · `--color-sidebar-ink` oklch(82% 0.01 220)
  · `--color-sidebar-label` oklch(65% 0.012 220) (kontras ≥ 4.5:1 di atas ink)

## Typography

- Display: **Space Grotesk** (variable), 600, tracking -0.02em — judul halaman, angka stat.
- Body: **Geist** (variable), 400/500 — seluruh UI; `tabular-nums` di semua tabel & uang.
- Outlier: **JetBrains Mono** (variable) — SATU peran: kode/nomor transaksi + struk.
- Self-hosted via Fontsource (npm) — tanpa CDN. Ikon: Bootstrap Icons (npm, lokal).
- Skala: major third dari 16px; judul halaman 1.375rem, stat 1.625–1.875rem, body tabel .875rem.

## Spacing

Skala 4-point bawaan Tailwind. Kedalaman lewat hairline `--color-rule`, BUKAN bayangan
dan BUKAN kartu bertumpuk.

**Doktrin radius:** semua permukaan & kontainer TAJAM (radius 0). Pill (999px) hanya
untuk tombol & badge — affordance bulat, kontainer tajam.

**Doktrin kontainer:** tanpa kartu/boks. Tabel, form, stat strip, dan filter langsung
di atas paper, dipisah hairline. Pengecualian: struk (artefak kertas, border tajam)
dan dialog konfirmasi.

## Motion

- Easings: `--ease-out: cubic-bezier(0.16, 1, 0.3, 1)`; durasi 120–220ms.
- Reveal pattern: tidak ada — halaman langsung tampil.
- Yang bergerak: hover/focus, drawer mobile, spinner submit.
- Reduced-motion: semua transisi/animasi dipangkas.

## Microinteractions stance

- Hapus data: `<dialog>` native ber-nama barang (bukan `confirm()`); aksi destruktif permanen.
- Submit form: tombol disable + spinner "Menyimpan…" (anti double-submit).
- Flash message: dismiss manual, tanpa toast selebrasi berulang.
- Focus ring: muncul instan, `var(--color-focus)`.
- Error form: per-field (invalid feedback) — tanpa alert validasi global ganda.

## CTA voice

- Primary: pill fill `accent`, teks `accent-ink`, ikon + label, `white-space: nowrap`.
- Secondary: pill outline `rule`, hover `paper-2`.
- Danger: outline `danger` (aksi baris) · solid `danger` hanya di dialog konfirmasi.
- Bahasa UI seragam: Indonesia (Simpan · Batal · Ubah · Hapus · Cetak · Kembali).

## Per-page allowances

- App pages MUST NOT use enrichment — fungsi membawa halaman.
- Struk (transaksi/show) boleh full mono sebagai artefak cetak.
- Input "Uang Bayar"/"Kembalian" bersifat UI-murni (tanpa atribut `name`) —
  tidak menyentuh backend sampai endpoint-nya siap.

## What pages MUST share

- Wordmark "Kasir Simple" + sub "Toko RPL Jaya" di sidebar.
- Aksen teal ≤ 5% viewport; satu resep tabel (label mikro uppercase + hairline + hover).
- Font display/body/mono sesuai peran; CTA voice di atas.
- Page-head: judul display + subjudul muted + aksi kanan (wrap aman di 320px).

## What pages MAY differ on

- Komposisi grid dalam keluarga Workbench (stat-led vs two-pane vs artefak).
- Kolom tabel sesuai data; bukan gayanya.

## Hutang yang disengaja (butuh backend — di luar scope redesign)

- Pagination Data Barang (`barang/index`): controller harus `paginate()` dulu,
  lalu view memakai `components/pagination` yang sama dengan transaksi.

## Exports

Sumber kebenaran token: `resources/css/app.css` (`@theme` Tailwind v4 memancarkan
semua variabel ke `:root`, sekaligus menyediakan utility `bg-*`/`text-*`/`font-*`).
