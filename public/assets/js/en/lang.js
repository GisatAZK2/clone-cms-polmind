// Guard untuk mencegah double declaration
if (typeof window.translations === 'undefined') {
var translations = window.translations = {
  id: {
      // Header & Menu
    "site-title": "POLITEKNIK MITRA INDUSTRI",
    "home": "BERANDA",
    "profile": "PROFIL",
    "lecturers": "DAFTAR DOSEN",
    "staff": "DAFTAR TENDIK",
    "advantages": "KEUNIKAN DAN KEUNGGULAN",
    "documentation": "DOKUMENTASI",
    "applied-program": "PRODI SARJANA TERAPAN",
    "admission": "PENERIMAAN MAHASISWA BARU",
    "language-id": "Bahasa Indonesia",
    "language-en": "Bahasa Inggris",

    // === BERANDA PAGE ===
    "home-welcome": "Selamat Datang di",
    "register-now-hero": "Daftar Sekarang",
    "team-lecturers-btn": "Tim Dosen",
    "advantages-title": "Keunggulan yang Membedakan Kami",
    "advantages-subtitle": "Pendekatan pendidikan berbasis industri nyata untuk mencetak lulusan yang siap kerja dan berdaya saing global.",
    "project-tag": "Project",
    "real-projects-title": "Real Industry Projects",
    "projects-desc": "Project dari Perusahaan di Dalam & Luar Kawasan MM2100",
    "projects-body": "Mahasiswa mengerjakan proyek nyata yang ditugaskan langsung oleh perusahaan mitra — bukan simulasi. Pengalaman ini membangun portofolio profesional dan koneksi industri sebelum lulus.",
    "pjbl-label": "Project-Based Learning (PjBL) kontekstual",
    "filter-all": "Semua",
    "filter-general": "Umum",
    "filter-achievement": "Prestasi",
    "filter-collaboration": "Kerjasama",
    "teaching-team": "Tim Pengajar",
    "professionals-title": "Dosen Profesional & Praktisi Industri Terbaik",
    "professionals-desc": "Perpaduan dosen akademisi berpengalaman dengan expert dan praktisi industri aktif. Mahasiswa belajar dari orang-orang yang benar-benar bekerja di bidangnya.",
    "professionals-label": "Mentoring langsung dari industri",
    "character-formation": "Pembentukan Karakter",
    "character-skills-title": "Attitude & Life Skills Siap Kerja",
    "character-skills-desc": "Tidak hanya hard skills teknis — kami mengutamakan pembentukan karakter, etika profesional, dan kemampuan hidup yang kuat sejak dini untuk menghadapi dunia kerja nyata.",
    "character-label": "Soft skills & karakter terintegrasi kurikulum",
    "study-programs-section": "Program Studi",
    "bachelor-degree-title": "Sarjana Terapan (D4) Prospek Cerah",
    "study-programs-desc": "Pilih program studi yang paling sesuai dengan minat dan potensi karir Anda di industri modern.",
    "employees-quote": "Terbuka juga bagi Karyawan yang ingin melanjutkan studi Sarjana Terapan!",
    "information-tag": "Informasi",
    "latest-news-title": "Berita Terkini",
    "news-section-desc": "Ikuti perkembangan terbaru dari kampus, kegiatan mahasiswa, dan dunia industri.",
    "news-empty-filter": "Tidak Ada berita yang Sesuai Filter",
    "ecosystem-tag": "Ekosistem Industri",
    "our-partners": "Mitra Kami",
    "partners-desc": "Didukung ratusan perusahaan industri terkemuka di dalam dan sekitar kawasan MM2100.",
    "partners-empty": "Belum ada logo mitra untuk ditampilkan.",
    "greeting-title": "Sambutan",
    "director-title": "Direktur",
    "cta-pmb-title": "Siap Bergabung dengan Polmind {{year}}?",
    "cta-pmb-desc": "Klik tombol daftar untuk melanjutkan ke halaman pendaftaran resmi dan lengkapi data Anda.",
    "register-btn-cta": "Daftar Sekarang →",
    "news-coming-soon": "Berita akan segera hadir",
    "news-no-data": "Belum ada berita",
    "news-no-description": "Pantau halaman ini untuk berita dan update terbaru dari kami.",

    // === PMB PAGE ===
    "pmb-hero-title": "Lebih dari Sekadar Kuliah, Kami adalah Inkubator Talenta Global!",
    "pmb-hero-subtitle": "Politeknik Mitra Industri hadir dengan konsep Teaching Factory yang revolusioner, mempersiapkanmu menjadi profesional handal dan membuka pintu karir impianmu di Jepang. Bergabunglah dengan kami dan ukir kisah suksesmu di panggung dunia!",
    "learn-more": "Selengkapnya",
    "register-now-btn" : "Daftar Sekarang",

    "pmb-page-title": "Pendaftaran Mahasiswa Baru",
    "pmb-page-description": "Bergabunglah dengan Politeknik Mitra Industri dan wujudkan impian karir Anda bersama kami.",

    "pmb-schedule-title": "Jadwal Pendaftaran",
    "pmb-registration-label": "Pendaftaran",
    "pmb-test-label": "Ujian Seleksi",
    "pmb-announcement-label": "Pengumuman",
    "pmb-reregistration-label": "Daftar Ulang",
    "pmb-schedule-empty-title": "Jadwal PMB belum tersedia.",
    "pmb-schedule-empty-desc": "Informasi jadwal pendaftaran akan ditampilkan di sini setelah diunggah oleh admin.",

    "pmb-requirements-title": "Persyaratan Administrasi",
    "pmb-requirements-empty-title": "Persyaratan administrasi belum diatur.",
    "pmb-requirements-empty-desc": "Silakan cek kembali nanti atau hubungi admin untuk informasi lebih lanjut.",

    "pmb-tuition-title": "Biaya Perkuliahan",
    "pmb-tuition-empty-title": "Biaya perkuliahan belum ditetapkan.",
    "pmb-tuition-empty-desc": "Informasi biaya akan ditampilkan setelah admin menambahkan daftar biaya per gelombang.",

    "pmb-financing-name": "Nama Pembiayaan",
    "pmb-financing-amount": "Nominal",
    "pmb-financing-period": "Periode",

    "pmb-closing-text": "Untuk melanjutkan proses pendaftaran, silakan klik tombol Daftar yang tersedia di bawah ini.",
    "pmb-register-button": "DAFTAR POLMIND",
    "pmb-help-text": "Jika ada pertanyaan, silakan hubungi admin kami di tombol WhatsApp di pojok kanan halaman.",

    "pmb-wave-label": "Gelombang",
    "pmb-fee-name": "Nama Biaya",
    "pmb-wave-default": "Gelombang",
    "pmb-discount-default": "Diskon",
    "pmb-cta-title-default": "Siap bergabung bersama Polmind?",
    "pmb-register-arrow-button": "DAFTAR POLMIND →",

    // === PROFILE PAGE ===
    "profil-hero-label": "Profil Institusi",
    "profil-visi-heading": "VISI",
    "profil-misi-heading": "MISI",
    "profil-visi-label": "Visi",
    "profil-founders-empty": "Data Jajaran Pendiri &amp; Experts sedang disiapkan.",
    "profil-decree": "Berdasarkan Keputusan Menteri DIKTI SAINTEK No 324/B/O/2025",
    "profil-founders": "Jajaran Pendiri",
    "profil-experts": "Experts",
    "profil-desc": "Politeknik Mitra Industri didirikan oleh jajaran pimpinan dan expert dari Industri, bersama Praktisi Pendidikan Vokasi.",

    // === LECTURERS PAGE ===
    "lecturers-internal-subtitle": "Akademisi berpengalaman yang mendampingi perjalanan studi Anda",
    "lecturers-internal-empty-title": "Belum ada dosen internal",
    "lecturers-internal-empty-desc": "Data dosen internal belum tersedia saat ini. Data akan muncul setelah ditambahkan.",
    "lecturers-expert-subtitle": "Praktisi aktif dari industri yang membawa pengalaman nyata ke ruang kelas",
    "lecturers-expert-empty-title": "Belum ada expert industri",
    "lecturers-expert-empty-desc": "Data expert industri belum tersedia saat ini. Data akan muncul setelah ditambahkan.",
    "modal-about-label": "Tentang",
    "modal-lecturer-empty-desc": "Belum ada deskripsi untuk dosen ini.",
    "modal-close-label": "Tutup",
    "internal-lecturers" : "Dosen Internal",
    "industry-lecturers" : "Expert Industri",

    // === STAFF PAGE ===
    "internal-staff": "Tenaga Pendidik",
    "staff-subtitle": "Profesional berpengalaman yang mendampingi perjalanan studi Anda",
    "staff-empty-title": "Belum ada tenaga pendidik",
    "staff-empty-desc": "Data tenaga pendidik belum tersedia saat ini. Data akan muncul setelah ditambahkan.",

    // === DOCUMENTATION PAGE ===
    "documentation-page-title": "DOKUMENTASI",
    "documentation-page-subtitle": "Arsip foto dan dokumentasi berbagai kegiatan yang telah dilaksanakan",
    "documentation-empty-title": "Belum ada dokumentasi",
    "documentation-empty-desc": "Dokumentasi kegiatan belum tersedia saat ini. Data akan muncul setelah ditambahkan.",
    "documentation-latest-badge": "Kegiatan Terbaru",
    "documentation-activity-label": "Kegiatan",
    "documentation-default-title": "Dokumentasi",
    "documentation-image-alt": "Dokumentasi kegiatan",
    "documentation-modal-close-label": "Tutup preview gambar",
    "documentation-modal-image-alt": "Preview dokumentasi",

    // === KEUNIKAN PAGE ===
    "uniq-title": "Keunikan & Keunggulan",
    "uniq-hero-subtitle": "Pendekatan pendidikan berbasis industri nyata — bukan sekadar teori di dalam kelas.",
    'uniq-empty-title': 'Belum Diisi',
    'uniq-empty-desc': 'Konten keunikan & keunggulan belum tersedia.',
    'uniq-image-alt': 'Gambar keunikan dan keunggulan Polmind',

    "uniq-cta-label": "Tertarik bergabung?",
    "uniq-cta-text": "Daftarkan dirimu sekarang dan mulai perjalanan karir bersama Polmind.",
    "uniq-cta-button": "Daftar Sekarang →",

    // === PROGRAM STUDI PAGE ===
    "prodi-page-title": "PROGRAM STUDI",
    "prodi-page-subtitle": "Pilih program studi yang sesuai dengan minat dan tujuan kariermu",
    "prodi-empty-title": "Belum ada program studi",
    "prodi-empty-desc": "Data program studi belum tersedia saat ini. Silakan kembali lagi nanti atau hubungi bagian akademik.",
    "prodi-meta-semester": "8 Semester",
    "prodi-meta-accreditation": "Akreditasi",
    "prodi-meta-degree": "Sarjana Terapan",
    "prodi-curriculum-label": "Outline kurikulum",
    "prodi-curriculum-intro-prefix": "Berikut ini adalah outline kurikulum untuk",
    "prodi-semester-label": "Semester",
    "prodi-semester-odd": "Ganjil",
    "prodi-semester-even": "Genap",
    "prodi-modal-close-label": "Tutup preview gambar program studi",
    "prodi-modal-image-alt": "Preview gambar program studi",

    // === NEWS CATEGORY (jenis_content) - ENUM VALUES ===
    "category-umum": "Umum",
    "category-prestasi": "Prestasi",
    "category-kerjasama": "Kerjasama",

    // === NEWS LIST PAGE ===
    "news-list-title": "Berita Terkini",
    "news-list-subtitle": "Update terbaru dan informasi penting dari Politeknik Mitra Industri",
    "news-read-more": "Baca Selengkapnya →",
    "news-empty-title": "Belum Ada Berita",
    "news-empty-desc": "Berita terbaru akan segera kami publikasikan",
    "news-author": "Penulis : ",

    // === SHARE BAR ===
    "share-label": "Bagikan:",
    "share-whatsapp": "WhatsApp",
    "share-telegram": "Telegram",
    "share-twitter": "Tweet",
    "share-facebook": "Facebook",
    "share-copy-link": "Salin Link",
    "share-copied": "Disalin!",

    // === NEWS DETAIL ===
    "news-back-link": "← Kembali ke Daftar Berita",
    "news-related-title": "Berita Terkait",

    "loader-text": "Memuat halaman...",

    "banner-why": "Mengapa Polmind",
    "stats-placeholder" : "Belum tersedia",

    "error-401-title": "Tidak Terautentikasi",
    "error-401-desc": "Anda perlu masuk terlebih dahulu untuk mengakses halaman ini.<br>Silakan login dengan akun Anda.",
    "error-403-title": "Akses Ditolak",
    "error-403-desc": "Anda tidak memiliki izin untuk mengakses halaman ini.<br>Silakan masuk dengan akun yang sesuai atau hubungi administrator.",
    "error-404-title": "Halaman Tidak Ditemukan",
    "error-404-desc": "Halaman yang Anda cari tidak tersedia atau mungkin telah dipindahkan.<br>Periksa kembali alamat URL atau kembali ke beranda.",
    "error-405-title": "Metode Tidak Diizinkan",
    "error-405-desc": "Permintaan yang Anda kirim tidak diizinkan untuk halaman ini.<br>Kembali dan coba cara yang berbeda.",
    "error-419-title": "Sesi Telah Berakhir",
    "error-419-desc": "Halaman ini sudah kadaluarsa karena tidak ada aktivitas.<br>Muat ulang halaman untuk melanjutkan.",
    "error-429-title": "Terlalu Banyak Permintaan",
    "error-429-desc": "Anda mengirim terlalu banyak permintaan dalam waktu singkat.<br>Tunggu beberapa saat sebelum mencoba kembali.",
    "error-500-title": "Kesalahan pada Server",
    "error-500-desc": "Terjadi kesalahan internal pada server kami.<br>Tim teknis kami sedang menangani masalah ini. Silakan coba beberapa saat lagi.",
    "error-502-title": "Gateway Bermasalah",
    "error-502-desc": "Server menerima respons tidak valid dari server upstream.<br>Masalah ini bersifat sementara, silakan coba beberapa saat lagi.",
    "error-503-badge": "Sedang Pemeliharaan",
    "error-503-title": "Layanan Tidak Tersedia",
    "error-503-desc": "Sistem sedang dalam proses pemeliharaan terjadwal.<br>Kami akan segera kembali online. Terima kasih atas kesabaran Anda.",
    "error-504-title": "Gateway Timeout",
    "error-504-desc": "Server tidak merespons dalam waktu yang ditentukan.<br>Koneksi mungkin lambat atau server sedang sibuk. Coba lagi.",
  },

  en: {
    // Header & Menu
    "site-title": "MITRA INDUSTRY POLYTECHNIC",
    "home": "HOME",
    "profile": "PROFILE",
    "lecturers": "LECTURERS",
    "staff": "STAFF",
    "advantages": "ADVANTAGES & STRENGTHS",
    "documentation": "DOCUMENTATION",
    "applied-program": "APPLIED BACHELOR'S PROGRAMS",
    "admission": "NEW STUDENT ADMISSION",
    "language-id": "Indonesian",
    "language-en": "English",

    // === PROFILE PAGE ===
    "profil-hero-label": "Institution Profile",
    "profil-visi-heading": "VISION",
    "profil-misi-heading": "MISSION",
    "profil-visi-label": "Vision",
    "profil-founders-empty": "Founder & Expert data is being prepared.",
    "profil-decree": "Based on the Decision of the Minister of DIKTI SAINTEK No 324/B/O/2025",
    "profil-founders": "Founders",
    "profil-experts": "Experts",
    "profil-desc": "Politeknik Mitra Industri was founded by industry leaders and experts, together with vocational education practitioners.",

    // Footer
    "footer-area": "MM2100 Industrial Area",
    "about": "About Polmind",
    "profile-footer": "Profile",
    "advantages-footer": "Advantages & Strengths",
    "founder-footer": "Founders & Experts",
    "study-programs": "Study Programs",
    "manuf-tech": "Manufacturing Engineering Tech",
    "digital-business": "Digital Business",
    "softeng-tech": "Software Engineering Tech",
    "support": "Support",
    "documentation-footer": "Documentation",
    "register-footer": "New Student Registration",
    "copyright": "© 2025 Yayasan Mitra Global Mandiri",

    // === BERANDA PAGE ===
    "home-welcome": "Welcome to",
    "register-now-hero": "Register Now",
    "team-lecturers-btn": "Lecturers Team",
    "advantages-title": "Advantages That Set Us Apart",
    "advantages-subtitle": "Industry-based education approach to produce graduates who are work-ready and globally competitive.",
    "project-tag": "Project",
    "real-projects-title": "Real Industry Projects",
    "projects-desc": "Projects from Companies Within & Outside MM2100 Industrial Area",
    "projects-body": "Students work on real projects assigned directly by partner companies — not simulations. This experience builds a professional portfolio and industry connections before graduation.",
    "pjbl-label": "Contextual Project-Based Learning (PjBL)",
    "filter-all": "All",
    "filter-general": "General",
    "filter-achievement": "Achievement",
    "filter-collaboration": "Collaboration",
    "teaching-team": "Teaching Team",
    "professionals-title": "Professional Lecturers & Best Industry Practitioners",
    "professionals-desc": "A combination of experienced academic lecturers with active industry experts and practitioners. Students learn from people who actually work in their field.",
    "professionals-label": "Direct mentoring from industry",
    "character-formation": "Character Formation",
    "character-skills-title": "Attitude & Work-Ready Life Skills",
    "character-skills-desc": "Not just technical hard skills — we prioritize character development, professional ethics, and strong life skills from the start to face the real working world.",
    "character-label": "Soft skills & character integrated into curriculum",
    "study-programs-section": "Study Programs",
    "bachelor-degree-title": "Applied Bachelor's Degree (D4) with Bright Prospects",
    "study-programs-desc": "Choose the study program that best suits your interests and career potential in the modern industry.",
    "employees-quote": "Also open to employees who want to continue their applied bachelor's degree studies!",
    "information-tag": "Information",
    "latest-news-title": "Latest News",
    "news-section-desc": "Follow the latest developments from campus, student activities, and the industry world.",
    "news-empty-filter": "No news matches the selected filter",
    "ecosystem-tag": "Industrial Ecosystem",
    "our-partners": "Our Partners",
    "partners-desc": "Supported by hundreds of leading industrial companies within and around the MM2100 industrial area.",
    "partners-empty": "No partner logos to display yet.",
    "greeting-title": "Greeting",
    "director-title": "Director",
    "cta-pmb-title": "Ready to Join Polmind {{year}}?",
    "cta-pmb-desc": "Click the register button to proceed to the official registration page and complete your data.",
    "register-btn-cta": "Register Now →",
    "news-coming-soon": "News coming soon",
    "news-no-data": "No news available yet",
    "news-no-description": "Monitor this page for the latest news and updates from us.",

    // === PMB PAGE ===
    "pmb-hero-title": "More Than Just College, We Are an Incubator for Global Talent!",
    "pmb-hero-subtitle": "Politeknik Mitra Industri presents a revolutionary Teaching Factory concept, preparing you to become a skilled professional and opening the door to your dream career in Japan. Join us and create your success story on the global stage!",
    "learn-more": "Learn More",
    "register-now-btn": "Register Now",
    "pmb-page-title": "New Student Admission",
    "pmb-page-description": "Join Politeknik Mitra Industri and realize your career dreams with us.",
    "pmb-schedule-title": "Registration Schedule",
    "pmb-registration-label": "Registration",
    "pmb-test-label": "Selection Test",
    "pmb-announcement-label": "Announcement",
    "pmb-reregistration-label": "Re-registration",
    "pmb-schedule-empty-title": "PMB schedule not available yet.",
    "pmb-schedule-empty-desc": "Registration schedule information will be displayed here after uploaded by the admin.",
    "pmb-requirements-title": "Administrative Requirements",
    "pmb-requirements-empty-title": "Administrative requirements not set yet.",
    "pmb-requirements-empty-desc": "Please check back later or contact the admin for more information.",
    "pmb-tuition-title": "Tuition Fees",
    "pmb-tuition-empty-title": "Tuition fees not set yet.",
    "pmb-tuition-empty-desc": "Fee information will be displayed after the admin adds the fee list per batch.",
    "pmb-financing-name": "Financing Name",
    "pmb-financing-amount": "Amount",
    "pmb-financing-period": "Period",
    "pmb-closing-text": "To continue the registration process, please click the Register button below.",
    "pmb-register-button": "REGISTER POLMIND",
    "pmb-help-text": "If you have any questions, please contact our admin via the WhatsApp button in the bottom right corner of the page.",

    "pmb-wave-label": "Batch",
    "pmb-fee-name": "Fee Name",
    "pmb-wave-default": "Batch",
    "pmb-discount-default": "Discount",
    "pmb-cta-title-default": "Ready to join Polmind?",
    "pmb-register-arrow-button": "REGISTER POLMIND →",

    // === LECTURERS PAGE ===
    "lecturers-internal-subtitle": "Experienced academics who support your study journey",
    "lecturers-internal-empty-title": "No internal lecturers yet",
    "lecturers-internal-empty-desc": "Internal lecturer data is not available at the moment. The data will appear once it has been added.",
    "lecturers-expert-subtitle": "Active industry practitioners who bring real-world experience into the classroom",
    "lecturers-expert-empty-title": "No industry experts yet",
    "lecturers-expert-empty-desc": "Industry expert data is not available at the moment. The data will appear once it has been added.",
    "modal-about-label": "About",
    "modal-lecturer-empty-desc": "No description is available for this lecturer yet.",
    "modal-close-label": "Close",
    "internal-lecturers" : "Internal Lecturers",
    "industry-lecturers" : "Industry Experts",

    // === STAFF PAGE ===
    "internal-staff": "Education Staff",
    "staff-subtitle": "Experienced professionals who support your study journey",
    "staff-empty-title": "No education staff yet",
    "staff-empty-desc": "Education staff data is not available at the moment. The data will appear once it has been added.",

    // === DOCUMENTATION PAGE ===
    "documentation-page-title": "DOCUMENTATION",
    "documentation-page-subtitle": "Photo archives and documentation of various activities that have been carried out",
    "documentation-empty-title": "No documentation yet",
    "documentation-empty-desc": "Activity documentation is not available at the moment. The data will appear once it has been added.",
    "documentation-latest-badge": "Latest Activity",
    "documentation-activity-label": "Activity",
    "documentation-default-title": "Documentation",
    "documentation-image-alt": "Activity documentation",
    "documentation-modal-close-label": "Close image preview",
    "documentation-modal-image-alt": "Documentation preview",

    // === ADVANTAGES PAGE ===
    "uniq-title": "Advantages & Strengths",
    "uniq-hero-subtitle": "A real industry-based education approach — not just classroom theory.",
    'uniq-empty-title': 'Not Filled Yet',
    'uniq-empty-desc': 'Advantages and strengths content is not available yet.',
    'uniq-image-alt': 'Polmind advantages and strengths image',

    "uniq-cta-label": "Interested in joining?",
    "uniq-cta-text": "Register now and start your career journey with Polmind.",
    "uniq-cta-button": "Register Now →",

    // === STUDY PROGRAM PAGE ===
    "prodi-page-title": "STUDY PROGRAMS",
    "prodi-page-subtitle": "Choose the study program that matches your interests and career goals",
    "prodi-empty-title": "No study programs yet",
    "prodi-empty-desc": "Study program data is not available at the moment. Please come back later or contact the academic office.",
    "prodi-meta-semester": "8 Semesters",
    "prodi-meta-accreditation": "Accreditation",
    "prodi-meta-degree": "Applied Bachelor",
    "prodi-curriculum-label": "Curriculum outline",
    "prodi-curriculum-intro-prefix": "Here is the curriculum outline for",
    "prodi-semester-label": "Semester",
    "prodi-semester-odd": "Odd",
    "prodi-semester-even": "Even",
    "prodi-modal-close-label": "Close study program image preview",
    "prodi-modal-image-alt": "Study program image preview",

    // === NEWS CATEGORY (jenis_content) - ENUM VALUES ===
    "category-umum": "General",
    "category-prestasi": "Achievement",
    "category-kerjasama": "Collaboration",

    // === NEWS LIST PAGE ===
    "news-list-title": "Latest News",
    "news-list-subtitle": "Latest updates and important information from Politeknik Mitra Industri",
    "news-read-more": "Read More →",
    "news-empty-title": "No News Yet",
    "news-empty-desc": "Latest news will be published soon",
    "news-author": "Author : ",

    // === SHARE BAR ===
    "share-label": "Share:",
    "share-whatsapp": "WhatsApp",
    "share-telegram": "Telegram",
    "share-twitter": "Tweet",
    "share-facebook": "Facebook",
    "share-copy-link": "Copy Link",
    "share-copied": "Copied!",

    "banner-why": "Why Polmind",
    "stats-placeholder" : "Not available yet",
    "btn-register" : "Register Now",
    "btn-lecturers" : "Lecturers Team",
    "uniq-empty-title": "Not Filled Yet",
    "uniq-empty-desc": "Advantages and strengths content is not available yet.",

    // === NEWS DETAIL ===
    "news-back-link": "← Back to News List",
    "news-related-title": "Related News",

    "loader-text": "Loading page...",

    "error-401-title": "Unauthenticated",
    "error-401-desc": "You need to log in first to access this page.<br>Please sign in with your account.",
    "error-403-title": "Access Denied",
    "error-403-desc": "You do not have permission to access this page.<br>Please sign in with the appropriate account or contact the administrator.",
    "error-404-title": "Page Not Found",
    "error-404-desc": "The page you are looking for is unavailable or may have been moved.<br>Please check the URL or return to the homepage.",
    "error-405-title": "Method Not Allowed",
    "error-405-desc": "The request method you sent is not allowed for this page.<br>Go back and try a different way.",
    "error-419-title": "Session Expired",
    "error-419-desc": "This page has expired due to inactivity.<br>Reload the page to continue.",
    "error-429-title": "Too Many Requests",
    "error-429-desc": "You have sent too many requests in a short period of time.<br>Please wait a moment before trying again.",
    "error-500-title": "Server Error",
    "error-500-desc": "An internal error occurred on our server.<br>Our technical team is working on the issue. Please try again later.",
    "error-502-title": "Bad Gateway",
    "error-502-desc": "The server received an invalid response from the upstream server.<br>This issue is temporary, please try again later.",
    "error-503-badge": "Under Maintenance",
    "error-503-title": "Service Unavailable",
    "error-503-desc": "The system is currently undergoing scheduled maintenance.<br>We will be back online soon. Thank you for your patience.",
    "error-504-title": "Gateway Timeout",
    "error-504-desc": "The server did not respond within the specified time.<br>The connection may be slow or the server may be busy. Please try again.",
  }
};
} // End: Guard untuk mencegah double declaration

