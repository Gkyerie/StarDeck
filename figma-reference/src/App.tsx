import { FormEvent, ReactNode, useMemo, useState } from "react"

type Page = "masuk" | "dasbor-komandan" | "inventaris" | "kategori" | "astronot" | "pinjaman-aktif" | "riwayat" | "dasbor-astronot" | "katalog" | "profil" | "pinjaman"

type Unit = {
  id: number
  nama: string
  kode: string
  tipe: "Permainan Papan" | "Konsol"
  kondisi: string
  kategori: string
  status: "Tersedia" | "Dipinjam"
  deskripsi: string
  warna: string
  ikon: string
}

type Anggota = {
  id: string
  nama: string
  jabatan: string
  modul: string
  status: "Aktif" | "Ditangguhkan"
}

type PinjamanData = {
  id: number
  anggotaId: string
  anggota: string
  unitKode: string
  unitNama: string
  tanggalPinjam: string
  jatuhTempo: string
  status: "Aktif" | "Dikembalikan"
  tanggalKembali?: string
  hariTerlambat?: number
  denda?: number
}

const unitAwal: Unit[] = [
  {
    id: 1,
    nama: "Terraformasi Mars",
    kode: "SD-BG-001",
    tipe: "Permainan Papan",
    kondisi: "Sangat Baik",
    kategori: "Strategi",
    status: "Dipinjam",
    deskripsi: "Bangun koloni dan ubah planet merah menjadi rumah baru.",
    warna: "from-orange-500/30 to-rose-900/20",
    ikon: "◈",
  },
  {
    id: 2,
    nama: "Penjelajah Catan",
    kode: "SD-BG-002",
    tipe: "Permainan Papan",
    kondisi: "Baik",
    kategori: "Keluarga",
    status: "Tersedia",
    deskripsi: "Berdagang, membangun, dan kuasai wilayah antariksa.",
    warna: "from-cyan-500/30 to-blue-900/20",
    ikon: "⬡",
  },
  {
    id: 3,
    nama: "PlayStation 5",
    kode: "SD-CS-001",
    tipe: "Konsol",
    kondisi: "Sangat Baik",
    kategori: "Konsol Rumah",
    status: "Dipinjam",
    deskripsi: "Konsol generasi terbaru untuk hiburan di modul rekreasi.",
    warna: "from-violet-500/30 to-indigo-900/20",
    ikon: "✦",
  },
  {
    id: 4,
    nama: "Nintendo Switch OLED",
    kode: "SD-CS-002",
    tipe: "Konsol",
    kondisi: "Baik",
    kategori: "Portabel",
    status: "Tersedia",
    deskripsi: "Konsol serbaguna dengan layar OLED yang tajam.",
    warna: "from-emerald-500/30 to-teal-900/20",
    ikon: "◎",
  },
  {
    id: 5,
    nama: "Wingspan Galaksi",
    kode: "SD-BG-003",
    tipe: "Permainan Papan",
    kondisi: "Cukup Baik",
    kategori: "Strategi",
    status: "Tersedia",
    deskripsi: "Kumpulkan spesies langka dari berbagai planet.",
    warna: "from-fuchsia-500/30 to-purple-900/20",
    ikon: "⌁",
  },
  {
    id: 6,
    nama: "Xbox Series S",
    kode: "SD-CS-003",
    tipe: "Konsol",
    kondisi: "Sangat Baik",
    kategori: "Konsol Rumah",
    status: "Tersedia",
    deskripsi: "Konsol ringkas untuk sesi permainan bersama kru.",
    warna: "from-lime-500/30 to-green-900/20",
    ikon: "✣",
  },
  {
    id: 7,
    nama: "Pandemi Orbit",
    kode: "SD-BG-004",
    tipe: "Permainan Papan",
    kondisi: "Baik",
    kategori: "Keluarga",
    status: "Dipinjam",
    deskripsi: "Selamatkan koloni dari wabah antargalaksi bersama seluruh kru.",
    warna: "from-rose-500/30 to-red-900/20",
    ikon: "✧",
  },
]

const anggotaAwal: Anggota[] = [
  {
    id: "AST-2048",
    nama: "Nara Rasyid",
    jabatan: "Astronot Senior",
    modul: "Modul Navigasi",
    status: "Aktif",
  },
  {
    id: "AST-1820",
    nama: "Bima Adinata",
    jabatan: "Teknisi Modul",
    modul: "Modul Rekreasi",
    status: "Aktif",
  },
  {
    id: "AST-2311",
    nama: "Sari Amanda",
    jabatan: "Peneliti Utama",
    modul: "Modul Riset",
    status: "Aktif",
  },
  {
    id: "AST-1907",
    nama: "Dara Rahman",
    jabatan: "Petugas Medis",
    modul: "Modul Medis",
    status: "Aktif",
  },
]

const pinjamanAwal: PinjamanData[] = [
  {
    id: 1,
    anggotaId: "AST-2048",
    anggota: "Nara Rasyid",
    unitKode: "SD-BG-001",
    unitNama: "Terraformasi Mars",
    tanggalPinjam: "14 Jun 2025",
    jatuhTempo: "19 Jun 2025",
    status: "Aktif",
  },
  {
    id: 2,
    anggotaId: "AST-2311",
    anggota: "Sari Amanda",
    unitKode: "SD-CS-001",
    unitNama: "PlayStation 5",
    tanggalPinjam: "12 Jun 2025",
    jatuhTempo: "17 Jun 2025",
    status: "Aktif",
  },
  {
    id: 3,
    anggotaId: "AST-2311",
    anggota: "Sari Amanda",
    unitKode: "SD-BG-004",
    unitNama: "Pandemi Orbit",
    tanggalPinjam: "16 Jun 2025",
    jatuhTempo: "21 Jun 2025",
    status: "Aktif",
  },
  {
    id: 4,
    anggotaId: "AST-1820",
    anggota: "Bima Adinata",
    unitKode: "SD-CS-002",
    unitNama: "Nintendo Switch OLED",
    tanggalPinjam: "01 Jun 2025",
    jatuhTempo: "06 Jun 2025",
    tanggalKembali: "08 Jun 2025",
    hariTerlambat: 2,
    denda: 10000,
    status: "Dikembalikan",
  },
  {
    id: 5,
    anggotaId: "AST-1907",
    anggota: "Dara Rahman",
    unitKode: "SD-BG-002",
    unitNama: "Penjelajah Catan",
    tanggalPinjam: "03 Jun 2025",
    jatuhTempo: "08 Jun 2025",
    tanggalKembali: "08 Jun 2025",
    hariTerlambat: 0,
    denda: 0,
    status: "Dikembalikan",
  },
]

const navKomandan = [
  ["dasbor-komandan", "Dasbor", "grid"],
  ["inventaris", "Unit & Inventaris", "box"],
  ["kategori", "Kategori", "tag"],
  ["astronot", "Data Astronot", "users"],
  ["pinjaman-aktif", "Pinjaman Aktif", "clock"],
  ["riwayat", "Riwayat & Laporan", "file"],
] as const

const navAstronot = [
  ["dasbor-astronot", "Beranda", "grid"],
  ["katalog", "Katalog Unit", "search"],
  ["pinjaman", "Pinjaman Saya", "clock"],
  ["profil", "Profil Astronot", "user"],
] as const

function Ikon({
  nama,
  className = "h-5 w-5",
}: {
  nama: string
  className?: string
}) {
  const path: Record<string, ReactNode> = {
    grid: (
      <>
        <rect x="3" y="3" width="7" height="7" rx="2" />
        <rect x="14" y="3" width="7" height="7" rx="2" />
        <rect x="3" y="14" width="7" height="7" rx="2" />
        <rect x="14" y="14" width="7" height="7" rx="2" />
      </>
    ),
    box: (
      <>
        <path d="m4 7 8-4 8 4-8 4-8-4Z" />
        <path d="m4 7 8 4 8-4v10l-8 4-8-4V7Z" />
        <path d="M12 11v10" />
      </>
    ),
    tag: <path d="M20 13 13 20l-9-9V4h7l9 9Z M8.5 8.5h.01" />,
    users: (
      <>
        <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" />
        <circle cx="9" cy="7" r="4" />
        <path d="M22 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75" />
      </>
    ),
    search: (
      <>
        <circle cx="11" cy="11" r="7" />
        <path d="m20 20-4-4" />
      </>
    ),
    clock: (
      <>
        <circle cx="12" cy="12" r="9" />
        <path d="M12 7v5l3 2" />
      </>
    ),
    user: (
      <>
        <circle cx="12" cy="8" r="4" />
        <path d="M4 21a8 8 0 0 1 16 0" />
      </>
    ),
    bell: (
      <>
        <path d="M18 8a6 6 0 0 0-12 0c0 7-3 7-3 9h18c0-2-3-2-3-9" />
        <path d="M10 21h4" />
      </>
    ),
    plus: <path d="M12 5v14M5 12h14" />,
    edit: (
      <>
        <path d="M12 20h9" />
        <path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L8 18l-4 1 1-4Z" />
      </>
    ),
    trash: (
      <>
        <path d="M3 6h18M8 6V4h8v2M19 6l-1 15H6L5 6M10 11v6M14 11v6" />
      </>
    ),
    file: (
      <>
        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8Z" />
        <path d="M14 2v6h6M8 13h8M8 17h6" />
      </>
    ),
    chevron: <path d="m9 18 6-6-6-6" />,
    logout: (
      <>
        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" />
        <path d="m16 17 5-5-5-5M21 12H9" />
      </>
    ),
    menu: <path d="M4 7h16M4 12h16M4 17h16" />,
    close: <path d="m6 6 12 12M18 6 6 18" />,
    check: <path d="m5 12 4 4L19 6" />,
    alert: (
      <>
        <path d="M10.3 3.7 2.4 18a2 2 0 0 0 1.7 3h15.8a2 2 0 0 0 1.7-3L13.7 3.7a2 2 0 0 0-3.4 0Z" />
        <path d="M12 9v4M12 17h.01" />
      </>
    ),
  }
  return (
    <svg
      className={className}
      viewBox="0 0 24 24"
      fill="none"
      stroke="currentColor"
      strokeWidth="1.8"
      strokeLinecap="round"
      strokeLinejoin="round"
      aria-hidden="true"
    >
      {path[nama]}
    </svg>
  )
}

function Logo({ ringkas = false }: { ringkas?: boolean }) {
  return (
    <div className="flex items-center gap-3">
      <div className="relative flex h-10 w-10 shrink-0 items-center justify-center rounded-xl border border-cyan-300/30 bg-cyan-300/10 text-cyan-300 shadow-[0_0_25px_rgba(34,211,238,.15)]">
        <div className="absolute h-6 w-6 rotate-45 rounded-sm border border-current opacity-50" />
        <span className="text-lg font-bold">S</span>
      </div>
      {!ringkas && (
        <div>
          <div className="text-lg font-bold tracking-tight text-white">
            Star<span className="text-cyan-300">Deck</span>
          </div>
          <div className="text-[10px] uppercase tracking-[.22em] text-slate-500">
            Pusat Rekreasi Orbital
          </div>
        </div>
      )}
    </div>
  )
}

function Tombol({
  children,
  varian = "utama",
  className = "",
  ikon,
  type = "button",
  onClick,
  disabled = false,
}: {
  children: ReactNode
  varian?: "utama" | "sekunder" | "bahaya" | "halus"
  className?: string
  ikon?: string
  type?: "button" | "submit"
  onClick?: () => void
  disabled?: boolean
}) {
  const gaya = {
    utama:
      "bg-cyan-300 text-slate-950 hover:bg-cyan-200 active:scale-[.98] shadow-[0_8px_24px_rgba(34,211,238,.15)]",
    sekunder:
      "border border-slate-700 bg-slate-900/80 text-slate-200 hover:border-cyan-300/50 hover:text-cyan-200 active:bg-slate-800",
    bahaya:
      "border border-rose-400/25 bg-rose-400/10 text-rose-300 hover:bg-rose-400/20 active:scale-[.98]",
    halus:
      "text-slate-400 hover:bg-white/5 hover:text-white active:bg-white/10",
  }
  return (
    <button
      type={type}
      onClick={onClick}
      disabled={disabled}
      className={`inline-flex min-h-10 items-center justify-center gap-2 rounded-xl px-4 text-sm font-semibold transition-all focus:outline-none focus:ring-2 focus:ring-cyan-300/40 disabled:cursor-not-allowed disabled:opacity-40 ${gaya[varian]} ${className}`}
    >
      {ikon && <Ikon nama={ikon} className="h-4 w-4" />}
      {children}
    </button>
  )
}

function Masukan({
  label,
  error,
  ...props
}: React.InputHTMLAttributes<HTMLInputElement> & {
  label: string
  error?: string
}) {
  return (
    <label className="block space-y-2">
      <span className="text-sm font-medium text-slate-300">{label}</span>
      <input
        {...props}
        className={`w-full rounded-xl border bg-slate-950/60 px-4 py-3 text-sm text-white outline-none transition placeholder:text-slate-600 focus:border-cyan-300/60 focus:ring-4 focus:ring-cyan-300/5 ${
          error ? "border-rose-400/70" : "border-slate-700/80"
        }`}
      />
      {error && (
        <span className="flex items-center gap-1.5 text-xs text-rose-300">
          <Ikon nama="alert" className="h-3.5 w-3.5" />
          {error}
        </span>
      )}
    </label>
  )
}

