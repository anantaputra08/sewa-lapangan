<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>GOR Jayabaya - Sistem Sewa Lapangan Online</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        'sans': ['Inter', 'ui-sans-serif', 'system-ui'],
                    },
                    colors: {
                        'blue': {
                            50: '#eff6ff',
                            100: '#dbeafe',
                            500: '#3b82f6',
                            600: '#2563eb',
                            700: '#1d4ed8',
                            800: '#1e40af',
                            900: '#1e3a8a'
                        },
                        'orange': {
                            50: '#fff7ed',
                            100: '#ffedd5',
                            500: '#f97316',
                            600: '#ea580c',
                            700: '#c2410c',
                            800: '#9a3412',
                            900: '#7c2d12'
                        }
                    }
                }
            }
        }
    </script>
</head>

<body class="font-sans antialiased bg-white">
    <!-- Navigation -->
    <nav class="fixed top-0 w-full bg-white/90 backdrop-blur-md border-b border-gray-100 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center space-x-2">
                    <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 17.93c-3.94-.49-7-3.85-7-7.93 0-.62.08-1.21.21-1.79L9 15v1c0 1.1.9 2 2 2v1.93zm6.9-2.54c-.26-.81-1-1.39-1.9-1.39h-1v-3c0-.55-.45-1-1-1H8v-2h2c.55 0 1-.45 1-1V7h2c1.1 0 2-.9 2-2v-.41c2.93 1.19 5 4.06 5 7.41 0 2.08-.8 3.97-2.1 5.39z"/>
                        </svg>
                    </div>
                    <span class="text-xl font-bold text-gray-900">GOR Jayabaya</span>
                </div>
                <div class="hidden md:flex items-center space-x-8">
                    <a href="#features" class="text-gray-600 hover:text-green-600 transition-colors">Fitur</a>
                    <a href="#pricing" class="text-gray-600 hover:text-green-600 transition-colors">Harga</a>
                    <a href="#contact" class="text-gray-600 hover:text-green-600 transition-colors">Kontak</a>
                    @if (Route::has('login'))
                        @auth
                            <button
                                class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700 transition-colors">
                                <a href="{{ url('/dashboard') }}">
                                    Dashboard
                                </a>
                            </button>
                        @else
                            <button
                                class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700 transition-colors">
                                <a href="{{ route('login') }}">
                                    Log In
                                </a>
                            </button>
                            @if (Route::has('register'))
                                <button
                                    class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700 transition-colors">
                                    <a href="{{ route('register') }}">
                                        Register
                                    </a>
                                </button>
                            @endif
                        @endauth
                    @endif
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="pt-20 pb-16 bg-gradient-to-br from-blue-50 to-orange-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid lg:grid-cols-2 gap-12 items-center">
                <div class="text-center lg:text-left">
                    <h1 class="text-4xl lg:text-6xl font-bold text-gray-900 mb-6">
                        Sewa Lapangan
                        <span class="text-blue-600">Olahraga</span>
                        dengan Mudah & Cepat
                    </h1>
                    <p class="text-xl text-gray-600 mb-8 leading-relaxed">
                        GOR Jayabaya menyediakan fasilitas olahraga lengkap dengan sistem booking online 24/7. 
                        Badminton, futsal, basket, dan tennis dalam satu lokasi strategis.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                        <button
                            class="bg-blue-600 text-white px-8 py-4 rounded-lg font-semibold hover:bg-blue-700 transition-all shadow-lg hover:shadow-xl">
                            Booking Sekarang
                        </button>
                        <button
                            class="border-2 border-gray-300 text-gray-700 px-8 py-4 rounded-lg font-semibold hover:border-blue-600 hover:text-blue-600 transition-all">
                            Lihat Fasilitas
                        </button>
                    </div>
                </div>
                <div class="flex justify-center">
                    <div class="bg-white rounded-2xl shadow-2xl p-8 max-w-md w-full">
                        <div class="space-y-4">
                            <div class="flex items-center justify-between p-4 bg-blue-50 rounded-lg">
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                        <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 17.93c-3.94-.49-7-3.85-7-7.93 0-.62.08-1.21.21-1.79L9 15v1c0 1.1.9 2 2 2v1.93zm6.9-2.54c-.26-.81-1-1.39-1.9-1.39h-1v-3c0-.55-.45-1-1-1H8v-2h2c.55 0 1-.45 1-1V7h2c1.1 0 2-.9 2-2v-.41c2.93 1.19 5 4.06 5 7.41 0 2.08-.8 3.97-2.1 5.39z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-900">Lapangan Badminton A1</p>
                                        <p class="text-sm text-gray-500">Tersedia: 5 lapangan</p>
                                    </div>
                                </div>
                                <p class="font-bold text-blue-600">Rp 45.000/jam</p>
                            </div>
                            <div class="flex items-center justify-between p-4 bg-orange-50 rounded-lg">
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center">
                                        <svg class="w-5 h-5 text-orange-600" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm0-14c-3.31 0-6 2.69-6 6s2.69 6 6 6 6-2.69 6-6-2.69-6-6-6z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-900">Lapangan Futsal</p>
                                        <p class="text-sm text-gray-500">Tersedia: 2 lapangan</p>
                                    </div>
                                </div>
                                <p class="font-bold text-orange-600">Rp 120.000/jam</p>
                            </div>
                            <div class="mt-6 p-4 bg-gray-50 rounded-lg">
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600">Total Booking Hari Ini</span>
                                    <span class="text-2xl font-bold text-gray-900">32 Sesi</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Facilities Section -->
    <section id="facilities" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-4">
                    Fasilitas Olahraga Lengkap
                </h2>
                <p class="text-xl text-gray-600">
                    Nikmati berbagai fasilitas olahraga berkualitas tinggi di GOR Jayabaya
                </p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                <div class="bg-white p-8 rounded-2xl border border-gray-100 hover:shadow-lg transition-shadow">
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mb-6">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">Lapangan Badminton</h3>
                    <p class="text-gray-600">8 lapangan badminton standar internasional dengan pencahayaan LED optimal dan lantai vinyl berkualitas tinggi.</p>
                </div>

                <div class="bg-white p-8 rounded-2xl border border-gray-100 hover:shadow-lg transition-shadow">
                    <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center mb-6">
                        <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">Lapangan Futsal</h3>
                    <p class="text-gray-600">3 lapangan futsal dengan rumput sintetis premium, dilengkapi sistem drainase dan pencahayaan stadium.</p>
                </div>

                <div class="bg-white p-8 rounded-2xl border border-gray-100 hover:shadow-lg transition-shadow">
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mb-6">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">Lapangan Basket</h3>
                    <p class="text-gray-600">2 lapangan basket indoor dengan ring adjustable dan lantai parket anti-slip untuk pengalaman bermain optimal.</p>
                </div>

                <div class="bg-white p-8 rounded-2xl border border-gray-100 hover:shadow-lg transition-shadow">
                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mb-6">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">Lapangan Tennis</h3>
                    <p class="text-gray-600">4 lapangan tennis outdoor dengan surface hard court dan net standar ITF, dilengkapi tribun penonton.</p>
                </div>

                <div class="bg-white p-8 rounded-2xl border border-gray-100 hover:shadow-lg transition-shadow">
                    <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center mb-6">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">Fasilitas Pendukung</h3>
                    <p class="text-gray-600">Parkir luas, kantin, mushola, ruang ganti, toilet, dan area istirahat yang nyaman untuk kenyamanan Anda.</p>
                </div>

                <div class="bg-white p-8 rounded-2xl border border-gray-100 hover:shadow-lg transition-shadow">
                    <div class="w-12 h-12 bg-teal-100 rounded-lg flex items-center justify-center mb-6">
                        <svg class="w-6 h-6 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">Sistem Booking Online</h3>
                    <p class="text-gray-600">Platform booking 24/7 dengan konfirmasi real-time, pembayaran digital, dan reminder otomatis.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Pricing Section -->
    <section id="pricing" class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-4">
                    Harga Sewa Lapangan
                </h2>
                <p class="text-xl text-gray-600">
                    Tarif kompetitif dengan fasilitas berkualitas tinggi
                </p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
                <div class="bg-white p-6 rounded-2xl shadow-lg">
                    <div class="text-center">
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">Badminton</h3>
                        <div class="text-3xl font-bold text-blue-600 mb-4">Rp 45.000<span class="text-sm text-gray-500">/jam</span></div>
                        <ul class="text-sm text-gray-600 space-y-2">
                            <li>• 8 lapangan tersedia</li>
                            <li>• Raket dan shuttlecock disediakan</li>
                            <li>• Pencahayaan LED optimal</li>
                            <li>• AC dan sound system</li>
                        </ul>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-2xl shadow-lg">
                    <div class="text-center">
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">Futsal</h3>
                        <div class="text-3xl font-bold text-orange-600 mb-4">Rp 120.000<span class="text-sm text-gray-500">/jam</span></div>
                        <ul class="text-sm text-gray-600 space-y-2">
                            <li>• 3 lapangan tersedia</li>
                            <li>• Bola futsal disediakan</li>
                            <li>• Rumput sintetis premium</li>
                            <li>• Pencahayaan stadium</li>
                        </ul>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-2xl shadow-lg">
                    <div class="text-center">
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">Basket</h3>
                        <div class="text-3xl font-bold text-green-600 mb-4">Rp 80.000<span class="text-sm text-gray-500">/jam</span></div>
                        <ul class="text-sm text-gray-600 space-y-2">
                            <li>• 2 lapangan tersedia</li>
                            <li>• Bola basket disediakan</li>
                            <li>• Lantai parket anti-slip</li>
                            <li>• Ring adjustable</li>
                        </ul>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-2xl shadow-lg">
                    <div class="text-center">
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">Tennis</h3>
                        <div class="text-3xl font-bold text-purple-600 mb-4">Rp 60.000<span class="text-sm text-gray-500">/jam</span></div>
                        <ul class="text-sm text-gray-600 space-y-2">
                            <li>• 4 lapangan tersedia</li>
                            <li>• Raket dan bola tennis disediakan</li>
                            <li>• Surface hard court</li>
                            <li>• Tribun penonton</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-20 bg-blue-600">
        <div class="max-w-4xl mx-auto text-center px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl lg:text-4xl font-bold text-white mb-6">
                Siap Bermain di GOR Jayabaya?
            </h2>
            <p class="text-xl text-blue-100 mb-8">
                Booking lapangan favorit Anda sekarang juga! Dapatkan pengalaman berolahraga terbaik di fasilitas modern kami.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <button
                    class="bg-white text-blue-600 px-8 py-4 rounded-lg font-semibold hover:bg-gray-50 transition-all shadow-lg">
                    Booking Sekarang
                </button>
                <button
                    class="border-2 border-white text-white px-8 py-4 rounded-lg font-semibold hover:bg-white hover:text-blue-600 transition-all">
                    Hubungi Kami
                </button>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-4 gap-8">
                <div>
                    <div class="flex items-center space-x-2 mb-4">
                        <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 17.93c-3.94-.49-7-3.85-7-7.93 0-.62.08-1.21.21-1.79L9 15v1c0 1.1.9 2 2 2v1.93zm6.9-2.54c-.26-.81-1-1.39-1.9-1.39h-1v-3c0-.55-.45-1-1-1H8v-2h2c.55 0 1-.45 1-1V7h2c1.1 0 2-.9 2-2v-.41c2.93 1.19 5 4.06 5 7.41 0 2.08-.8 3.97-2.1 5.39z"/>
                            </svg>
                        </div>
                        <span class="text-xl font-bold">GOR Jayabaya</span>
                    </div>
                    <p class="text-gray-400">
                        Pusat olahraga terlengkap di kawasan Jakarta Selatan
                    </p>
                </div>

                <div>
                    <h3 class="font-semibold mb-4">Fasilitas</h3>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="#" class="hover:text-white transition-colors">Badminton</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Futsal</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Basket</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Tennis</a></li>
                    </ul>
                </div>

                <div>
                    <h3 class="font-semibold mb-4">Layanan</h3>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="#" class="hover:text-white transition-colors">Booking Online</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Membership</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Event Organizer</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Turnamen</a></li>
                    </ul>
                </div>

                <div>
                    <h3 class="font-semibold mb-4">Kontak</h3>
                    <ul class="space-y-2 text-gray-400">
                        <li>Jl. Raya Jayabaya No. 123</li>
                        <li>Jakarta Selatan 12560</li>
                        <li>Telepon: (021) 7890-1234</li>
                        <li>WhatsApp: +62 812-3456-7890</li>
                        <li>Email: info@gorjayabaya.com</li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-gray-800 mt-12 pt-8 flex flex-col md:flex-row justify-between items-center">
                <p class="text-gray-400">&copy; 2024 GOR Jayabaya. Semua hak dilindungi.</p>
                <div class="flex space-x-6 mt-4 md:mt-0">
                    <a href="#" class="text-gray-400 hover:text-white transition-colors">Jam Operasional: 06:00 - 23:00 WIB</a>
                </div>
            </div>
        </div>
    </footer>

    <script>
        // Smooth scrolling for navigation links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Add scroll effect to navigation
        window.addEventListener('scroll', function() {
            const nav = document.querySelector('nav');
            if (window.scrollY > 50) {
                nav.classList.add('shadow-sm');
            } else {
                nav.classList.remove('shadow-sm');
            }
        });

        // Add fade-in animation for cards
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver(function(entries) {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, observerOptions);

        // Observe all cards
        document.querySelectorAll('.bg-white.p-8, .bg-white.p-6').forEach(card => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(30px)';
            card.style.transition = 'opacity 0.6s ease-out, transform 0.6s ease-out';
            observer.observe(card);
        });

        // Add hover effect to pricing cards
        document.querySelectorAll('.shadow-lg').forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-10px)';
                this.style.transition = 'transform 0.3s ease';
            });
            
            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
            });
        });