/* ===============================
   SWITCHER + IMG MAPPING
   =============================== */

// Alias untuk kemudahan akses
var translations = window.translations || {};

// Ubah innerHTML pada elemen dengan data-translate.
// Untuk <img>, alt akan diganti (bukan src — src diatur oleh imgMapping).
function applyTextTranslations(lang) {
  document.querySelectorAll('[data-translate]').forEach(el => {
    const key = el.getAttribute('data-translate');
    const val = translations?.[lang]?.[key];

    if (!val) return;

    if (el.tagName === 'IMG') {
      el.alt = val;
    } else {
      el.innerHTML = val;
    }
  });

  document.querySelectorAll('[data-translate-attr][data-translate-key]').forEach(el => {
    const attr = el.getAttribute('data-translate-attr');
    const key = el.getAttribute('data-translate-key');
    const val = translations?.[lang]?.[key];

    if (!attr || !val) return;

    el.setAttribute(attr, val);
  });

  if (translations[lang]["page-title-home"]) {
    try { document.title = translations[lang]["page-title-home"]; } catch(e) {}
  }
}

// Ganti sumber gambar sesuai bahasa (pastikan ID sesuai di HTML).
function applyImageTranslations(lang) {
  const imgMapping = {
    // Slider 1–13 (sesuaikan ID elemen: id="img-slider1" ... "img-slider13")
    "slider1":  { id: "../assets/images/slider1.png",            en: "../assets/images/en/slider01_en.png" },
    "slider2":  { id: "../assets/images/slider6.jpg",            en: "../assets/images/en/slider02_en.jpg" },
    "slider3":  { id: "../assets/images/slider2.png",            en: "../assets/images/en/slider03_en.png" },
    "slider4":  { id: "../assets/images/slider3.png",            en: "../assets/images/en/slider04_en.png" },
    "slider5":  { id: "../assets/images/slider4.png",            en: "../assets/images/en/slider05_en.png" },
    "slider6":  { id: "../assets/images/slider5.png",            en: "../assets/images/en/slider06_en.png" },
    "slider7":  { id: "../assets/images/slider/slider15.png",    en: "../assets/images/en/slider07_en.png" },
    "slider8":  { id: "../assets/images/slider/slider16.png",    en: "../assets/images/en/slider08_en.png" },
    "slider9":  { id: "../assets/images/slider/slider17.png",    en: "../assets/images/en/slider09_en.png" },
    "slider10": { id: "../assets/images/slider/slider18.png",    en: "../assets/images/en/slider10_en.png" },
    "slider11": { id: "../assets/images/slider/slider19.png",    en: "../assets/images/en/slider11_en.png" },
    "slider12": { id: "../assets/images/slider/slider21.png",    en: "../assets/images/en/slider12_en.png" },
    "slider13": { id: "../assets/images/slider/slider22.png",    en: "../assets/images/en/slider13_en.png" },

    // Banner pendaftaran
    "wp_daftar": { id: "../assets/images/wp_daftar.png",         en: "../assets/images/en/wp_daftar_en.png" },

    // Prodi (desktop <img>) dan mobile <source> (srcset)
    "prodi":     { id: "../assets/images/prodi.png",             en: "../assets/images/en/prodi_en.png" },
    "prodi_sp":  { id: "../assets/images/prodi-sp.png",          en: "../assets/images/en/prodi-sp_en.png" }, // <source id="src-prodi-sp">

    // Page Profile
    "profil":   { id: "../assets/images/b_profil.png", en: "../assets/images/en/b_profil_en.png" },
    "profil_sp": { id: "../assets/images/m_profil.png", en: "../assets/images/en/b_profil_en.png" }
  };

  // Ganti sumber gambar jika elemen ditemukan
  for (const [key, srcs] of Object.entries(imgMapping)) {
    const imgEl = document.getElementById('img-' + key);
    const sourceEl = document.getElementById('src-' + key + '-sp'); // untuk <source> mobile

    if (imgEl) imgEl.src = srcs[lang];
    if (sourceEl) sourceEl.srcset = srcs[lang];
  }
}