function Badge({
  children,
  jenis = "netral",
}: {
  children: ReactNode
  jenis?: "sukses" | "peringatan" | "netral" | "ungu"
}) {
  const gaya = {
    sukses: "border-emerald-400/20 bg-emerald-400/10 text-emerald-300",
    peringatan: "border-amber-400/20 bg-amber-400/10 text-amber-300",
    netral: "border-slate-600/40 bg-slate-700/30 text-slate-300",
    ungu: "border-violet-400/20 bg-violet-400/10 text-violet-300",
  }
  return (
    <span
      className={`inline-flex items-center rounded-full border px-2.5 py-1 text-[11px] font-semibold ${gaya[jenis]}`}
    >
      {children}
    </span>
  )
}

function Modal({
  judul,
  children,
  onClose,
  lebar = "max-w-lg",
}: {
  judul: string
  children: ReactNode
  onClose: () => void
  lebar?: string
}) {
  return (
    <div
      className="fixed inset-0 z-50 flex items-center justify-center bg-slate-950/80 p-4 backdrop-blur-md"
      onMouseDown={onClose}
    >
      <div
        role="dialog"
        aria-modal="true"
        aria-label={judul}
        onMouseDown={(e) => e.stopPropagation()}
        className={`max-h-[90vh] w-full overflow-auto rounded-2xl border border-slate-700 bg-slate-900 shadow-2xl ${lebar}`}
      >
        <div className="sticky top-0 z-10 flex items-center justify-between border-b border-slate-800 bg-slate-900/95 px-6 py-5 backdrop-blur">
          <div className="text-lg font-bold text-white">{judul}</div>
          <Tombol
            varian="halus"
            className="!h-9 !min-h-9 !w-9 !p-0"
            onClick={onClose}
          >
            <Ikon nama="close" />
          </Tombol>
        </div>
        {children}
      </div>
    </div>
  )
}

function Kartu({
  children,
  className = "",
}: {
  children: ReactNode
  className?: string
}) {
  return (
    <div
      className={`rounded-2xl border border-slate-800 bg-slate-900/65 shadow-[0_16px_50px_rgba(0,0,0,.14)] ${className}`}
    >
      {children}
    </div>
  )
}

function Login({ setPage }: { setPage: (p: Page) => void }) {
  const [email, setEmail] = useState("komandan@stardeck.id")
  const [sandi, setSandi] = useState("orbit")
  const [bantuan, setBantuan] = useState(false)
  const submit = (e: FormEvent) => {
    e.preventDefault()
    setPage(email.includes("astronot") ? "dasbor-astronot" : "dasbor-komandan")
  }
  return (
    <main className="relative flex min-h-screen items-center justify-center overflow-hidden bg-slate-950 p-5">
      <div className="orbit orbit-a" />
      <div className="orbit orbit-b" />
      <div className="stars" />
      <div className="relative z-10 w-full max-w-md">
        <div className="mb-8 flex justify-center">
          <Logo />
        </div>
        <Kartu className="p-7 sm:p-9">
          <div className="mb-7">
            <div className="text-2xl font-bold text-white">
              Selamat datang kembali
            </div>
            <p className="mt-2 text-sm leading-6 text-slate-400">
              Masuk untuk mengelola koleksi dan pinjaman unit rekreasi stasiun.
            </p>
          </div>
          <form onSubmit={submit} className="space-y-5">
            <Masukan
              label="Alamat email"
              type="email"
              value={email}
              onChange={(e) => setEmail(e.target.value)}
            />
            <Masukan
              label="Kata sandi"
              type="password"
              value={sandi}
              onChange={(e) => setSandi(e.target.value)}
            />
            <div className="flex items-center gap-3 rounded-xl border border-rose-400/25 bg-rose-400/10 p-3.5 text-sm text-rose-200">
              <Ikon nama="alert" className="h-5 w-5 shrink-0" />
              <span>Email atau kata sandi salah</span>
            </div>
            <Tombol type="submit" className="w-full">
              Masuk ke StarDeck <Ikon nama="chevron" className="h-4 w-4" />
            </Tombol>
          </form>
          <div className="mt-5 flex items-center justify-between text-xs">
            <button
              onClick={() => setBantuan(true)}
              className="text-slate-400 underline-offset-4 transition hover:text-cyan-300 hover:underline"
            >
              Lupa kata sandi?
            </button>
            <span className="text-slate-600">Akses kru terverifikasi</span>
          </div>
        </Kartu>
        <div className="mt-5 grid grid-cols-2 gap-3">
          <Tombol varian="sekunder" onClick={() => setPage("dasbor-komandan")}>
            Pratinjau Komandan
          </Tombol>
          <Tombol varian="sekunder" onClick={() => setPage("dasbor-astronot")}>
            Pratinjau Astronot
          </Tombol>
        </div>
      </div>
      {bantuan && (
        <Modal judul="Pulihkan akses" onClose={() => setBantuan(false)}>
          <div className="space-y-5 p-6">
            <p className="text-sm leading-6 text-slate-400">
              Hubungi petugas sistem di Modul Komando atau kirim permintaan
              pemulihan melalui alamat email terdaftar.
            </p>
            <Masukan
              label="Alamat email terdaftar"
              placeholder="nama@stardeck.id"
            />
            <Tombol className="w-full" onClick={() => setBantuan(false)}>
              Kirim tautan pemulihan
            </Tombol>
          </div>
        </Modal>
      )}
    </main>
  )
}

function Kerangka({
  page,
  setPage,
  role,
  children,
}: {
  page: Page
  setPage: (p: Page) => void
  role: "komandan" | "astronot"
  children: ReactNode
}) {
  const [menu, setMenu] = useState(false)
  const [notifikasi, setNotifikasi] = useState(false)
  const nav = role === "komandan" ? navKomandan : navAstronot
  return (
    <div className="min-h-screen bg-slate-950 text-slate-100">
      <div className="pointer-events-none fixed inset-0 bg-[radial-gradient(circle_at_75%_15%,rgba(34,211,238,.055),transparent_27%),radial-gradient(circle_at_30%_80%,rgba(139,92,246,.045),transparent_25%)]" />
      <aside
        className={`fixed inset-y-0 left-0 z-40 w-64 border-r border-slate-800 bg-slate-950/95 p-5 backdrop-blur-xl transition-transform lg:translate-x-0 ${
          menu ? "translate-x-0" : "-translate-x-full"
        }`}
      >
        <div className="mb-8 flex items-center justify-between">
          <Logo />
          <Tombol
            varian="halus"
            className="!h-9 !min-h-9 !w-9 !p-0 lg:hidden"
            onClick={() => setMenu(false)}
          >
            <Ikon nama="close" />
          </Tombol>
        </div>
        <div className="mb-3 px-3 text-[10px] font-semibold uppercase tracking-[.2em] text-slate-600">
          {role === "komandan" ? "Pusat Komando" : "Portal Astronot"}
        </div>
        <nav className="space-y-1.5">
          {nav.map(([id, label, icon]) => (
            <button
              key={id}
              onClick={() => {
                setPage(id)
                setMenu(false)
              }}
              className={`group flex w-full items-center gap-3 rounded-xl px-3.5 py-3 text-sm font-medium transition-all ${
                page === id
                  ? "bg-cyan-300/10 text-cyan-300 shadow-[inset_3px_0_0_#67e8f9]"
                  : "text-slate-400 hover:bg-white/5 hover:text-white active:bg-white/10"
              }`}
            >
              <Ikon nama={icon} className="h-5 w-5" />
              <span>{label}</span>
            </button>
          ))}
        </nav>
        <div className="absolute bottom-5 left-5 right-5">
          <div className="mb-3 flex items-center gap-3 rounded-xl border border-slate-800 bg-slate-900/60 p-3">
            <div className="flex h-9 w-9 items-center justify-center rounded-lg bg-violet-400/10 font-bold text-violet-300">
              {role === "komandan" ? "AK" : "NR"}
            </div>
            <div className="min-w-0">
              <div className="truncate text-xs font-semibold text-white">
                {role === "komandan" ? "Arka Kusuma" : "Nara Rasyid"}
              </div>
              <div className="text-[10px] text-slate-500">
                {role === "komandan" ? "Komandan Stasiun" : "Astronot Senior"}
              </div>
            </div>
          </div>
          <Tombol
            varian="halus"
            ikon="logout"
            className="w-full justify-start"
            onClick={() => setPage("masuk")}
          >
            Keluar
          </Tombol>
        </div>
      </aside>
      {menu && (
        <div
          className="fixed inset-0 z-30 bg-slate-950/70 lg:hidden"
          onClick={() => setMenu(false)}
        />
      )}
      <div className="relative lg:pl-64">
        <header className="sticky top-0 z-20 flex h-16 items-center justify-between border-b border-slate-800 bg-slate-950/80 px-4 backdrop-blur-xl sm:px-7">
          <div className="flex items-center gap-3">
            <Tombol
              varian="halus"
              className="!h-9 !min-h-9 !w-9 !p-0 lg:hidden"
              onClick={() => setMenu(true)}
            >
              <Ikon nama="menu" />
            </Tombol>
            <div className="text-xs text-slate-500">
              Stasiun Andromeda <span className="mx-2 text-slate-700">/</span>{" "}
              <span className="text-slate-300">
                {nav.find((n) => n[0] === page)?.[1]}
              </span>
            </div>
          </div>
          <button
            onClick={() => setNotifikasi(true)}
            className="relative rounded-xl p-2.5 text-slate-400 transition hover:bg-white/5 hover:text-white active:bg-white/10"
          >
            <Ikon nama="bell" />
            <span className="absolute right-2 top-2 h-2 w-2 rounded-full bg-cyan-300 ring-2 ring-slate-950" />
          </button>
        </header>
        <main className="mx-auto max-w-[1500px] p-4 sm:p-7 lg:p-9">
          {children}
        </main>
      </div>
      {notifikasi && (
        <Modal judul="Notifikasi stasiun" onClose={() => setNotifikasi(false)}>
          <div className="divide-y divide-slate-800">
            <div className="p-5">
              <Badge jenis="peringatan">Perlu tindakan</Badge>
              <div className="mt-3 text-sm font-semibold text-white">
                Satu pinjaman melewati batas waktu
              </div>
              <p className="mt-1 text-xs text-slate-500">
                Periksa status pengembalian unit SD-BG-001.
              </p>
            </div>
            <div className="p-5">
              <Badge jenis="sukses">Inventaris</Badge>
              <div className="mt-3 text-sm font-semibold text-white">
                Unit baru siap dipinjam
              </div>
              <p className="mt-1 text-xs text-slate-500">
                Nintendo Switch OLED telah lolos pemeriksaan.
              </p>
            </div>
            <div className="p-5">
              <Tombol className="w-full" onClick={() => setNotifikasi(false)}>
                Tandai semua sudah dibaca
              </Tombol>
            </div>
          </div>
        </Modal>
      )}
    </div>
  )
}

function JudulHalaman({
  sapaan,
  judul,
  deskripsi,
  aksi,
}: {
  sapaan?: string
  judul: string
  deskripsi: string
  aksi?: ReactNode
}) {
  return (
    <div className="mb-7 flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
      <div>
        {sapaan && (
          <div className="mb-2 text-xs font-semibold uppercase tracking-[.18em] text-cyan-300">
            {sapaan}
          </div>
        )}
        <div className="text-2xl font-bold tracking-tight text-white sm:text-3xl">
          {judul}
        </div>
        <p className="mt-2 max-w-2xl text-sm leading-6 text-slate-400">
          {deskripsi}
        </p>
      </div>
      {aksi}
    </div>
  )
}

