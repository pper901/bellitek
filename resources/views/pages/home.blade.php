@extends('layouts.app')

@section('content')

{{-- Message Alert Notifications --}}
@if (session('error'))
    <x-message-alert type="error" :message="session('error')" />
@elseif (session('success'))
    <x-message-alert type="success" :message="session('success')" />
@endif

<style>
    @keyframes bt-pulse {
        0%, 100% { opacity: 1; transform: scale(1); }
        50% { opacity: .4; transform: scale(.75); }
    }
    .bt-live-dot { animation: bt-pulse 2s ease-in-out infinite; }
    @media (prefers-reduced-motion: reduce) {
        .bt-live-dot { animation: none; }
    }
    .bt-grid-overlay {
        background-image:
            repeating-linear-gradient(90deg, rgba(255,255,255,.06) 0 1px, transparent 1px 64px),
            repeating-linear-gradient(0deg, rgba(255,255,255,.06) 0 1px, transparent 1px 64px);
    }
    .bt-trace {
        transform: scaleX(0);
        transform-origin: left;
        transition: transform .35s cubic-bezier(.4,0,.2,1);
    }
    .group:hover .bt-trace { transform: scaleX(1); }
</style>

<!-- Hero Section -->
<section
    class="relative text-center py-28 text-white bg-cover bg-center rounded-b-2xl shadow-xl overflow-hidden mb-16"
    style="background-image: url('https://5to6o2z4j5.ucarecd.net/8c41370d-2659-4949-99d5-1e5318fd08d8/-/preview/1000x1000/');"
>
    <div class="absolute inset-0 bg-gradient-to-b from-[#12181F]/95 via-[#12181F]/80 to-[#12181F]/95"></div>
    <div class="absolute inset-0 bt-grid-overlay" aria-hidden="true"></div>

    <div class="relative z-10 max-w-4xl mx-auto px-4">
        <div class="inline-flex items-center gap-2 font-mono text-xs sm:text-sm uppercase tracking-[0.25em] text-[#6FE0B0] mb-6">
            <span class="relative flex h-2 w-2">
                <span class="bt-live-dot absolute inline-flex h-full w-full rounded-full bg-[#6FE0B0]"></span>
            </span>
            Lagos Repair &amp; ICT Workshop
        </div>

        <h1 class="text-4xl sm:text-6xl font-extrabold mb-5 tracking-tight leading-[1.05]">
            We Fix. You Relax.
        </h1>

        <div class="flex flex-wrap justify-center gap-2 mb-10">
            <span class="font-mono text-xs uppercase tracking-wider bg-white/10 border border-white/15 rounded-full px-3 py-1.5 text-gray-200">Laptop &amp; Phone Repairs</span>
            <span class="font-mono text-xs uppercase tracking-wider bg-white/10 border border-white/15 rounded-full px-3 py-1.5 text-gray-200">Premium Parts</span>
            <span class="font-mono text-xs uppercase tracking-wider bg-white/10 border border-white/15 rounded-full px-3 py-1.5 text-gray-200">ICT Solutions</span>
        </div>

        <div class="flex flex-wrap justify-center items-center gap-4">
            <a href="/repair/book"
               class="bg-[#E8A33D] text-[#12181F] px-8 py-3.5 rounded-full font-bold hover:bg-[#f2b256] transition duration-150 shadow-lg hover:shadow-[#E8A33D]/30">
                Track Your Repair
            </a>

            <a href="{{ route('store.index') }}"
               class="border border-white/40 text-white px-8 py-3.5 rounded-full font-semibold hover:bg-white/10 hover:border-white/70 transition duration-150">
                Explore Store
            </a>

            @auth
                @if(auth()->user()->role === 'admin')
                    <a href="{{ route('admin.dashboard') }}"
                       class="font-mono text-xs uppercase tracking-wider border border-white/25 text-gray-300 px-4 py-2 rounded-full hover:text-white hover:border-white/50 transition duration-150">
                        Admin Dashboard
                    </a>
                @endif
            @endauth
        </div>

        <!-- Diagnostic readout panel -->
        <div class="mt-14 mx-auto max-w-2xl grid grid-cols-1 sm:grid-cols-3 divide-y sm:divide-y-0 sm:divide-x divide-white/10 border border-white/10 rounded-xl bg-white/5 backdrop-blur-sm font-mono text-xs uppercase tracking-wider text-gray-300">
            <div class="flex items-center justify-center gap-2 py-3 px-2">
                <span class="h-1.5 w-1.5 rounded-full bg-[#6FE0B0]"></span> Genuine Parts
            </div>
            <div class="flex items-center justify-center gap-2 py-3 px-2">
                <span class="h-1.5 w-1.5 rounded-full bg-[#6FE0B0]"></span> Full Diagnostics
            </div>
            <div class="flex items-center justify-center gap-2 py-3 px-2">
                <span class="h-1.5 w-1.5 rounded-full bg-[#6FE0B0]"></span> Warranty Backed
            </div>
        </div>
    </div>