function switchLanguage(lang) {
  if (lang !== 'id' && lang !== 'en') {
    lang = 'id';
  }

  try {
    localStorage.setItem('polmind_lang', lang);
  } catch (e) {}

  document.documentElement.lang = lang;

  applyTextTranslations(lang);
  applyImageTranslations(lang);

  document.documentElement.classList.remove('polmind-loading-lang');
}

// Inisialisasi saat halaman siap
(function initLang() {
  function getStartLang() {
    const earlyLang = window.polmindInitLang || null;

    let saved = null;
    try {
      saved = localStorage.getItem('polmind_lang');
    } catch (e) {}

    const htmlLang = (document.documentElement.getAttribute('lang') || 'id').toLowerCase();

    if (earlyLang === 'en' || earlyLang === 'id') return earlyLang;
    if (saved === 'en' || saved === 'id') return saved;
    if (htmlLang === 'en' || htmlLang === 'id') return htmlLang;

    return 'id';
  }

  const startLang = getStartLang();

  function initSelects() {
    const desktopSelect = document.getElementById('languageSwitcher');
    const mobileSelect = document.getElementById('languageSwitcherMobile');

    if (desktopSelect) desktopSelect.value = startLang;
    if (mobileSelect) mobileSelect.value = startLang;

    if (desktopSelect) {
      desktopSelect.addEventListener('change', function () {
        const lang = this.value === 'en' ? 'en' : 'id';

        if (mobileSelect) mobileSelect.value = lang;
        switchLanguage(lang);
      });
    }

    if (mobileSelect) {
      mobileSelect.addEventListener('change', function () {
        const lang = this.value === 'en' ? 'en' : 'id';

        if (desktopSelect) desktopSelect.value = lang;
        switchLanguage(lang);
      });
    }
  }

  switchLanguage(startLang);

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initSelects);
  } else {
    initSelects();
  }
})();