function DasborKomandan({ setPage }: { setPage: (p: Page) => void }) {
  const statistik = [
    ["48", "Total unit", "+3 bulan ini", "box"],
    ["12", "Sedang dipinjam", "25% inventaris", "clock"],
    ["Rp185.000", "Total denda terlambat", "4 pinjaman", "alert"],
    ["36", "Unit tersedia", "Siap dipinjam", "check"],
  ]
  return (
    <>
      <JudulHalaman
        sapaan="Selamat bertugas, Komandan Arka"
        judul="Ringkasan stasiun"
        deskripsi="Pantau inventaris rekreasi dan aktivitas peminjaman seluruh kru dari satu pusat kendali."
      />
      <div className="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        {statistik.map((s, i) => (
          <Kartu
            key={s[1]}
            className="p-5 transition hover:-translate-y-0.5 hover:border-slate-700"
          >
            <div className="mb-5 flex items-center justify-between">
              <div
                className={`flex h-10 w-10 items-center justify-center rounded-xl ${
                  i === 2
                    ? "bg-rose-400/10 text-rose-300"
                    : i === 3
                      ? "bg-emerald-400/10 text-emerald-300"
                      : "bg-cyan-300/10 text-cyan-300"
                }`}
              >
                <Ikon nama={s[3]} />
              </div>
              <span className="text-[11px] text-slate-500">{s[2]}</span>
            </div>
            <div className="text-2xl font-bold text-white">{s[0]}</div>
            <div className="mt-1 text-xs text-slate-400">{s[1]}</div>
          </Kartu>
        ))}
      </div>
      <div className="mt-7 grid gap-6 xl:grid-cols-[1.5fr_1fr]">
        <Kartu className="p-6">
          <div className="mb-5 flex items-center justify-between">
            <div>
              <div className="font-bold text-white">Aktivitas terkini</div>
              <p className="mt-1 text-xs text-slate-500">
                Perubahan dalam 24 jam terakhir
              </p>
            </div>
            <Badge jenis="sukses">Langsung</Badge>
          </div>
          <div className="space-y-1">
            {[
              ["NR", "Nara meminjam Terraformasi Mars", "10 menit lalu"],
              ["BA", "Bima mengembalikan Nintendo Switch OLED", "1 jam lalu"],
              ["SA", "Sari dikenai denda keterlambatan", "3 jam lalu"],
              ["DR", "Dara memperbarui profil astronot", "5 jam lalu"],
            ].map((a, i) => (
              <div
                key={a[1]}
                className="flex items-center gap-4 rounded-xl p-3 transition hover:bg-white/[.025]"
              >
                <div
                  className={`flex h-9 w-9 shrink-0 items-center justify-center rounded-full text-xs font-bold ${
                    i === 2
                      ? "bg-rose-400/10 text-rose-300"
                      : "bg-slate-800 text-slate-300"
                  }`}
                >
                  {a[0]}
                </div>
                <div className="min-w-0 flex-1 text-sm text-slate-300">
                  {a[1]}
                </div>
                <div className="hidden text-xs text-slate-600 sm:block">
                  {a[2]}
                </div>
              </div>
            ))}
          </div>
        </Kartu>
        <div>
          <div className="mb-4 font-bold text-white">Akses cepat</div>
          <div className="grid gap-3">
            {[
              [
                "inventaris",
                "box",
                "Kelola inventaris",
                "Tambah atau perbarui unit",
              ],
              ["kategori", "tag", "Atur kategori", "Kelompokkan koleksi"],
              [
                "astronot",
                "users",
                "Kelola astronot",
                "Lihat akses dan pinjaman",
              ],
            ].map(([id, icon, title, desc]) => (
              <button
                key={id}
                onClick={() => setPage(id as Page)}
                className="group flex items-center gap-4 rounded-2xl border border-slate-800 bg-slate-900/60 p-4 text-left transition hover:border-cyan-300/30 hover:bg-slate-900 active:scale-[.99]"
              >
                <div className="flex h-11 w-11 items-center justify-center rounded-xl bg-cyan-300/10 text-cyan-300 transition group-hover:bg-cyan-300 group-hover:text-slate-950">
                  <Ikon nama={icon} />
                </div>
                <div className="flex-1">
                  <div className="text-sm font-semibold text-white">
                    {title}
                  </div>
                  <div className="mt-1 text-xs text-slate-500">{desc}</div>
                </div>
                <Ikon
                  nama="chevron"
                  className="h-4 w-4 text-slate-600 transition group-hover:translate-x-1 group-hover:text-cyan-300"
                />
              </button>
            ))}
          </div>
        </div>
      </div>
    </>
  )
}

function DasborAstronot({ setPage }: { setPage: (p: Page) => void }) {
  const [cari, setCari] = useState("")
  return (
    <>
      <Kartu className="relative mb-7 overflow-hidden border-cyan-300/15 bg-gradient-to-r from-cyan-950/60 to-violet-950/50 p-7 sm:p-9">
        <div className="absolute -right-14 -top-16 h-52 w-52 rounded-full border border-cyan-300/10" />
        <div className="absolute -right-3 -top-5 h-32 w-32 rounded-full border border-violet-300/10" />
        <div className="relative max-w-2xl">
          <Badge jenis="ungu">Siklus 0842</Badge>
          <div className="mt-5 text-3xl font-bold text-white">
            Selamat kembali, Nara.
          </div>
          <p className="mt-3 text-sm leading-6 text-slate-300">
            Koleksi hiburan stasiun siap menemani waktu istirahatmu setelah misi
            hari ini.
          </p>
          <div className="mt-6 flex max-w-xl gap-2">
            <input
              value={cari}
              onChange={(e) => setCari(e.target.value)}
              onKeyDown={(e) => e.key === "Enter" && setPage("katalog")}
              placeholder="Cari nama unit..."
              className="min-w-0 flex-1 rounded-xl border border-white/10 bg-slate-950/50 px-4 py-3 text-sm outline-none placeholder:text-slate-500 focus:border-cyan-300/50"
            />
            <Tombol onClick={() => setPage("katalog")} ikon="search">
              Cari
            </Tombol>
          </div>
        </div>
      </Kartu>
      <div className="grid gap-6 lg:grid-cols-[1.15fr_.85fr]">
        <Kartu className="p-6">
          <div className="mb-5 flex flex-wrap items-center justify-between gap-3">
            <div>
              <div className="font-bold text-white">Pinjaman aktif</div>
              <div className="mt-1 text-xs text-slate-500">
                1 dari 2 slot digunakan
              </div>
            </div>
            <div className="rounded-xl border border-cyan-300/20 bg-cyan-300/10 px-3 py-2 text-xs font-bold text-cyan-200">
              Maksimal 2 unit pinjaman
            </div>
          </div>
          <div className="rounded-xl border border-slate-800 bg-slate-950/50 p-4">
            <div className="flex flex-col gap-4 sm:flex-row sm:items-center">
              <div className="flex h-14 w-14 items-center justify-center rounded-xl bg-orange-400/10 text-2xl text-orange-300">
                ◈
              </div>
              <div className="flex-1">
                <div className="font-semibold text-white">
                  Terraformasi Mars
                </div>
                <div className="mt-1 text-xs text-slate-500">
                  SD-BG-001 · Permainan Papan
                </div>
              </div>
              <div className="sm:text-right">
                <Badge jenis="peringatan">Jatuh tempo besok</Badge>
                <div className="mt-2 text-xs text-slate-500">18 Juni 2025</div>
              </div>
            </div>
          </div>
          <Tombol
            varian="sekunder"
            className="mt-4 w-full"
            onClick={() => setPage("pinjaman")}
          >
            Lihat rincian pinjaman
          </Tombol>
        </Kartu>
        <Kartu className="p-6">
          <div className="mb-5 font-bold text-white">Peringatan waktu</div>
          <div className="flex gap-4 rounded-xl border border-amber-400/20 bg-amber-400/10 p-4">
            <Ikon nama="clock" className="h-6 w-6 shrink-0 text-amber-300" />
            <div>
              <div className="text-sm font-semibold text-amber-100">
                1 unit segera jatuh tempo
              </div>
              <p className="mt-1 text-xs leading-5 text-amber-200/60">
                Kembalikan sebelum pukul 18.00 waktu stasiun untuk menghindari
                denda.
              </p>
            </div>
          </div>
          <div className="mt-5 grid grid-cols-2 gap-3">
            <div className="rounded-xl bg-slate-950/50 p-4">
              <div className="text-xl font-bold text-white">0</div>
              <div className="mt-1 text-xs text-slate-500">Denda aktif</div>
            </div>
            <div className="rounded-xl bg-slate-950/50 p-4">
              <div className="text-xl font-bold text-white">7</div>
              <div className="mt-1 text-xs text-slate-500">Total pinjaman</div>
            </div>
          </div>
        </Kartu>
      </div>
    </>
  )
}

function Katalog({ units }: { units: Unit[] }) {
  const [cari, setCari] = useState("")
  const [tampilan, setTampilan] = useState<"kisi" | "daftar">("kisi")
  const [filter, setFilter] = useState("Semua")
  const [detail, setDetail] = useState<Unit | null>(null)
  const hasil = useMemo(
    () =>
      units.filter(
        (u) =>
          u.nama.toLowerCase().includes(cari.toLowerCase()) &&
          (filter === "Semua" || u.tipe === filter),
      ),
    [units, cari, filter],
  )
  return (
    <>
      <JudulHalaman
        judul="Katalog unit"
        deskripsi="Temukan permainan papan dan konsol yang tersedia di pusat rekreasi orbital."
      />
      <Kartu className="mb-6 p-4">
        <div className="flex flex-col gap-3 md:flex-row">
          <div className="relative flex-1">
            <Ikon
              nama="search"
              className="absolute left-4 top-3.5 h-5 w-5 text-slate-500"
            />
            <input
              value={cari}
              onChange={(e) => setCari(e.target.value)}
              placeholder="Cari berdasarkan nama unit..."
              className="w-full rounded-xl border border-slate-700 bg-slate-950/60 py-3 pl-12 pr-4 text-sm outline-none focus:border-cyan-300/50"
            />
          </div>
          <div className="flex gap-2">
            {["Semua", "Permainan Papan", "Konsol"].map((f) => (
              <button
                key={f}
                onClick={() => setFilter(f)}
                className={`rounded-xl border px-3 text-xs font-semibold transition ${
                  filter === f
                    ? "border-cyan-300/30 bg-cyan-300/10 text-cyan-300"
                    : "border-slate-700 text-slate-400 hover:border-slate-600 hover:text-white"
                }`}
              >
                {f}
              </button>
            ))}
          </div>
          <div className="flex rounded-xl border border-slate-700 p-1">
            <button
              aria-label="Tampilan kisi"
              onClick={() => setTampilan("kisi")}
              className={`rounded-lg p-2 transition ${
                tampilan === "kisi"
                  ? "bg-slate-700 text-white"
                  : "text-slate-500 hover:text-white"
              }`}
            >
              <Ikon nama="grid" />
            </button>
            <button
              aria-label="Tampilan daftar"
              onClick={() => setTampilan("daftar")}
              className={`rounded-lg p-2 transition ${
                tampilan === "daftar"
                  ? "bg-slate-700 text-white"
                  : "text-slate-500 hover:text-white"
              }`}
            >
              <Ikon nama="menu" />
            </button>
          </div>
        </div>
      </Kartu>
      <div className="mb-4 text-xs text-slate-500">
        Menampilkan {hasil.length} unit
      </div>
      {hasil.length === 0 ? (
        <Kartu className="p-12 text-center">
          <Ikon nama="search" className="mx-auto h-8 w-8 text-slate-600" />
          <div className="mt-4 font-semibold text-white">
            Unit tidak ditemukan
          </div>
          <p className="mt-2 text-sm text-slate-500">
            Coba nama atau jenis unit yang lain.
          </p>
          <Tombol
            varian="sekunder"
            className="mt-5"
            onClick={() => {
              setCari("")
              setFilter("Semua")
            }}
          >
            Atur ulang pencarian
          </Tombol>
        </Kartu>
      ) : (
        <div
          className={
            tampilan === "kisi"
              ? "grid gap-5 sm:grid-cols-2 xl:grid-cols-3"
              : "space-y-3"
          }
        >
          {hasil.map((u) =>
            tampilan === "kisi" ? (
              <KartuUnit key={u.id} unit={u} onClick={() => setDetail(u)} />
            ) : (
              <button
                key={u.id}
                onClick={() => setDetail(u)}
                className="flex w-full flex-col gap-4 rounded-2xl border border-slate-800 bg-slate-900/60 p-4 text-left transition hover:border-cyan-300/25 hover:bg-slate-900 sm:flex-row sm:items-center"
              >
                <div
                  className={`flex h-16 w-20 items-center justify-center rounded-xl bg-gradient-to-br ${u.warna} text-2xl text-white`}
                >
                  {u.ikon}
                </div>
                <div className="flex-1">
                  <div className="font-semibold text-white">{u.nama}</div>
                  <div className="mt-1 text-xs text-slate-500">
                    {u.kode} · {u.tipe}
                  </div>
                </div>
                <Badge jenis="netral">{u.kategori}</Badge>
                <div className="text-xs text-slate-400">
                  Kondisi: {u.kondisi}
                </div>
                <Badge
                  jenis={u.status === "Tersedia" ? "sukses" : "peringatan"}
                >
                  {u.status}
                </Badge>
              </button>
            ),
          )}
        </div>
      )}
      {detail && (
        <Modal judul="Rincian unit" onClose={() => setDetail(null)}>
          <div className="p-6">
            <div
              className={`flex h-40 items-center justify-center rounded-2xl bg-gradient-to-br ${detail.warna} text-6xl text-white`}
            >
              {detail.ikon}
            </div>
            <div className="mt-5 flex items-start justify-between gap-3">
              <div>
                <div className="text-xl font-bold text-white">
                  {detail.nama}
                </div>
                <div className="mt-1 text-xs text-slate-500">{detail.kode}</div>
              </div>
              <Badge
                jenis={detail.status === "Tersedia" ? "sukses" : "peringatan"}
              >
                {detail.status}
              </Badge>
            </div>
            <p className="mt-4 text-sm leading-6 text-slate-400">
              {detail.deskripsi}
            </p>
            <div className="mt-5 grid grid-cols-3 gap-2 text-center">
              <div className="rounded-xl bg-slate-950/50 p-3">
                <div className="text-[10px] text-slate-600">Jenis</div>
                <div className="mt-1 text-xs text-slate-300">{detail.tipe}</div>
              </div>
              <div className="rounded-xl bg-slate-950/50 p-3">
                <div className="text-[10px] text-slate-600">Kategori</div>
                <div className="mt-1 text-xs text-slate-300">
                  {detail.kategori}
                </div>
              </div>
              <div className="rounded-xl bg-slate-950/50 p-3">
                <div className="text-[10px] text-slate-600">Kondisi</div>
                <div className="mt-1 text-xs text-slate-300">
                  {detail.kondisi}
                </div>
              </div>
            </div>
            <Tombol
              disabled={detail.status === "Dipinjam"}
              className="mt-6 w-full"
              onClick={() => setDetail(null)}
            >
              {detail.status === "Tersedia"
                ? "Ajukan peminjaman"
                : "Unit sedang dipinjam"}
            </Tombol>
          </div>
        </Modal>
      )}
    </>
  )
}