</section>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

    <!-- Primary Services Overview -->
    <section class="py-6 grid md:grid-cols-3 gap-8 text-center mb-20">
        <!-- EXPERT REPAIRS -->
        <div class="group relative p-6 bg-white border border-gray-100 shadow-lg rounded-2xl flex flex-col justify-between hover:shadow-xl hover:-translate-y-1 transition-all duration-300 overflow-hidden">
            <span class="bt-trace absolute top-0 left-0 right-0 h-[3px] bg-[#1F6F54]" aria-hidden="true"></span>
            <div>
                <div class="relative mb-4 rounded-xl overflow-hidden h-48">
                    <img src="{{ asset('images/phone_repair.jpg') }}"
                         onerror="this.onerror=null; this.src='https://placehold.co/600x400/12181F/FFFFFF?text=Expert+Repairs';"
                         class="w-full h-full object-cover" alt="Phone Repair" />
                    <div class="absolute inset-0 bg-[#12181F] mix-blend-multiply opacity-30 group-hover:opacity-0 transition duration-300"></div>
                    <span class="absolute top-3 left-3 font-mono text-[10px] uppercase tracking-widest bg-[#12181F]/80 text-[#6FE0B0] px-2 py-1 rounded-full backdrop-blur-sm">Repairs</span>
                </div>
                <h3 class="font-bold text-2xl mb-2 text-gray-900">Expert Repairs</h3>
                <p class="text-gray-600 mb-6 text-sm">We fix phones, laptops, and consoles using original components with warranty coverage.</p>
            </div>
            <a href="/services/book" class="mt-auto block bg-[#1F6F54] text-white px-6 py-2.5 rounded-xl font-semibold hover:bg-[#175943] transition duration-150">
                Book a Repair
            </a>
        </div>

        <!-- DEVICE SALES -->
        <div class="group relative p-6 bg-white border border-gray-100 shadow-lg rounded-2xl flex flex-col justify-between hover:shadow-xl hover:-translate-y-1 transition-all duration-300 overflow-hidden">
            <span class="bt-trace absolute top-0 left-0 right-0 h-[3px] bg-[#1F6F54]" aria-hidden="true"></span>
            <div>
                <div class="relative mb-4 rounded-xl overflow-hidden h-48">
                    <img src="{{ asset('images/laptop-sales.jpg') }}"
                         onerror="this.onerror=null; this.src='https://placehold.co/600x400/12181F/FFFFFF?text=Device+Sales';"
                         class="w-full h-full object-cover" alt="Device Sales"/>
                    <div class="absolute inset-0 bg-[#12181F] mix-blend-multiply opacity-30 group-hover:opacity-0 transition duration-300"></div>
                    <span class="absolute top-3 left-3 font-mono text-[10px] uppercase tracking-widest bg-[#12181F]/80 text-[#6FE0B0] px-2 py-1 rounded-full backdrop-blur-sm">Sales</span>
                </div>
                <h3 class="font-bold text-2xl mb-2 text-gray-900">Device Sales</h3>
                <p class="text-gray-600 mb-6 text-sm">Buy reliable smartphones, workstation laptops, and tech accessories at competitive prices.</p>
            </div>
            <a href="{{ route('store.index') }}" class="mt-auto block bg-[#1F6F54] text-white px-6 py-2.5 rounded-xl font-semibold hover:bg-[#175943] transition duration-150">
                Visit Store
            </a>
        </div>

        <!-- ICT INSTALLATIONS -->
        <div class="group relative p-6 bg-white border border-gray-100 shadow-lg rounded-2xl flex flex-col justify-between hover:shadow-xl hover:-translate-y-1 transition-all duration-300 overflow-hidden">
            <span class="bt-trace absolute top-0 left-0 right-0 h-[3px] bg-[#1F6F54]" aria-hidden="true"></span>
            <div>
                <div class="relative mb-4 rounded-xl overflow-hidden h-48">
                    <img src="{{ asset('images/tower.jpg') }}"
                         onerror="this.onerror=null; this.src='https://placehold.co/600x400/12181F/FFFFFF?text=ICT+Installations';"
                         class="w-full h-full object-cover" alt="ICT Installation"/>
                    <div class="absolute inset-0 bg-[#12181F] mix-blend-multiply opacity-30 group-hover:opacity-0 transition duration-300"></div>
                    <span class="absolute top-3 left-3 font-mono text-[10px] uppercase tracking-widest bg-[#12181F]/80 text-[#6FE0B0] px-2 py-1 rounded-full backdrop-blur-sm">Infrastructure</span>
                </div>
                <h3 class="font-bold text-2xl mb-2 text-gray-900">ICT Infrastructure</h3>
                <p class="text-gray-600 mb-6 text-sm">Network architecture, CCTV camera installations, and enterprise hardware deployment.</p>
            </div>
            <a href="/contact" class="mt-auto block bg-[#1F6F54] text-white px-6 py-2.5 rounded-xl font-semibold hover:bg-[#175943] transition duration-150">
                Contact Us Now
            </a>
        </div>
    </section>

    {{-- Dynamic Categorized Products Showcase --}}
    @php
        $sections = [
            'Latest Devices' => $devices,
            'Replacement Parts' => $parts,
            'Repair Tools & Kits' => $tools,
        ];
    @endphp

    @foreach($sections as $title => $products)
        @if($products->count() > 0)
            <section class="mb-16">
                <div class="flex justify-between items-end mb-6 border-b border-gray-200 pb-3">
                    <div>
                        <span class="font-mono text-[11px] uppercase tracking-[0.2em] text-[#1F6F54]">Inventory</span>
                        <h2 class="text-2xl font-extrabold text-gray-900 tracking-tight">{{ $title }}</h2>
                    </div>
                    <a href="{{ route('store.index') }}" class="text-[#1F6F54] text-sm font-bold hover:text-[#175943] transition">
                        View All &rarr;
                    </a>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($products as $product)
                        <div class="group relative bg-white border border-gray-100 rounded-xl shadow-md hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300 flex flex-col justify-between overflow-hidden">
                            <span class="bt-trace absolute top-0 left-0 right-0 h-[3px] bg-[#1F6F54] z-10" aria-hidden="true"></span>
                            <div>
                                <a href="{{ route('store.product', $product->id) }}" class="block relative overflow-hidden">
                                    @php
                                        $imagePath = optional($product->images->first())->path ?? 'placeholders/default-product.png';
                                    @endphp
                                    <img src="{{ $imagePath }}"
                                         onerror="this.onerror=null; this.src='https://placehold.co/600x400/eeeeee/333333?text=No+Image';"
                                         alt="{{ $product->name }}"
                                         class="w-full h-48 object-cover group-hover:scale-105 transition duration-300"
                                    >
                                </a>

                                <div class="p-4">
                                    <a href="{{ route('store.product', $product->id) }}"
                                       class="text-base font-bold text-gray-900 hover:text-[#1F6F54] transition line-clamp-1"
                                       title="{{ $product->name }}">
                                        {{ $product->name }}
                                    </a>

                                    <p class="font-mono text-[11px] text-gray-500 mt-1 uppercase tracking-wide">
                                        {{ $product->brand ?? 'Generic' }} &bull; {{ $product->condition ?? 'New' }}
                                    </p>

                                    <p class="text-lg text-[#1F6F54] font-extrabold mt-3">
                                        ₦{{ number_format($product->price) }}
                                    </p>
                                </div>
                            </div>

                            <div class="p-4 pt-0">
                                <form action="{{ route('cart.add', $product->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="w-full bg-[#1F6F54] text-white font-semibold py-2 rounded-lg shadow-sm hover:bg-[#175943] transition flex items-center justify-center space-x-2 text-sm">
                                        <svg class="w-4 h-4 fill-current" viewBox="0 0 1024 1024" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M343.376 717.726a7.984 7.984 0 0 1-7.966-7.388 7.99 7.99 0 0 1 7.374-8.576L945 656.472l61.52-384.42H224.056c-4.422 0-8-3.576-8-7.998a7.994 7.994 0 0 1 8-7.998h791.836a8.01 8.01 0 0 1 7.904 9.264l-63.986 399.918a8.022 8.022 0 0 1-7.312 6.716l-608.514 45.756c-0.202 0.016-0.406 0.016-0.608 0.016zM312.03 719.96a7.988 7.988 0 0 1-7.716-5.922L128.35 58.168a7.99 7.99 0 0 1 5.654-9.794c4.266-1.124 8.654 1.376 9.794 5.654l175.962 655.874a7.994 7.994 0 0 1-5.654 9.794 7.988 7.988 0 0 1-2.076 0.264z"/>
                                        </svg>
                                        <span>Add to Cart</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>
        @endif
    @endforeach

    <!-- DIY Repair Guides Banner -->
    <section class="relative py-14 px-6 text-center bg-[#12181F] rounded-2xl mb-16 shadow-xl overflow-hidden">
        <div class="absolute inset-0 bt-grid-overlay" aria-hidden="true"></div>
        <div class="relative z-10">
            <p class="font-mono text-xs uppercase tracking-[0.25em] text-[#6FE0B0] mb-3">$ search --guides</p>
            <h2 class="text-3xl font-extrabold mb-3 text-white">DIY Repair Guides</h2>
            <p class="text-base text-gray-300 mb-8 max-w-2xl mx-auto">
                Search our technician database for step-by-step assembly guides, motherboard schematics, and common fix solutions.
            </p>
            <a href="/guides" class="inline-block border border-[#6FE0B0]/50 text-[#6FE0B0] px-8 py-3 rounded-full font-bold text-base hover:bg-[#6FE0B0]/10 hover:border-[#6FE0B0] transition duration-150">
                Explore Repair Guides &rarr;
            </a>
        </div>
    </section>

</div>

@endsection