/* ====== Tambahan terjemahan untuk Kartu Berita ====== */
/* Bisa ditempel di akhir translate.js atau file terpisah yang di-load SETELAH translate.js */

(function () {
  // Pastikan namespace tersedia
  if (typeof window.translations === "undefined") {
    window.translations = { id: {}, en: {} };
  }

  // Setup shortcuts
  const translations = window.translations;
  translations.id = translations.id || {};
  translations.en = translations.en || {};

  // ---------- Indonesia ----------
  Object.assign(translations.id, {
    // === TIME TRANSLATION MAPPINGS ===
    "time-just-now": "baru saja",
    "time-1-second-ago": "1 detik yang lalu",
    "time-seconds-ago": "{count} detik yang lalu",
    "time-1-minute-ago": "1 menit yang lalu",
    "time-minutes-ago": "{count} menit yang lalu",
    "time-1-hour-ago": "1 jam yang lalu",
    "time-hours-ago": "{count} jam yang lalu",
    "time-1-day-ago": "1 hari yang lalu",
    "time-days-ago": "{count} hari yang lalu",
    "time-1-week-ago": "1 minggu yang lalu",
    "time-weeks-ago": "{count} minggu yang lalu",
    "time-1-month-ago": "1 bulan yang lalu",
    "time-months-ago": "{count} bulan yang lalu",
    "time-1-year-ago": "1 tahun yang lalu",
    "time-years-ago": "{count} tahun yang lalu",
  });

  // ---------- English ----------
  Object.assign(translations.en, {
    "time-just-now": "just now",
    "time-1-second-ago": "1 second ago",
    "time-seconds-ago": "{count} seconds ago",
    "time-1-minute-ago": "1 minute ago",
    "time-minutes-ago": "{count} minutes ago",
    "time-1-hour-ago": "1 hour ago",
    "time-hours-ago": "{count} hours ago",
    "time-1-day-ago": "1 day ago",
    "time-days-ago": "{count} days ago",
    "time-1-week-ago": "1 week ago",
    "time-weeks-ago": "{count} weeks ago",
    "time-1-month-ago": "1 month ago",
    "time-months-ago": "{count} months ago",
    "time-1-year-ago": "1 year ago",
    "time-years-ago": "{count} years ago",
  });

  /* Setelah menambahkan kunci, langsung re-apply bahasa aktif
     agar kartu berita ikut berubah tanpa reload. */
  var activeLang = "id";
  try {
    const select = document.getElementById("languageSwitcher");
    const saved = localStorage.getItem("polmind_lang");
    if (select && (select.value === "id" || select.value === "en")) {
      activeLang = select.value;
    } else if (saved === "id" || saved === "en") {
      activeLang = saved;
    } else {
      const htmlLang = (document.documentElement.getAttribute("lang") || "id").toLowerCase();
      activeLang = (htmlLang === "en") ? "en" : "id";
    }
  } catch (e) {}

  if (typeof switchLanguage === "function") {
    switchLanguage(activeLang);
  }
})();
document.addEventListener('turbo:before-render', function () {
  let lang = 'id';

  try {
    lang = localStorage.getItem('polmind_lang') || window.polmindInitLang || 'id';
  } catch (e) {}

  if (lang === 'en') {
    document.documentElement.classList.add('polmind-loading-lang');
  }
});

document.addEventListener('turbo:render', function () {
  let lang = 'id';

  try {
    lang = localStorage.getItem('polmind_lang') || window.polmindInitLang || 'id';
  } catch (e) {}

  if (typeof switchLanguage === 'function') {
    switchLanguage(lang);
  } else {
    document.documentElement.classList.remove('polmind-loading-lang');
  }
});

document.addEventListener('turbo:load', function () {
  let lang = 'id';

  try {
    lang = localStorage.getItem('polmind_lang') || window.polmindInitLang || 'id';
  } catch (e) {}

  if (typeof switchLanguage === 'function') {
    switchLanguage(lang);
  } else {
    document.documentElement.classList.remove('polmind-loading-lang');
  }
});