function KartuUnit({ unit, onClick }: { unit: Unit onClick: () => void }) {
  return (
    <button
      onClick={onClick}
      className="group overflow-hidden rounded-2xl border border-slate-800 bg-slate-900/60 text-left transition-all hover:-translate-y-1 hover:border-cyan-300/25 hover:shadow-[0_18px_45px_rgba(0,0,0,.22)] active:translate-y-0"
    >
      <div
        className={`relative flex h-36 items-center justify-center bg-gradient-to-br ${unit.warna}`}
      >
        <span className="text-5xl text-white/90 transition-transform group-hover:scale-110">
          {unit.ikon}
        </span>
        <div className="absolute left-3 top-3">
          <Badge jenis="netral">{unit.tipe}</Badge>
        </div>
        <div className="absolute right-3 top-3">
          <Badge jenis={unit.status === "Tersedia" ? "sukses" : "peringatan"}>
            {unit.status}
          </Badge>
        </div>
      </div>
      <div className="p-5">
        <div className="font-bold text-white">{unit.nama}</div>
        <div className="mt-1 text-xs font-medium text-cyan-300/70">
          {unit.kode}
        </div>
        <div className="mt-4 flex items-center justify-between">
          <Badge jenis="ungu">{unit.kategori}</Badge>
          <span className="text-xs text-slate-500">{unit.kondisi}</span>
        </div>
      </div>
    </button>
  )
}

function Inventaris({
  units,
  setUnits,
}: {
  units: Unit[]
  setUnits: (u: Unit[]) => void
}) {
  const [modal, setModal] = useState<"tambah" | "ubah" | null>(null)
  const [aktif, setAktif] = useState<Unit | null>(null)
  const [hapus, setHapus] = useState<Unit | null>(null)
  const [cari, setCari] = useState("")
  const [pesan, setPesan] = useState("")
  const bukaUbah = (u: Unit) => {
    setAktif(u)
    setModal("ubah")
  }
  const hapusUnit = () => {
    if (!hapus) return
    if (hapus.status === "Dipinjam") {
      setPesan(
        "Unit yang sedang dipinjam tidak dapat dihapus. Tunggu hingga unit dikembalikan.",
      )
      return
    }
    setUnits(units.filter((u) => u.id !== hapus.id))
    setHapus(null)
    setPesan("Unit berhasil dihapus dari inventaris.")
  }
  return (
    <>
      <JudulHalaman
        judul="Unit & inventaris"
        deskripsi="Kelola seluruh aset permainan dan konsol yang tersedia di stasiun."
        aksi={
          <Tombol
            ikon="plus"
            onClick={() => {
              setAktif(null)
              setModal("tambah")
            }}
          >
            Tambah Unit
          </Tombol>
        }
      />
      {pesan && (
        <div
          className={`mb-5 flex items-center justify-between gap-3 rounded-xl border p-4 text-sm ${
            pesan.includes("berhasil")
              ? "border-emerald-400/20 bg-emerald-400/10 text-emerald-200"
              : "border-rose-400/20 bg-rose-400/10 text-rose-200"
          }`}
        >
          <span className="flex items-center gap-2">
            <Ikon
              nama={pesan.includes("berhasil") ? "check" : "alert"}
              className="h-5 w-5"
            />
            {pesan}
          </span>
          <button
            onClick={() => setPesan("")}
            className="rounded-lg p-1 hover:bg-white/10"
          >
            <Ikon nama="close" className="h-4 w-4" />
          </button>
        </div>
      )}
      <Kartu className="overflow-hidden">
        <div className="flex flex-col gap-3 border-b border-slate-800 p-4 sm:flex-row sm:items-center sm:justify-between">
          <div className="relative w-full max-w-sm">
            <Ikon
              nama="search"
              className="absolute left-3 top-2.5 h-5 w-5 text-slate-500"
            />
            <input
              value={cari}
              onChange={(e) => setCari(e.target.value)}
              placeholder="Cari unit atau kode..."
              className="w-full rounded-xl border border-slate-700 bg-slate-950/60 py-2.5 pl-10 pr-4 text-sm outline-none focus:border-cyan-300/50"
            />
          </div>
          <div className="text-xs text-slate-500">
            {units.length} total unit
          </div>
        </div>
        <div className="overflow-x-auto">
          <table className="w-full min-w-[800px] text-left">
            <thead className="bg-slate-950/40 text-[10px] uppercase tracking-wider text-slate-500">
              <tr>
                <th className="px-5 py-4">Unit</th>
                <th className="px-5 py-4">Jenis</th>
                <th className="px-5 py-4">Kategori</th>
                <th className="px-5 py-4">Kondisi</th>
                <th className="px-5 py-4">Status</th>
                <th className="px-5 py-4 text-right">Tindakan</th>
              </tr>
            </thead>
            <tbody className="divide-y divide-slate-800">
              {units
                .filter((u) =>
                  `${u.nama}${u.kode}`
                    .toLowerCase()
                    .includes(cari.toLowerCase()),
                )
                .map((u) => (
                  <tr key={u.id} className="transition hover:bg-white/[.025]">
                    <td className="px-5 py-4">
                      <div className="text-sm font-semibold text-white">
                        {u.nama}
                      </div>
                      <div className="mt-1 text-xs text-cyan-300/60">
                        {u.kode}
                      </div>
                    </td>
                    <td className="px-5 py-4 text-xs text-slate-400">
                      {u.tipe}
                    </td>
                    <td className="px-5 py-4">
                      <Badge jenis="ungu">{u.kategori}</Badge>
                    </td>
                    <td className="px-5 py-4 text-xs text-slate-400">
                      {u.kondisi}
                    </td>
                    <td className="px-5 py-4">
                      <Badge
                        jenis={
                          u.status === "Tersedia" ? "sukses" : "peringatan"
                        }
                      >
                        {u.status}
                      </Badge>
                    </td>
                    <td className="px-5 py-4">
                      <div className="flex justify-end gap-2">
                        <Tombol
                          varian="sekunder"
                          ikon="edit"
                          onClick={() => bukaUbah(u)}
                        >
                          Ubah
                        </Tombol>
                        <Tombol
                          varian="bahaya"
                          ikon="trash"
                          onClick={() => {
                            setHapus(u)
                            setPesan("")
                          }}
                        >
                          Hapus
                        </Tombol>
                      </div>
                    </td>
                  </tr>
                ))}
            </tbody>
          </table>
        </div>
      </Kartu>
      {modal && (
        <FormUnit
          mode={modal}
          unit={aktif}
          units={units}
          onClose={() => setModal(null)}
          onSave={(unit) => {
            setUnits(
              modal === "ubah"
                ? units.map((u) => (u.id === unit.id ? unit : u))
                : [...units, unit],
            )
            setModal(null)
            setPesan(
              modal === "ubah"
                ? "Perubahan unit berhasil disimpan."
                : "Unit baru berhasil ditambahkan.",
            )
          }}
        />
      )}
      {hapus && (
        <Modal judul="Hapus unit" onClose={() => setHapus(null)}>
          <div className="p-6">
            <div
              className={`mb-5 flex h-12 w-12 items-center justify-center rounded-xl ${
                hapus.status === "Dipinjam"
                  ? "bg-amber-400/10 text-amber-300"
                  : "bg-rose-400/10 text-rose-300"
              }`}
            >
              <Ikon nama={hapus.status === "Dipinjam" ? "alert" : "trash"} />
            </div>
            <div className="font-semibold text-white">
              {hapus.status === "Dipinjam"
                ? "Unit masih dalam masa pinjaman"
                : `Hapus ${hapus.nama}?`}
            </div>
            <p className="mt-2 text-sm leading-6 text-slate-400">
              {hapus.status === "Dipinjam"
                ? "Tindakan ini diblokir agar riwayat pinjaman tetap aman. Unit hanya dapat dihapus setelah dikembalikan."
                : "Data unit akan dihapus permanen dari inventaris. Tindakan ini tidak dapat dibatalkan."}
            </p>
            <div className="mt-6 flex justify-end gap-3">
              <Tombol varian="sekunder" onClick={() => setHapus(null)}>
                Batal
              </Tombol>
              <Tombol varian="bahaya" onClick={hapusUnit}>
                {hapus.status === "Dipinjam" ? "Mengerti" : "Ya, hapus unit"}
              </Tombol>
            </div>
          </div>
        </Modal>
      )}
    </>
  )
}

function FormUnit({
  mode,
  unit,
  units,
  onClose,
  onSave,
}: {
  mode: "tambah" | "ubah"
  unit: Unit | null
  units: Unit[]
  onClose: () => void
  onSave: (u: Unit) => void
}) {
  const [form, setForm] = useState({
    nama: unit?.nama || "",
    kode: unit?.kode || "SD-BG-001",
    tipe: unit?.tipe || "Permainan Papan",
    kondisi: unit?.kondisi || "Sangat Baik",
    kategori: unit?.kategori || "Strategi",
    deskripsi: unit?.deskripsi || "",
  })
  const duplikat = units.some(
    (u) =>
      u.kode.toLowerCase() === form.kode.toLowerCase() && u.id !== unit?.id,
  )
  const submit = (e: FormEvent) => {
    e.preventDefault()
    if (duplikat || !form.nama || !form.kode) return
    onSave({
      ...form,
      tipe: form.tipe as Unit["tipe"],
      id: unit?.id || Date.now(),
      status: unit?.status || "Tersedia",
      warna: unit?.warna || "from-cyan-500/30 to-blue-900/20",
      ikon: unit?.ikon || "✦",
    })
  }
  return (
    <Modal
      judul={mode === "tambah" ? "Tambah unit baru" : "Ubah unit"}
      onClose={onClose}
      lebar="max-w-2xl"
    >
      <form onSubmit={submit} className="space-y-5 p-6">
        {duplikat && (
          <div className="flex gap-3 rounded-xl border border-rose-400/25 bg-rose-400/10 p-4 text-sm text-rose-200">
            <Ikon nama="alert" className="h-5 w-5 shrink-0" />
            <div>
              <div className="font-semibold">Kode unit sudah digunakan</div>
              <div className="mt-1 text-xs text-rose-300/70">
                Masukkan kode unik lain agar data dapat disimpan.
              </div>
            </div>
          </div>
        )}
        <div className="grid gap-5 sm:grid-cols-2">
          <Masukan
            label="Nama unit"
            value={form.nama}
            onChange={(e) => setForm({ ...form, nama: e.target.value })}
            error={!form.nama ? "Nama unit wajib diisi" : undefined}
          />
          <Masukan
            label="Kode unit"
            value={form.kode}
            onChange={(e) =>
              setForm({ ...form, kode: e.target.value.toUpperCase() })
            }
            error={duplikat ? "Kode unit ini sudah ada" : undefined}
          />
          <Pilihan
            label="Tipe"
            value={form.tipe}
            options={["Permainan Papan", "Konsol"]}
            onChange={(v) => setForm({ ...form, tipe: v })}
          />
          <Pilihan
            label="Kondisi"
            value={form.kondisi}
            options={["Sangat Baik", "Baik", "Cukup Baik", "Perlu Perbaikan"]}
            onChange={(v) => setForm({ ...form, kondisi: v })}
          />
          <Pilihan
            label="Kategori"
            value={form.kategori}
            options={["Strategi", "Keluarga", "Portabel", "Konsol Rumah"]}
            onChange={(v) => setForm({ ...form, kategori: v })}
          />
        </div>
        <label className="block space-y-2">
          <span className="text-sm font-medium text-slate-300">Deskripsi</span>
          <textarea
            value={form.deskripsi}
            onChange={(e) => setForm({ ...form, deskripsi: e.target.value })}
            rows={3}
            className="w-full resize-none rounded-xl border border-slate-700 bg-slate-950/60 px-4 py-3 text-sm outline-none focus:border-cyan-300/60"
            placeholder="Tuliskan deskripsi singkat unit..."
          />
        </label>
        <div className="flex justify-end gap-3 border-t border-slate-800 pt-5">
          <Tombol varian="sekunder" onClick={onClose}>
            Batal
          </Tombol>
          <Tombol type="submit">
            {mode === "tambah" ? "Simpan Unit" : "Simpan Perubahan"}
          </Tombol>
        </div>
      </form>
    </Modal>
  )
}

