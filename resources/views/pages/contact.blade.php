@extends('layouts.app')

@section('content')

<div class="max-w-7xl mx-auto p-4 sm:p-6 lg:p-8">

    <header class="text-center mb-12">
        <h1 class="text-5xl font-extrabold text-blue-700 mb-4">
            BelliTek Nigeria: Contact & Impact
        </h1>
        <p class="text-xl text-gray-600">Your trusted partner for sustainable and affordable technology solutions.</p>
    </header>

    <div class="lg:grid lg:grid-cols-3 gap-10">

        {{-- 📍 Column 1: Contact Information --}}
        <div class="lg:col-span-1 p-6 bg-white rounded-xl shadow-lg border border-gray-100 mb-8 lg:mb-0 h-fit sticky top-8">
            <h2 class="text-2xl font-bold text-gray-800 mb-6 border-b pb-2">Get in Touch 👋</h2>
            
            <div class="space-y-4 text-lg">
                <p class="flex items-center text-gray-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                    <span class="font-semibold">Location:</span> 📍 Lagos, Nigeria
                </p>
                <p class="flex items-center text-gray-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" /></svg>
                    <span class="font-semibold">Call Us:</span> 📱 07046537824
                </p>
                <p class="flex items-center text-gray-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-600 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.848 5.232a2 2 0 002.304 0L21 8m-2 4v7a2 2 0 01-2 2H7a2 2 0 01-2-2v-7m14-5a2 2 0 00-2-2H7a2 2 0 00-2 2h14z" /></svg>
                    <span class="font-semibold">Email:</span> ✉️ bellitek@gmail.com
                </p>
            </div>
            
        </div>

        {{-- 🌍 Column 2 & 3: Benefits and Impact --}}
        <div class="lg:col-span-2 space-y-10">

            <h2 class="text-3xl font-bold text-gray-800 mb-6 border-b pb-2">Our Impact & Commitment to Nigeria 🇳🇬</h2>

            {{-- Reducing E-Waste --}}
            <div class="p-6 bg-white rounded-xl shadow-lg border border-green-100">
                <h3 class="text-xl font-bold text-green-700 mb-2 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7.707 7.707a2 2 0 010 2.828l-4.243 4.243a2 2 0 01-2.828 0l-7.707-7.707A1.99 1.99 0 013 12V7a4 4 0 014-4z" /></svg>
                    1. Reducing E-Waste & Promoting Sustainability
                </h3>
                <p class="text-gray-600 mb-3">By repairing devices instead of replacing them,  BelliTek  actively supports Nigeria’s push for a greener and more sustainable tech ecosystem.</p>
                <ul class="list-disc list-inside text-gray-600 space-y-1">
                    <li> Reduce  electronic waste.</li>
                    <li> Extend  the lifecycle of gadgets.</li>
                    <li> Educate  consumers on device care.</li>
                </ul>
            </div>

            {{-- Making Technology Affordable --}}
            <div class="p-6 bg-white rounded-xl shadow-lg border border-blue-100">
                <h3 class="text-xl font-bold text-blue-700 mb-2 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c1.657 0 3 .895 3 2s-1.343 2-3 2h-4v4m3-4v4m-6-10H5a2 2 0 00-2 2v10a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-3" /></svg>
                    2. Making Technology Affordable for Nigerians
                </h3>
                <p class="text-gray-600 mb-3">BelliTek reduces the cost of technology ownership, making tech more accessible for  students, SMEs, and workers  by offering:</p>
                <ul class="list-disc list-inside text-gray-600 space-y-1">
                    <li> Affordable  repairs.</li>
                    <li>Budget-friendly  refurbished phones .</li>
                    <li>Cheaper accessories and professional installations at  lower rates .</li>
                </ul>
            </div>

            {{-- Supporting Local SMEs --}}
            <div class="p-6 bg-white rounded-xl shadow-lg border border-yellow-100">
                <h3 class="text-xl font-bold text-yellow-700 mb-2 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4z" /></svg>
                    3. Supporting Local SMEs with ICT Infrastructure
                </h3>
                <p class="text-gray-600 mb-3">We boost the efficiency and productivity of small and medium enterprises by providing essential business infrastructure:</p>
                <ul class="list-disc list-inside text-gray-600 space-y-1">
                    <li> Network setup  and  CCTV installations .</li>
                    <li>Reliable  IT support  and device maintenance.</li>
                    <li>Software upgrades.</li>
                </ul>
            </div>

            {{-- Reliability & Security --}}
            <div class="p-6 bg-white rounded-xl shadow-lg border border-indigo-100">
                <h3 class="text-xl font-bold text-indigo-700 mb-2 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.24c1.29 1.157 1.944 2.895 1.944 4.636s-.654 3.479-1.944 4.636l-4.24 4.24a6 6 0 01-8.486 0L3.394 17.656a6 6 0 010-8.485l4.243-4.243a6 6 0 018.485 0z" /></svg>
                    4. Technology Reliability & Security
                </h3>
                <p class="text-gray-600 mb-3">We build safe IT environments for schools, offices, and shops by ensuring proper installations and minimizing risks:</p>
                <ul class="list-disc list-inside text-gray-600 space-y-1">
                    <li>Reduce  cyber vulnerabilities .</li>
                    <li>Secure company and  customer data .</li>
                    <li>Improve device performance.</li>
                </ul>
            </div>

            {{-- Non-Oil Economy --}}
            <div class="p-6 bg-white rounded-xl shadow-lg border border-gray-200">
                <h3 class="text-xl font-bold text-gray-700 mb-2 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                    5. Contributing to Nigeria’s Non-Oil Economy
                </h3>
                <p class="text-gray-600">BelliTek strengthens Nigeria’s local tech sector and contributes to  GDP growth outside oil  by generating tax revenue, supporting local suppliers, and creating tech-based innovations.</p>
            </div>

        </div>
    </div>
</div>
@endsection