function Pilihan({
  label,
  value,
  options,
  onChange,
}: {
  label: string
  value: string
  options: string[]
  onChange: (v: string) => void
}) {
  return (
    <label className="block space-y-2">
      <span className="text-sm font-medium text-slate-300">{label}</span>
      <select
        value={value}
        onChange={(e) => onChange(e.target.value)}
        className="w-full rounded-xl border border-slate-700 bg-slate-950/60 px-4 py-3 text-sm text-white outline-none focus:border-cyan-300/60"
      >
        {options.map((o) => (
          <option key={o}>{o}</option>
        ))}
      </select>
    </label>
  )
}

function Profil() {
  const [form, setForm] = useState({
    telepon: "812",
    pangkat: "",
    modul: "",
    alamat: "",
  })
  const [foto, setFoto] = useState("")
  const [simpan, setSimpan] = useState(false)
  const teleponSalah = !/^\d{9,15}$/.test(form.telepon)
  return (
    <>
      <JudulHalaman
        judul="Profil astronot"
        deskripsi="Pastikan informasi identitas dan penempatanmu selalu akurat."
      />
      {simpan && (
        <div className="mb-5 flex items-center gap-2 rounded-xl border border-emerald-400/20 bg-emerald-400/10 p-4 text-sm text-emerald-200">
          <Ikon nama="check" className="h-5 w-5" />
          Profil berhasil diperbarui.
        </div>
      )}
      <div className="grid gap-6 lg:grid-cols-[280px_1fr]">
        <Kartu className="h-fit p-6 text-center">
          <div className="mx-auto flex h-28 w-28 items-center justify-center overflow-hidden rounded-2xl border border-slate-700 bg-gradient-to-br from-violet-500/20 to-cyan-500/20 text-3xl font-bold text-white">
            {foto ? (
              <img
                src={foto}
                alt="Foto profil astronot"
                className="h-full w-full object-cover"
              />
            ) : (
              "NR"
            )}
          </div>
          <div className="mt-4 font-bold text-white">Nara Rasyid</div>
          <div className="mt-1 text-xs text-slate-500">
            ID Astronot · AST-2048
          </div>
          <label className="mt-5 inline-flex min-h-10 cursor-pointer items-center justify-center gap-2 rounded-xl border border-slate-700 bg-slate-900 px-4 text-sm font-semibold text-slate-200 transition hover:border-cyan-300/50 hover:text-cyan-200 active:bg-slate-800">
            <Ikon nama="user" className="h-4 w-4" />
            Unggah Foto
            <input
              type="file"
              accept="image/*"
              className="hidden"
              onChange={(e) => {
                const file = e.target.files?.[0]
                if (file) setFoto(URL.createObjectURL(file))
              }}
            />
          </label>
          <p className="mt-3 text-[10px] leading-4 text-slate-600">
            Format JPG atau PNG, maksimal 2 MB.
          </p>
        </Kartu>
        <Kartu className="p-6 sm:p-8">
          <div className="mb-6 border-b border-slate-800 pb-5">
            <div className="font-bold text-white">Informasi pribadi</div>
            <p className="mt-1 text-xs text-slate-500">
              Kolom bertanda wajib harus dilengkapi.
            </p>
          </div>
          <div className="grid gap-5 sm:grid-cols-2">
            <Masukan
              label="Nomor Telepon"
              value={form.telepon}
              onChange={(e) => setForm({ ...form, telepon: e.target.value })}
              error={
                teleponSalah
                  ? "Nomor telepon harus terdiri dari 9–15 digit"
                  : undefined
              }
              placeholder="Contoh: 81234567890"
            />
            <Masukan
              label="Pangkat/Jabatan"
              value={form.pangkat}
              onChange={(e) => setForm({ ...form, pangkat: e.target.value })}
              error={
                !form.pangkat ? "Pangkat atau jabatan wajib diisi" : undefined
              }
              placeholder="Contoh: Astronot Senior"
            />
            <Pilihan
              label="Modul Stasiun"
              value={form.modul}
              options={[
                "",
                "Modul Komando",
                "Modul Riset",
                "Modul Navigasi",
                "Modul Medis",
              ]}
              onChange={(v) => setForm({ ...form, modul: v })}
            />
            <label className="block space-y-2 sm:col-span-2">
              <span className="text-sm font-medium text-slate-300">Alamat</span>
              <textarea
                value={form.alamat}
                onChange={(e) => setForm({ ...form, alamat: e.target.value })}
                rows={4}
                placeholder="Masukkan alamat domisili di Bumi..."
                className={`w-full resize-none rounded-xl border bg-slate-950/60 px-4 py-3 text-sm outline-none focus:border-cyan-300/60 ${
                  !form.alamat ? "border-rose-400/70" : "border-slate-700"
                }`}
              />
              {!form.alamat && (
                <span className="flex items-center gap-1.5 text-xs text-rose-300">
                  <Ikon nama="alert" className="h-3.5 w-3.5" />
                  Alamat wajib diisi
                </span>
              )}
            </label>
          </div>
          <div className="mt-7 flex justify-end">
            <Tombol
              onClick={() => {
                if (!teleponSalah && form.pangkat && form.modul && form.alamat)
                  setSimpan(true)
              }}
            >
              Simpan Perubahan
            </Tombol>
          </div>
        </Kartu>
      </div>
    </>
  )
}

function Kategori() {
  const [tambah, setTambah] = useState(false)
  const [nama, setNama] = useState("")
  const [items, setItems] = useState([
    ["Strategi", 18, "Permainan dengan taktik dan perencanaan"],
    ["Keluarga", 12, "Permainan ringan untuk seluruh kru"],
    ["Konsol Rumah", 10, "Konsol untuk modul rekreasi"],
    ["Portabel", 8, "Perangkat yang mudah dibawa"],
  ])
  return (
    <>
      <JudulHalaman
        judul="Kategori unit"
        deskripsi="Atur pengelompokan koleksi agar unit lebih mudah ditemukan."
        aksi={
          <Tombol ikon="plus" onClick={() => setTambah(true)}>
            Tambah Kategori
          </Tombol>
        }
      />
      <div className="grid gap-4 sm:grid-cols-2">
        {items.map((i) => (
          <Kartu
            key={i[0]}
            className="group p-5 transition hover:border-cyan-300/20"
          >
            <div className="flex items-start justify-between">
              <div className="flex h-11 w-11 items-center justify-center rounded-xl bg-violet-400/10 text-violet-300">
                <Ikon nama="tag" />
              </div>
              <Badge>{i[1]} unit</Badge>
            </div>
            <div className="mt-5 font-bold text-white">{i[0]}</div>
            <p className="mt-2 text-sm text-slate-500">{i[2]}</p>
          </Kartu>
        ))}
      </div>
      {tambah && (
        <Modal judul="Tambah kategori" onClose={() => setTambah(false)}>
          <div className="space-y-5 p-6">
            <Masukan
              label="Nama kategori"
              value={nama}
              onChange={(e) => setNama(e.target.value)}
              error={!nama ? "Nama kategori wajib diisi" : undefined}
            />
            <Masukan
              label="Keterangan"
              placeholder="Jelaskan penggunaan kategori ini..."
            />
            <div className="flex justify-end gap-3">
              <Tombol varian="sekunder" onClick={() => setTambah(false)}>
                Batal
              </Tombol>
              <Tombol
                onClick={() => {
                  if (nama) {
                    setItems([
                      ...items,
                      [nama, 0, "Kategori baru koleksi StarDeck"],
                    ])
                    setTambah(false)
                    setNama("")
                  }
                }}
              >
                Simpan Kategori
              </Tombol>
            </div>
          </div>
        </Modal>
      )}
    </>
  )
}

function DataAstronot() {
  const [detail, setDetail] = useState<string | null>(null)
  const data = [
    ["Nara Rasyid", "AST-2048", "Astronot Senior", "1 pinjaman"],
    ["Bima Adinata", "AST-1820", "Teknisi Modul", "0 pinjaman"],
    ["Sari Amanda", "AST-2311", "Peneliti Utama", "2 pinjaman"],
    ["Dara Rahman", "AST-1907", "Petugas Medis", "0 pinjaman"],
  ]
  return (
    <>
      <JudulHalaman
        judul="Data astronot"
        deskripsi="Pantau keanggotaan dan aktivitas peminjaman seluruh kru stasiun."
      />
      <Kartu className="overflow-hidden">
        <div className="divide-y divide-slate-800">
          {data.map((a, i) => (
            <button
              key={a[1]}
              onClick={() => setDetail(a[0])}
              className="flex w-full items-center gap-4 p-5 text-left transition hover:bg-white/[.025]"
            >
              <div className="flex h-11 w-11 items-center justify-center rounded-full bg-slate-800 text-xs font-bold text-cyan-300">
                {a[0]
                  .split(" ")
                  .map((n) => n[0])
                  .join("")}
              </div>
              <div className="flex-1">
                <div className="text-sm font-semibold text-white">{a[0]}</div>
                <div className="mt-1 text-xs text-slate-500">
                  {a[1]} · {a[2]}
                </div>
              </div>
              <Badge jenis={i === 2 ? "peringatan" : "netral"}>{a[3]}</Badge>
              <Ikon nama="chevron" className="h-4 w-4 text-slate-600" />
            </button>
          ))}
        </div>
      </Kartu>
      {detail && (
        <Modal judul="Rincian astronot" onClose={() => setDetail(null)}>
          <div className="p-6 text-center">
            <div className="mx-auto flex h-20 w-20 items-center justify-center rounded-2xl bg-cyan-300/10 text-xl font-bold text-cyan-300">
              {detail
                .split(" ")
                .map((n) => n[0])
                .join("")}
            </div>
            <div className="mt-4 text-lg font-bold text-white">{detail}</div>
            <div className="mt-1 text-xs text-slate-500">
              Anggota aktif StarDeck
            </div>
            <div className="mt-6 grid grid-cols-2 gap-3">
              <div className="rounded-xl bg-slate-950/50 p-4">
                <div className="text-xl font-bold text-white">7</div>
                <div className="text-xs text-slate-500">Total pinjaman</div>
              </div>
              <div className="rounded-xl bg-slate-950/50 p-4">
                <div className="text-xl font-bold text-emerald-300">Aktif</div>
                <div className="text-xs text-slate-500">Status anggota</div>
              </div>
            </div>
            <Tombol className="mt-5 w-full" onClick={() => setDetail(null)}>
              Tutup rincian
            </Tombol>
          </div>
        </Modal>
      )}
    </>
  )
}

function Pinjaman() {
  const [kembali, setKembali] = useState(false)
  return (
    <>
      <JudulHalaman
        judul="Pinjaman saya"
        deskripsi="Pantau unit yang sedang kamu gunakan dan riwayat pengembalian."
      />
      <div className="grid gap-6 lg:grid-cols-[1fr_.55fr]">
        <Kartu className="p-6">
          <div className="mb-5 flex items-center justify-between">
            <div className="font-bold text-white">Sedang dipinjam</div>
            <Badge jenis="peringatan">1 unit</Badge>
          </div>
          <div className="flex flex-col gap-4 rounded-xl border border-slate-800 bg-slate-950/40 p-5 sm:flex-row sm:items-center">
            <div className="flex h-16 w-16 items-center justify-center rounded-xl bg-orange-400/10 text-3xl text-orange-300">
              ◈
            </div>
            <div className="flex-1">
              <div className="font-semibold text-white">Terraformasi Mars</div>
              <div className="mt-1 text-xs text-slate-500">
                Dipinjam 11 Juni 2025
              </div>
              <div className="mt-2 text-xs font-medium text-amber-300">
                Jatuh tempo 18 Juni 2025
              </div>
            </div>
            <Tombol varian="sekunder" onClick={() => setKembali(true)}>
              Ajukan Pengembalian
            </Tombol>
          </div>
        </Kartu>
        <Kartu className="p-6">
          <div className="font-bold text-white">Ringkasan aturan</div>
          <div className="mt-5 space-y-4 text-sm text-slate-400">
            <div className="flex gap-3">
              <Ikon nama="check" className="h-5 w-5 text-cyan-300" />
              <span>Maksimal 2 unit sekaligus</span>
            </div>
            <div className="flex gap-3">
              <Ikon nama="clock" className="h-5 w-5 text-cyan-300" />
              <span>Masa pinjaman 7 hari</span>
            </div>
            <div className="flex gap-3">
              <Ikon nama="alert" className="h-5 w-5 text-cyan-300" />
              <span>Denda dihitung per hari</span>
            </div>
          </div>
        </Kartu>
      </div>
      {kembali && (
        <Modal judul="Ajukan pengembalian" onClose={() => setKembali(false)}>
          <div className="p-6">
            <p className="text-sm leading-6 text-slate-400">
              Serahkan unit beserta seluruh komponennya ke petugas StarDeck.
              Setelah diperiksa, status pinjaman akan diperbarui.
            </p>
            <Pilihan
              label="Lokasi penyerahan"
              value="Loket Modul Rekreasi"
              options={["Loket Modul Rekreasi", "Pos Komando Utama"]}
              onChange={() => {}}
            />
            <Tombol className="mt-6 w-full" onClick={() => setKembali(false)}>
              Konfirmasi Pengembalian
            </Tombol>
          </div>
        </Modal>
      )}
    </>
  )
}

function KategoriAdmin({ units }: { units: Unit[] }) {
  const [items, setItems] = useState([
    { nama: "Strategi", keterangan: "Taktik dan perencanaan tingkat lanjut" },
    { nama: "Keluarga", keterangan: "Permainan ringan untuk seluruh kru" },
    { nama: "Konsol Rumah", keterangan: "Konsol tetap di modul rekreasi" },
    { nama: "Portabel", keterangan: "Perangkat ringkas yang mudah dibawa" },
  ])
  const [tambah, setTambah] = useState(false)
  const [hapus, setHapus] = useState<string | null>(null)
  const [nama, setNama] = useState("")
  const [keterangan, setKeterangan] = useState("")
  const terpakai = hapus ? units.filter((u) => u.kategori === hapus).length : 0
  const duplikat = items.some(
    (i) => i.nama.toLowerCase() === nama.trim().toLowerCase(),
  )
  return (
    <>
      <JudulHalaman
        judul="Kelola kategori"
        deskripsi="Jaga nama kategori tetap unik dan pantau keterkaitannya dengan inventaris."
        aksi={
          <Tombol ikon="plus" onClick={() => setTambah(true)}>
            Tambah Kategori
          </Tombol>
        }
      />
      <Kartu className="overflow-hidden">
        <div className="overflow-x-auto">
          <table className="w-full min-w-[700px] text-left">
            <thead className="bg-slate-950/40 text-[10px] uppercase tracking-wider text-slate-500">
              <tr>
                <th className="px-6 py-4">Nama Kategori</th>
                <th className="px-6 py-4">Keterangan</th>
                <th className="px-6 py-4">Unit Terhubung</th>
                <th className="px-6 py-4 text-right">Tindakan</th>
              </tr>
            </thead>
            <tbody className="divide-y divide-slate-800">
              {items.map((item) => {
                const jumlah = units.filter(
                  (u) => u.kategori === item.nama,
                ).length
                return (
                  <tr
                    key={item.nama}
                    className="transition hover:bg-white/[.025]"
                  >
                    <td className="px-6 py-5">
                      <div className="flex items-center gap-3 font-semibold text-white">
                        <span className="flex h-9 w-9 items-center justify-center rounded-lg bg-violet-400/10 text-violet-300">
                          <Ikon nama="tag" className="h-4 w-4" />
                        </span>
                        {item.nama}
                      </div>
                    </td>
                    <td className="px-6 py-5 text-sm text-slate-400">
                      {item.keterangan}
                    </td>
                    <td className="px-6 py-5">
                      <Badge jenis={jumlah ? "ungu" : "netral"}>
                        {jumlah} unit
                      </Badge>
                    </td>
                    <td className="px-6 py-5">
                      <div className="flex justify-end gap-2">
                        <Tombol
                          varian="sekunder"
                          ikon="edit"
                          onClick={() => {
                            setNama(item.nama)
                            setKeterangan(item.keterangan)
                            setTambah(true)
                          }}
                        >
                          Ubah
                        </Tombol>
                        <Tombol
                          varian="bahaya"
                          ikon="trash"
                          onClick={() => setHapus(item.nama)}
                        >
                          Hapus
                        </Tombol>
                      </div>
                    </td>
                  </tr>
                )
              })}
            </tbody>
          </table>
        </div>
      </Kartu>
      {tambah && (
        <Modal
          judul={
            items.some((i) => i.nama === nama)
              ? "Ubah kategori"
              : "Tambah kategori"
          }
          onClose={() => {
            setTambah(false)
            setNama("")
            setKeterangan("")
          }}
        >
          <div className="space-y-5 p-6">
            {duplikat && !items.some((i) => i.nama === nama) && (
              <div className="flex gap-3 rounded-xl border border-rose-400/25 bg-rose-400/10 p-4 text-sm text-rose-200">
                <Ikon nama="alert" className="h-5 w-5 shrink-0" />
                <div>
                  <div className="font-semibold">
                    Nama kategori sudah digunakan
                  </div>
                  <div className="mt-1 text-xs text-rose-300/70">
                    Setiap kategori harus memiliki nama yang unik.
                  </div>
                </div>
              </div>
            )}
            <Masukan
              label="Nama kategori"
              value={nama}
              onChange={(e) => setNama(e.target.value)}
              error={!nama ? "Nama kategori wajib diisi" : undefined}
            />
            <Masukan
              label="Keterangan"
              value={keterangan}
              onChange={(e) => setKeterangan(e.target.value)}
              placeholder="Jelaskan penggunaan kategori..."
            />
            <div className="flex justify-end gap-3">
              <Tombol
                varian="sekunder"
                onClick={() => {
                  setTambah(false)
                  setNama("")
                  setKeterangan("")
                }}
              >
                Batal
              </Tombol>
              <Tombol
                onClick={() => {
                  const sudahAda = items.find((i) => i.nama === nama)
                  if (!nama || (duplikat && !sudahAda)) return
                  if (sudahAda)
                    setItems(
                      items.map((i) =>
                        i.nama === nama ? { nama, keterangan } : i,
                      ),
                    )
                  else
                    setItems([
                      ...items,
                      {
                        nama,
                        keterangan: keterangan || "Kategori koleksi StarDeck",
                      },
                    ])
                  setTambah(false)
                  setNama("")
                  setKeterangan("")
                }}
              >
                Simpan Kategori
              </Tombol>
            </div>
          </div>
        </Modal>
      )}
      {hapus && (
        <Modal judul="Hapus kategori" onClose={() => setHapus(null)}>
          <div className="p-6">
            {terpakai > 0 ? (
              <>
                <div className="flex gap-3 rounded-xl border border-rose-400/25 bg-rose-400/10 p-4 text-rose-200">
                  <Ikon nama="alert" className="h-6 w-6 shrink-0" />
                  <div>
                    <div className="font-semibold">
                      Penghapusan kategori diblokir
                    </div>
                    <p className="mt-1 text-sm leading-5 text-rose-200/70">
                      Kategori “{hapus}” masih terhubung ke {terpakai} unit.
                      Pindahkan semua unit ke kategori lain terlebih dahulu.
                    </p>
                  </div>
                </div>
                <Tombol className="mt-6 w-full" onClick={() => setHapus(null)}>
                  Mengerti
                </Tombol>
              </>
            ) : (
              <>
                <p className="text-sm leading-6 text-slate-400">
                  Kategori “{hapus}” tidak terhubung ke unit mana pun dan dapat
                  dihapus dengan aman.
                </p>
                <div className="mt-6 flex justify-end gap-3">
                  <Tombol varian="sekunder" onClick={() => setHapus(null)}>
                    Batal
                  </Tombol>
                  <Tombol
                    varian="bahaya"
                    onClick={() => {
                      setItems(items.filter((i) => i.nama !== hapus))
                      setHapus(null)
                    }}
                  >
                    Ya, Hapus Kategori
                  </Tombol>
                </div>
              </>
            )}
          </div>
        </Modal>
      )}
    </>
  )
}

function AnggotaAdmin({
  anggota,
  setAnggota,
  pinjaman,
}: {
  anggota: Anggota[]
  setAnggota: (a: Anggota[]) => void
  pinjaman: PinjamanData[]
}) {
  const [hapus, setHapus] = useState<Anggota | null>(null)
  const [detail, setDetail] = useState<Anggota | null>(null)
  const [tambah, setTambah] = useState(false)
  const [nama, setNama] = useState("")
  const pinjamanAktif = (id: string) =>
    pinjaman.filter((p) => p.anggotaId === id && p.status === "Aktif").length
  return (
    <>
      <JudulHalaman
        judul="Kelola astronot"
        deskripsi="Kelola akun anggota dan pastikan kewajiban pinjaman selesai sebelum akun dihapus."
        aksi={
          <Tombol ikon="plus" onClick={() => setTambah(true)}>
            Tambah Astronot
          </Tombol>
        }
      />
      <Kartu className="overflow-hidden">
        <div className="overflow-x-auto">
          <table className="w-full min-w-[850px] text-left">
            <thead className="bg-slate-950/40 text-[10px] uppercase tracking-wider text-slate-500">
              <tr>
                <th className="px-6 py-4">Astronot</th>
                <th className="px-6 py-4">Jabatan</th>
                <th className="px-6 py-4">Modul</th>
                <th className="px-6 py-4">Pinjaman Aktif</th>
                <th className="px-6 py-4">Status</th>
                <th className="px-6 py-4 text-right">Tindakan</th>
              </tr>
            </thead>
            <tbody className="divide-y divide-slate-800">
              {anggota.map((a) => (
                <tr key={a.id} className="transition hover:bg-white/[.025]">
                  <td className="px-6 py-5">
                    <button
                      onClick={() => setDetail(a)}
                      className="flex items-center gap-3 text-left"
                    >
                      <span className="flex h-10 w-10 items-center justify-center rounded-full bg-cyan-300/10 text-xs font-bold text-cyan-300">
                        {a.nama
                          .split(" ")
                          .map((n) => n[0])
                          .join("")}
                      </span>
                      <span>
                        <span className="block text-sm font-semibold text-white">
                          {a.nama}
                        </span>
                        <span className="mt-1 block text-xs text-slate-500">
                          {a.id}
                        </span>
                      </span>
                    </button>
                  </td>
                  <td className="px-6 py-5 text-sm text-slate-400">
                    {a.jabatan}
                  </td>
                  <td className="px-6 py-5 text-sm text-slate-400">
                    {a.modul}
                  </td>
                  <td className="px-6 py-5">
                    <Badge
                      jenis={pinjamanAktif(a.id) ? "peringatan" : "netral"}
                    >
                      {pinjamanAktif(a.id)} unit
                    </Badge>
                  </td>
                  <td className="px-6 py-5">
                    <Badge jenis="sukses">{a.status}</Badge>
                  </td>
                  <td className="px-6 py-5">
                    <div className="flex justify-end gap-2">
                      <Tombol varian="sekunder" onClick={() => setDetail(a)}>
                        Rincian
                      </Tombol>
                      <Tombol
                        varian="bahaya"
                        ikon="trash"
                        onClick={() => setHapus(a)}
                      >
                        Hapus
                      </Tombol>
                    </div>
                  </td>
                </tr>
              ))}
            </tbody>
          </table>
        </div>
      </Kartu>
      {tambah && (
        <Modal judul="Tambah akun astronot" onClose={() => setTambah(false)}>
          <div className="space-y-5 p-6">
            <Masukan
              label="Nama lengkap"
              value={nama}
              onChange={(e) => setNama(e.target.value)}
              error={!nama ? "Nama lengkap wajib diisi" : undefined}
            />
            <Masukan label="Alamat email" placeholder="nama@stardeck.id" />
            <Pilihan
              label="Modul penempatan"
              value="Modul Riset"
              options={[
                "Modul Riset",
                "Modul Navigasi",
                "Modul Medis",
                "Modul Rekreasi",
              ]}
              onChange={() => {}}
            />
            <div className="flex justify-end gap-3">
              <Tombol varian="sekunder" onClick={() => setTambah(false)}>
                Batal
              </Tombol>
              <Tombol
                onClick={() => {
                  if (!nama) return
                  setAnggota([
                    ...anggota,
                    {
                      id: `AST-${2100 + anggota.length}`,
                      nama,
                      jabatan: "Astronot Baru",
                      modul: "Modul Riset",
                      status: "Aktif",
                    },
                  ])
                  setTambah(false)
                  setNama("")
                }}
              >
                Simpan Akun
              </Tombol>
            </div>
          </div>
        </Modal>
      )}
      {detail && (
        <Modal judul="Rincian astronot" onClose={() => setDetail(null)}>
          <div className="p-6 text-center">
            <div className="mx-auto flex h-20 w-20 items-center justify-center rounded-2xl bg-cyan-300/10 text-xl font-bold text-cyan-300">
              {detail.nama
                .split(" ")
                .map((n) => n[0])
                .join("")}
            </div>
            <div className="mt-4 text-lg font-bold text-white">
              {detail.nama}
            </div>
            <div className="mt-1 text-xs text-slate-500">
              {detail.id} · {detail.jabatan}
            </div>
            <div className="mt-6 grid grid-cols-2 gap-3">
              <div className="rounded-xl bg-slate-950/50 p-4">
                <div className="text-xl font-bold text-white">
                  {pinjamanAktif(detail.id)}
                </div>
                <div className="text-xs text-slate-500">Pinjaman aktif</div>
              </div>
              <div className="rounded-xl bg-slate-950/50 p-4">
                <div className="text-sm font-bold text-emerald-300">
                  {detail.status}
                </div>
                <div className="text-xs text-slate-500">Status akun</div>
              </div>
            </div>
            <Tombol className="mt-5 w-full" onClick={() => setDetail(null)}>
              Tutup Rincian
            </Tombol>
          </div>
        </Modal>
      )}
      {hapus && (
        <Modal judul="Hapus akun astronot" onClose={() => setHapus(null)}>
          <div className="p-6">
            {pinjamanAktif(hapus.id) > 0 ? (
              <>
                <div className="flex gap-3 rounded-xl border border-rose-400/25 bg-rose-400/10 p-4 text-rose-200">
                  <Ikon nama="alert" className="h-6 w-6 shrink-0" />
                  <div>
                    <div className="font-semibold">
                      Penghapusan anggota diblokir
                    </div>
                    <p className="mt-1 text-sm leading-5 text-rose-200/70">
                      {hapus.nama} masih memiliki {pinjamanAktif(hapus.id)}{" "}
                      pinjaman aktif. Proses pengembalian seluruh unit sebelum
                      menghapus akun.
                    </p>
                  </div>
                </div>
                <Tombol className="mt-6 w-full" onClick={() => setHapus(null)}>
                  Lihat dan selesaikan nanti
                </Tombol>
              </>
            ) : (
              <>
                <p className="text-sm leading-6 text-slate-400">
                  Akun {hapus.nama} akan dihapus dari daftar anggota StarDeck.
                </p>
                <div className="mt-6 flex justify-end gap-3">
                  <Tombol varian="sekunder" onClick={() => setHapus(null)}>
                    Batal
                  </Tombol>
                  <Tombol
                    varian="bahaya"
                    onClick={() => {
                      setAnggota(anggota.filter((a) => a.id !== hapus.id))
                      setHapus(null)
                    }}
                  >
                    Ya, Hapus Akun
                  </Tombol>
                </div>
              </>
            )}
          </div>
        </Modal>
      )}
    </>
  )
}

function FormPinjamanBaru({
  anggota,
  units,
  pinjaman,
  onClose,
  onSave,
}: {
  anggota: Anggota[]
  units: Unit[]
  pinjaman: PinjamanData[]
  onClose: () => void
  onSave: (anggota: Anggota, unit: Unit) => void
}) {
  const [anggotaId, setAnggotaId] = useState("AST-2311")
  const [unitKode, setUnitKode] = useState("SD-CS-001")
  const anggotaDipilih = anggota.find((a) => a.id === anggotaId)!
  const unitDipilih = units.find((u) => u.kode === unitKode)!
  const jumlahAktif = pinjaman.filter(
    (p) => p.anggotaId === anggotaId && p.status === "Aktif",
  ).length
  const batas = jumlahAktif >= 2
  const tidakTersedia = unitDipilih?.status === "Dipinjam"
  return (
    <Modal judul="Tambah pinjaman baru" onClose={onClose} lebar="max-w-2xl">
      <div className="space-y-5 p-6">
        {(batas || tidakTersedia) && (
          <div className="space-y-2">
            {batas && (
              <div className="flex gap-3 rounded-xl border border-rose-400/25 bg-rose-400/10 p-4 text-sm text-rose-200">
                <Ikon nama="alert" className="h-5 w-5 shrink-0" />
                <div>
                  <div className="font-semibold">Batas pinjaman tercapai</div>
                  <div className="mt-1 text-xs text-rose-300/70">
                    {anggotaDipilih.nama} sudah memiliki 2 pinjaman aktif.
                  </div>
                </div>
              </div>
            )}
            {tidakTersedia && (
              <div className="flex gap-3 rounded-xl border border-amber-400/25 bg-amber-400/10 p-4 text-sm text-amber-100">
                <Ikon nama="alert" className="h-5 w-5 shrink-0" />
                <div>
                  <div className="font-semibold">Unit tidak tersedia</div>
                  <div className="mt-1 text-xs text-amber-200/70">
                    {unitDipilih.nama} sudah berstatus Dipinjam.
                  </div>
                </div>
              </div>
            )}
          </div>
        )}
        <Pilihan
          label="Pilih astronot"
          value={anggotaId}
          options={anggota.map((a) => a.id)}
          onChange={setAnggotaId}
        />
        <div className="rounded-xl border border-slate-800 bg-slate-950/40 p-4">
          <div className="text-sm font-semibold text-white">
            {anggotaDipilih.nama}
          </div>
          <div className="mt-1 text-xs text-slate-500">
            {anggotaDipilih.jabatan} · {jumlahAktif} dari 2 pinjaman aktif
          </div>
        </div>
        <Pilihan
          label="Pilih unit"
          value={unitKode}
          options={units.map((u) => u.kode)}
          onChange={setUnitKode}
        />
        <div className="rounded-xl border border-slate-800 bg-slate-950/40 p-4">
          <div className="flex items-center justify-between">
            <div>
              <div className="text-sm font-semibold text-white">
                {unitDipilih.nama}
              </div>
              <div className="mt-1 text-xs text-slate-500">
                {unitDipilih.kode} · {unitDipilih.tipe}
              </div>
            </div>
            <Badge
              jenis={
                unitDipilih.status === "Tersedia" ? "sukses" : "peringatan"
              }
            >
              {unitDipilih.status}
            </Badge>
          </div>
        </div>
        <div className="grid grid-cols-2 gap-3">
          <div className="rounded-xl bg-slate-950/50 p-4">
            <div className="text-[10px] uppercase tracking-wider text-slate-600">
              Tanggal Pinjam
            </div>
            <div className="mt-2 text-sm font-semibold text-white">
              20 Jun 2025
            </div>
          </div>
          <div className="rounded-xl bg-slate-950/50 p-4">
            <div className="text-[10px] uppercase tracking-wider text-slate-600">
              Jatuh Tempo
            </div>
            <div className="mt-2 text-sm font-semibold text-white">
              25 Jun 2025
            </div>
            <div className="mt-1 text-[10px] text-slate-600">
              Tanggal pinjam + 5 hari
            </div>
          </div>
        </div>
        <div className="flex justify-end gap-3 border-t border-slate-800 pt-5">
          <Tombol varian="sekunder" onClick={onClose}>
            Batal
          </Tombol>
          <Tombol
            disabled={batas || tidakTersedia}
            onClick={() => onSave(anggotaDipilih, unitDipilih)}
          >
            Simpan Pinjaman
          </Tombol>
        </div>
      </div>
    </Modal>
  )
}

function ModalPengembalian({
  data,
  onClose,
  onProcess,
}: {
  data: PinjamanData
  onClose: () => void
  onProcess: (p: PinjamanData) => void
}) {
  const terlambat = data.id === 1 ? 1 : data.id === 2 ? 3 : 0
  const denda = terlambat * 5000
  const selesai = data.status === "Dikembalikan"
  return (
    <Modal
      judul="Proses pengembalian & denda"
      onClose={onClose}
      lebar="max-w-2xl"
    >
      <div className="space-y-5 p-6">
        {selesai && (
          <div className="flex gap-3 rounded-xl border border-rose-400/25 bg-rose-400/10 p-4 text-rose-200">
            <Ikon nama="alert" className="h-6 w-6 shrink-0" />
            <div>
              <div className="font-semibold">Pemrosesan ganda diblokir</div>
              <p className="mt-1 text-sm text-rose-200/70">
                Pinjaman ini sudah berstatus Dikembalikan pada{" "}
                {data.tanggalKembali}. Tidak ada tindakan lanjutan yang dapat
                dilakukan.
              </p>
            </div>
          </div>
        )}
        <div className="flex items-center gap-4 rounded-xl border border-slate-800 bg-slate-950/40 p-4">
          <div className="flex h-12 w-12 items-center justify-center rounded-xl bg-cyan-300/10 text-cyan-300">
            <Ikon nama="box" />
          </div>
          <div className="flex-1">
            <div className="font-semibold text-white">{data.unitNama}</div>
            <div className="mt-1 text-xs text-slate-500">
              {data.unitKode} · {data.anggota}
            </div>
          </div>
          <Badge jenis={selesai ? "sukses" : "peringatan"}>{data.status}</Badge>
        </div>
        <div className="grid gap-3 sm:grid-cols-2">
          <InfoTanggal label="Tanggal Pinjam" nilai={data.tanggalPinjam} />
          <InfoTanggal label="Jatuh Tempo" nilai={data.jatuhTempo} />
          <InfoTanggal
            label="Tanggal Kembali"
            nilai={selesai ? data.tanggalKembali! : "20 Jun 2025"}
            sorot
          />
          <InfoTanggal
            label="Hari Terlambat"
            nilai={`${selesai ? data.hariTerlambat : terlambat} hari`}
            catatan="Maks. (0, kembali − jatuh tempo)"
          />
        </div>
        <div
          className={`rounded-2xl border p-5 ${
            denda > 0 || (data.denda || 0) > 0
              ? "border-rose-400/25 bg-rose-400/10"
              : "border-emerald-400/20 bg-emerald-400/10"
          }`}
        >
          <div className="text-xs uppercase tracking-wider text-slate-400">
            Total Denda
          </div>
          <div className="mt-2 text-3xl font-bold text-white">
            Rp {((selesai ? data.denda : denda) || 0).toLocaleString("id-ID")}
          </div>
          <div className="mt-1 text-xs text-slate-400">
            Rp 5.000 × {selesai ? data.hariTerlambat : terlambat} hari terlambat
          </div>
        </div>
        <div className="flex justify-end gap-3">
          <Tombol varian="sekunder" onClick={onClose}>
            {selesai ? "Tutup" : "Batal"}
          </Tombol>
          <Tombol
            disabled={selesai}
            onClick={() =>
              onProcess({
                ...data,
                status: "Dikembalikan",
                tanggalKembali: "20 Jun 2025",
                hariTerlambat: terlambat,
                denda,
              })
            }
          >
            Konfirmasi Pengembalian
          </Tombol>
        </div>
      </div>
    </Modal>
  )
}

function InfoTanggal({
  label,
  nilai,
  catatan,
  sorot = false,
}: {
  label: string
  nilai: string
  catatan?: string
  sorot?: boolean
}) {
  return (
    <div
      className={`rounded-xl border p-4 ${
        sorot
          ? "border-cyan-300/20 bg-cyan-300/5"
          : "border-slate-800 bg-slate-950/40"
      }`}
    >
      <div className="text-[10px] uppercase tracking-wider text-slate-600">
        {label}
      </div>
      <div className="mt-2 text-sm font-semibold text-white">{nilai}</div>
      {catatan && (
        <div className="mt-1 text-[10px] text-slate-600">{catatan}</div>
      )}
    </div>
  )
}

function PinjamanAktifAdmin({
  anggota,
  units,
  setUnits,
  pinjaman,
  setPinjaman,
}: {
  anggota: Anggota[]
  units: Unit[]
  setUnits: (u: Unit[]) => void
  pinjaman: PinjamanData[]
  setPinjaman: (p: PinjamanData[]) => void
}) {
  const [tambah, setTambah] = useState(false)
  const [kembali, setKembali] = useState<PinjamanData | null>(null)
  const aktif = pinjaman.filter((p) => p.status === "Aktif")
  const terlambat = (p: PinjamanData) => ["1", "2"].includes(String(p.id))
  return (
    <>
      <JudulHalaman
        judul="Pinjaman aktif"
        deskripsi="Pantau seluruh unit yang sedang digunakan dan proses pengembaliannya."
        aksi={
          <Tombol ikon="plus" onClick={() => setTambah(true)}>
            Tambah Pinjaman
          </Tombol>
        }
      />
      <div className="mb-5 grid gap-3 sm:grid-cols-3">
        <Kartu className="p-4">
          <div className="text-2xl font-bold text-white">{aktif.length}</div>
          <div className="mt-1 text-xs text-slate-500">Total aktif</div>
        </Kartu>
        <Kartu className="p-4">
          <div className="text-2xl font-bold text-rose-300">
            {aktif.filter(terlambat).length}
          </div>
          <div className="mt-1 text-xs text-slate-500">Terlambat</div>
        </Kartu>
        <Kartu className="p-4">
          <div className="text-2xl font-bold text-emerald-300">
            {aktif.filter((p) => !terlambat(p)).length}
          </div>
          <div className="mt-1 text-xs text-slate-500">Tepat waktu</div>
        </Kartu>
      </div>
      <Kartu className="overflow-hidden">
        <div className="overflow-x-auto">
          <table className="w-full min-w-[950px] text-left">
            <thead className="bg-slate-950/40 text-[10px] uppercase tracking-wider text-slate-500">
              <tr>
                <th className="px-5 py-4">Astronot</th>
                <th className="px-5 py-4">Unit</th>
                <th className="px-5 py-4">Tanggal Pinjam</th>
                <th className="px-5 py-4">Jatuh Tempo</th>
                <th className="px-5 py-4">Status</th>
                <th className="px-5 py-4 text-right">Tindakan</th>
              </tr>
            </thead>
            <tbody className="divide-y divide-slate-800">
              {aktif.map((p) => (
                <tr
                  key={p.id}
                  className={
                    terlambat(p)
                      ? "bg-rose-400/[.035]"
                      : "transition hover:bg-white/[.025]"
                  }
                >
                  <td className="px-5 py-5">
                    <div className="text-sm font-semibold text-white">
                      {p.anggota}
                    </div>
                    <div className="mt-1 text-xs text-slate-500">
                      {p.anggotaId}
                    </div>
                  </td>
                  <td className="px-5 py-5">
                    <div className="text-sm text-slate-300">{p.unitNama}</div>
                    <div className="mt-1 text-xs text-cyan-300/60">
                      {p.unitKode}
                    </div>
                  </td>
                  <td className="px-5 py-5 text-sm text-slate-400">
                    {p.tanggalPinjam}
                  </td>
                  <td
                    className={`px-5 py-5 text-sm font-medium ${
                      terlambat(p) ? "text-rose-300" : "text-slate-300"
                    }`}
                  >
                    {p.jatuhTempo}
                    <div className="mt-1 text-[10px] text-slate-600">
                      Pinjam + 5 hari
                    </div>
                  </td>
                  <td className="px-5 py-5">
                    <Badge jenis={terlambat(p) ? "peringatan" : "sukses"}>
                      {terlambat(p) ? "Terlambat" : "Aktif"}
                    </Badge>
                  </td>
                  <td className="px-5 py-5 text-right">
                    <Tombol
                      varian={terlambat(p) ? "bahaya" : "sekunder"}
                      onClick={() => setKembali(p)}
                    >
                      Proses Pengembalian
                    </Tombol>
                  </td>
                </tr>
              ))}
            </tbody>
          </table>
        </div>
      </Kartu>
      {tambah && (
        <FormPinjamanBaru
          anggota={anggota}
          units={units}
          pinjaman={pinjaman}
          onClose={() => setTambah(false)}
          onSave={(a, u) => {
            setPinjaman([
              ...pinjaman,
              {
                id: Date.now(),
                anggotaId: a.id,
                anggota: a.nama,
                unitKode: u.kode,
                unitNama: u.nama,
                tanggalPinjam: "20 Jun 2025",
                jatuhTempo: "25 Jun 2025",
                status: "Aktif",
              },
            ])
            setUnits(
              units.map((item) =>
                item.id === u.id ? { ...item, status: "Dipinjam" } : item,
              ),
            )
            setTambah(false)
          }}
        />
      )}
      {kembali && (
        <ModalPengembalian
          data={kembali}
          onClose={() => setKembali(null)}
          onProcess={(hasil) => {
            setPinjaman(pinjaman.map((p) => (p.id === hasil.id ? hasil : p)))
            setUnits(
              units.map((u) =>
                u.kode === hasil.unitKode ? { ...u, status: "Tersedia" } : u,
              ),
            )
            setKembali(null)
          }}
        />
      )}
    </>
  )
}

function PinjamanSaya({ pinjaman }: { pinjaman: PinjamanData[] }) {
  const [detail, setDetail] = useState<PinjamanData | null>(null)
  const pribadi = pinjaman.filter(
    (p) => p.anggotaId === "AST-2048" && p.status === "Aktif",
  )
  return (
    <>
      <JudulHalaman
        judul="Pinjaman saya"
        deskripsi="Daftar unit aktif, sisa waktu, dan tanggal pengembalian milikmu."
      />
      <div className="mb-5 rounded-xl border border-cyan-300/20 bg-cyan-300/10 p-4 text-sm text-cyan-100">
        <strong>{pribadi.length} dari 2 slot digunakan.</strong> Masa pinjaman
        setiap unit adalah 5 hari.
      </div>
      <Kartu className="overflow-hidden">
        <div className="overflow-x-auto">
          <table className="w-full min-w-[760px] text-left">
            <thead className="bg-slate-950/40 text-[10px] uppercase tracking-wider text-slate-500">
              <tr>
                <th className="px-6 py-4">Unit</th>
                <th className="px-6 py-4">Tanggal Pinjam</th>
                <th className="px-6 py-4">Jatuh Tempo</th>
                <th className="px-6 py-4">Sisa Waktu</th>
                <th className="px-6 py-4 text-right">Tindakan</th>
              </tr>
            </thead>
            <tbody>
              {pribadi.map((p) => (
                <tr key={p.id} className="bg-rose-400/[.035]">
                  <td className="px-6 py-5">
                    <div className="font-semibold text-white">{p.unitNama}</div>
                    <div className="mt-1 text-xs text-cyan-300/60">
                      {p.unitKode}
                    </div>
                  </td>
                  <td className="px-6 py-5 text-sm text-slate-400">
                    {p.tanggalPinjam}
                  </td>
                  <td className="px-6 py-5 text-sm font-medium text-rose-300">
                    {p.jatuhTempo}
                  </td>
                  <td className="px-6 py-5">
                    <Badge jenis="peringatan">Terlambat 1 hari</Badge>
                  </td>
                  <td className="px-6 py-5 text-right">
                    <Tombol varian="sekunder" onClick={() => setDetail(p)}>
                      Lihat Rincian
                    </Tombol>
                  </td>
                </tr>
              ))}
            </tbody>
          </table>
        </div>
      </Kartu>
      {detail && (
        <Modal judul="Rincian pinjaman saya" onClose={() => setDetail(null)}>
          <div className="p-6">
            <div className="font-bold text-white">{detail.unitNama}</div>
            <div className="mt-1 text-xs text-slate-500">{detail.unitKode}</div>
            <div className="mt-5 grid grid-cols-2 gap-3">
              <InfoTanggal
                label="Tanggal Pinjam"
                nilai={detail.tanggalPinjam}
              />
              <InfoTanggal label="Jatuh Tempo" nilai={detail.jatuhTempo} />
            </div>
            <div className="mt-4 rounded-xl border border-amber-400/20 bg-amber-400/10 p-4 text-sm text-amber-100">
              Serahkan unit ke loket Modul Rekreasi. Pengembalian akan diproses
              oleh Komandan.
            </div>
            <Tombol className="mt-5 w-full" onClick={() => setDetail(null)}>
              Tutup Rincian
            </Tombol>
          </div>
        </Modal>
      )}
    </>
  )
}

function RiwayatLaporan({ pinjaman }: { pinjaman: PinjamanData[] }) {
  const [cetak, setCetak] = useState(false)
  const [kembali, setKembali] = useState<PinjamanData | null>(null)
  const selesai = pinjaman.filter((p) => p.status === "Dikembalikan")
  const totalDenda = selesai.reduce((n, p) => n + (p.denda || 0), 0)
  return (
    <>
      <JudulHalaman
        judul="Riwayat & laporan misi"
        deskripsi="Arsip pengembalian lengkap untuk audit inventaris dan laporan operasional."
        aksi={
          <Tombol ikon="file" onClick={() => setCetak(true)}>
            Cetak / Ekspor PDF
          </Tombol>
        }
      />
      <div className="mb-5 grid gap-3 sm:grid-cols-3">
        <Kartu className="p-4">
          <div className="text-2xl font-bold text-white">{selesai.length}</div>
          <div className="mt-1 text-xs text-slate-500">Transaksi selesai</div>
        </Kartu>
        <Kartu className="p-4">
          <div className="text-2xl font-bold text-white">
            {selesai.reduce((n, p) => n + (p.hariTerlambat || 0), 0)}
          </div>
          <div className="mt-1 text-xs text-slate-500">
            Total hari terlambat
          </div>
        </Kartu>
        <Kartu className="p-4">
          <div className="text-2xl font-bold text-cyan-300">
            Rp {totalDenda.toLocaleString("id-ID")}
          </div>
          <div className="mt-1 text-xs text-slate-500">Denda terkumpul</div>
        </Kartu>
      </div>
      <Kartu className="overflow-hidden">
        <div className="overflow-x-auto">
          <table className="w-full min-w-[1050px] text-left">
            <thead className="bg-slate-950/40 text-[10px] uppercase tracking-wider text-slate-500">
              <tr>
                <th className="px-4 py-4">Astronot</th>
                <th className="px-4 py-4">Unit</th>
                <th className="px-4 py-4">Tanggal Pinjam</th>
                <th className="px-4 py-4">Jatuh Tempo</th>
                <th className="px-4 py-4">Tanggal Kembali</th>
                <th className="px-4 py-4">Terlambat</th>
                <th className="px-4 py-4">Denda</th>
                <th className="px-4 py-4 text-right">Tindakan</th>
              </tr>
            </thead>
            <tbody className="divide-y divide-slate-800">
              {selesai.map((p) => (
                <tr key={p.id} className="transition hover:bg-white/[.025]">
                  <td className="px-4 py-5 text-sm font-semibold text-white">
                    {p.anggota}
                  </td>
                  <td className="px-4 py-5">
                    <div className="text-sm text-slate-300">{p.unitNama}</div>
                    <div className="text-xs text-slate-600">{p.unitKode}</div>
                  </td>
                  <td className="px-4 py-5 text-xs text-slate-400">
                    {p.tanggalPinjam}
                  </td>
                  <td className="px-4 py-5 text-xs text-slate-400">
                    {p.jatuhTempo}
                  </td>
                  <td className="px-4 py-5 text-xs text-slate-300">
                    {p.tanggalKembali}
                  </td>
                  <td className="px-4 py-5">
                    <Badge
                      jenis={
                        (p.hariTerlambat || 0) > 0 ? "peringatan" : "sukses"
                      }
                    >
                      {p.hariTerlambat} hari
                    </Badge>
                  </td>
                  <td className="px-4 py-5 text-sm font-semibold text-white">
                    Rp {(p.denda || 0).toLocaleString("id-ID")}
                  </td>
                  <td className="px-4 py-5 text-right">
                    <Tombol varian="sekunder" onClick={() => setKembali(p)}>
                      Lihat Pengembalian
                    </Tombol>
                  </td>
                </tr>
              ))}
            </tbody>
          </table>
        </div>
      </Kartu>
      {kembali && (
        <ModalPengembalian
          data={kembali}
          onClose={() => setKembali(null)}
          onProcess={() => {}}
        />
      )}
      {cetak && (
        <Modal
          judul="Pratinjau laporan misi"
          onClose={() => setCetak(false)}
          lebar="max-w-3xl"
        >
          <div className="p-6">
            <div className="print-report rounded-2xl border border-slate-700 bg-slate-950/50 p-6">
              <div className="flex items-start justify-between border-b border-slate-700 pb-5">
                <Logo />
                <div className="text-right">
                  <div className="text-xs font-semibold text-white">
                    LAPORAN MISI SD-0625
                  </div>
                  <div className="mt-1 text-[10px] text-slate-500">
                    Dibuat 20 Juni 2025
                  </div>
                </div>
              </div>
              <div className="my-6">
                <div className="text-xl font-bold text-white">
                  Riwayat Peminjaman StarDeck
                </div>
                <p className="mt-2 text-xs text-slate-400">
                  Stasiun Andromeda · Modul Rekreasi Orbital
                </p>
              </div>
              <div className="grid grid-cols-3 gap-3">
                {[
                  ["Transaksi", String(selesai.length)],
                  [
                    "Hari terlambat",
                    String(
                      selesai.reduce((n, p) => n + (p.hariTerlambat || 0), 0),
                    ),
                  ],
                  ["Total denda", `Rp ${totalDenda.toLocaleString("id-ID")}`],
                ].map((i) => (
                  <div
                    key={i[0]}
                    className="rounded-xl border border-slate-700 p-3"
                  >
                    <div className="text-[9px] uppercase text-slate-500">
                      {i[0]}
                    </div>
                    <div className="mt-1 text-sm font-bold text-white">
                      {i[1]}
                    </div>
                  </div>
                ))}
              </div>
              <div className="mt-6 text-[10px] leading-5 text-slate-500">
                Dokumen ini dibuat otomatis oleh Sistem Inventaris StarDeck dan
                digunakan untuk audit operasional stasiun.
              </div>
            </div>
            <div className="mt-6 flex justify-end gap-3">
              <Tombol varian="sekunder" onClick={() => setCetak(false)}>
                Kembali
              </Tombol>
              <Tombol onClick={() => window.print()}>Cetak Sekarang</Tombol>
            </div>
          </div>
        </Modal>
      )}
    </>
  )
}

export default function App() {
  const [page, setPage] = useState<Page>("masuk")
  const [units, setUnits] = useState<Unit[]>(unitAwal)
  const [anggota, setAnggota] = useState<Anggota[]>(anggotaAwal)
  const [pinjaman, setPinjaman] = useState<PinjamanData[]>(pinjamanAwal)
  if (page === "masuk") return <Login setPage={setPage} />
  const role = ["dasbor-astronot", "katalog", "profil", "pinjaman"].includes(
    page,
  )
    ? "astronot"
    : "komandan"
  let content: ReactNode
  if (page === "dasbor-komandan") content = <DasborKomandan setPage={setPage} />
  else if (page === "dasbor-astronot")
    content = <DasborAstronot setPage={setPage} />
  else if (page === "inventaris")
    content = <Inventaris units={units} setUnits={setUnits} />
  else if (page === "katalog") content = <Katalog units={units} />
  else if (page === "profil") content = <Profil />
  else if (page === "kategori") content = <KategoriAdmin units={units} />
  else if (page === "astronot")
    content = (
      <AnggotaAdmin
        anggota={anggota}
        setAnggota={setAnggota}
        pinjaman={pinjaman}
      />
    )
  else if (page === "pinjaman-aktif")
    content = (
      <PinjamanAktifAdmin
        anggota={anggota}
        units={units}
        setUnits={setUnits}
        pinjaman={pinjaman}
        setPinjaman={setPinjaman}
      />
    )
  else if (page === "riwayat") content = <RiwayatLaporan pinjaman={pinjaman} />
  else content = <PinjamanSaya pinjaman={pinjaman} />
  return (
    <Kerangka page={page} setPage={setPage} role={role}>
      {content}
    </Kerangka>
  )
}
