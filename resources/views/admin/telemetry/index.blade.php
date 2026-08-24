@extends('admin.layout')

@section('title', 'Telemetry')

@section('content')

<div class="space-y-6">

    {{-- ============================================================
        INFRASTRUCTURE STATUS
    ============================================================= --}}

    <div>
        <h2 class="text-lg font-bold text-slate-800">
            Infrastructure
        </h2>

        <p class="text-sm text-slate-500">
            Live status of the GeneralClass services.
        </p>
    </div>


    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

        {{-- Java Server --}}
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">

            <div class="flex justify-between items-start">

                <div>
                    <p class="text-sm font-medium text-slate-500 uppercase tracking-wider">
                        Java Server
                    </p>

                    <h3
                        id="java-status"
                        class="text-2xl font-bold text-slate-800 mt-1"
                    >
                        Checking...
                    </h3>
                </div>

                <div
                    id="java-status-icon"
                    class="bg-slate-100 text-slate-500 p-3 rounded-xl"
                >
                    <i class="fas fa-server"></i>
                </div>

            </div>

            <div
                id="java-status-message"
                class="mt-4 flex items-center text-sm text-slate-500"
            >
                <i class="fas fa-circle text-[8px] mr-2"></i>

                <span>
                    Checking Java server...
                </span>
            </div>

        </div>


        {{-- WebSocket --}}
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">

            <div class="flex justify-between items-start">

                <div>
                    <p class="text-sm font-medium text-slate-500 uppercase tracking-wider">
                        WebSocket Server
                    </p>

                    <h3
                        id="websocket-status"
                        class="text-2xl font-bold text-slate-800 mt-1"
                    >
                        Checking...
                    </h3>
                </div>

                <div
                    id="websocket-status-icon"
                    class="bg-slate-100 text-slate-500 p-3 rounded-xl"
                >
                    <i class="fas fa-network-wired"></i>
                </div>

            </div>

            <div
                id="websocket-status-message"
                class="mt-4 flex items-center text-sm text-slate-500"
            >
                <i class="fas fa-circle text-[8px] mr-2"></i>

                <span>
                    Checking WebSocket...
                </span>
            </div>

        </div>


        {{-- Laravel --}}
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">

            <div class="flex justify-between items-start">

                <div>
                    <p class="text-sm font-medium text-slate-500 uppercase tracking-wider">
                        Laravel
                    </p>

                    <h3
                        id="laravel-status"
                        class="text-2xl font-bold text-slate-800 mt-1"
                    >
                        Checking...
                    </h3>
                </div>

                <div
                    id="laravel-status-icon"
                    class="bg-slate-100 text-slate-500 p-3 rounded-xl"
                >
                    <i class="fab fa-laravel"></i>
                </div>

            </div>

            <div
                id="laravel-status-message"
                class="mt-4 flex items-center text-sm text-slate-500"
            >
                <i class="fas fa-circle text-[8px] mr-2"></i>

                <span>
                    Checking Laravel...
                </span>
            </div>

        </div>

    </div>


    {{-- ============================================================
        HOST SYSTEM TELEMETRY
    ============================================================= --}}

    <div class="flex items-center justify-between">

        <div>
            <h2 class="text-lg font-bold text-slate-800">
                Host System Resources
            </h2>

            <p class="text-sm text-slate-500">
                CPU and memory utilization of the server hosting GeneralClass.
            </p>
        </div>

        <span
            id="system-refresh-status"
            class="text-xs text-slate-400"
        >
            Updating...
        </span>

    </div>


    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        {{-- Host CPU --}}
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">

            <div class="flex justify-between items-start">

                <div>
                    <p class="text-sm font-medium text-slate-500 uppercase tracking-wider">
                        Host CPU
                    </p>

                    <h3
                        id="host-cpu"
                        class="text-3xl font-bold text-slate-800 mt-2"
                    >
                        --
                    </h3>
                </div>

                <div class="bg-blue-100 text-blue-600 p-3 rounded-xl">
                    <i class="fas fa-microchip"></i>
                </div>

            </div>

            <div class="mt-5">

                <div class="flex justify-between text-xs text-slate-400 mb-2">
                    <span>CPU utilization</span>
                    <span id="host-cpu-label">--</span>
                </div>

                <div class="w-full bg-slate-100 rounded-full h-2">

                    <div
                        id="host-cpu-bar"
                        class="bg-blue-500 h-2 rounded-full transition-all duration-500"
                        style="width: 0%"
                    ></div>

                </div>

            </div>

        </div>


        {{-- Host Memory --}}
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">

            <div class="flex justify-between items-start">

                <div>
                    <p class="text-sm font-medium text-slate-500 uppercase tracking-wider">
                        Host Memory
                    </p>

                    <h3
                        id="host-memory"
                        class="text-3xl font-bold text-slate-800 mt-2"
                    >
                        --
                    </h3>
                </div>

                <div class="bg-purple-100 text-purple-600 p-3 rounded-xl">
                    <i class="fas fa-memory"></i>
                </div>

            </div>

            <div class="mt-5">

                <div class="flex justify-between text-xs text-slate-400 mb-2">

                    <span id="host-memory-details">
                        --
                    </span>

                    <span id="host-memory-label">
                        --
                    </span>

                </div>

                <div class="w-full bg-slate-100 rounded-full h-2">

                    <div
                        id="host-memory-bar"
                        class="bg-purple-500 h-2 rounded-full transition-all duration-500"
                        style="width: 0%"
                    ></div>

                </div>

            </div>

        </div>

    </div>


    {{-- ============================================================
        DOCKER CONTAINER TELEMETRY
    ============================================================= --}}

    <div>

        <h2 class="text-lg font-bold text-slate-800">
            Docker Containers
        </h2>

        <p class="text-sm text-slate-500">
            Resource utilization of the Laravel and Java containers.
        </p>

    </div>


    <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">


        {{-- ========================================================
            LARAVEL CONTAINER
        ========================================================= --}}

        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">

            <div class="p-6 border-b border-slate-100">

                <div class="flex items-center justify-between">

                    <div class="flex items-center gap-3">

                        <div class="bg-red-100 text-red-600 p-3 rounded-xl">
                            <i class="fab fa-laravel"></i>
                        </div>

                        <div>

                            <h3 class="font-bold text-slate-800">
                                Laravel Container
                            </h3>

                            <p
                                id="laravel-container-name"
                                class="text-xs text-slate-400 mt-1"
                            >
                                Checking...
                            </p>

                        </div>

                    </div>

                    <span
                        id="laravel-container-status"
                        class="px-2 py-1 rounded-full text-xs font-medium bg-slate-100 text-slate-500"
                    >
                        Checking
                    </span>

                </div>

            </div>


            <div class="p-6">

                <div class="grid grid-cols-2 gap-4">

                    {{-- CPU --}}
                    <div class="bg-slate-50 rounded-xl p-4">

                        <p class="text-xs uppercase tracking-wider text-slate-400">
                            CPU
                        </p>

                        <p
                            id="laravel-docker-cpu"
                            class="text-2xl font-bold text-slate-800 mt-1"
                        >
                            --
                        </p>

                    </div>


                    {{-- Memory --}}
                    <div class="bg-slate-50 rounded-xl p-4">

                        <p class="text-xs uppercase tracking-wider text-slate-400">
                            Memory
                        </p>

                        <p
                            id="laravel-docker-memory"
                            class="text-2xl font-bold text-slate-800 mt-1"
                        >
                            --
                        </p>

                    </div>


                    {{-- Memory Usage --}}
                    <div class="bg-slate-50 rounded-xl p-4">

                        <p class="text-xs uppercase tracking-wider text-slate-400">
                            Memory Usage
                        </p>

                        <p
                            id="laravel-docker-memory-usage"
                            class="text-sm font-semibold text-slate-700 mt-1"
                        >
                            --
                        </p>

                    </div>


                    {{-- PIDs --}}
                    <div class="bg-slate-50 rounded-xl p-4">

                        <p class="text-xs uppercase tracking-wider text-slate-400">
                            Processes
                        </p>

                        <p
                            id="laravel-docker-pids"
                            class="text-2xl font-bold text-slate-800 mt-1"
                        >
                            --
                        </p>

                    </div>

                </div>


                {{-- CPU progress --}}
                <div class="mt-6">

                    <div class="flex justify-between text-xs text-slate-400 mb-2">

                        <span>
                            CPU utilization
                        </span>

                        <span id="laravel-docker-cpu-label">
                            --
                        </span>

                    </div>

                    <div class="w-full bg-slate-100 rounded-full h-2">

                        <div
                            id="laravel-docker-cpu-bar"
                            class="bg-red-500 h-2 rounded-full transition-all duration-500"
                            style="width: 0%"
                        ></div>

                    </div>

                </div>


                {{-- Memory progress --}}
                <div class="mt-4">

                    <div class="flex justify-between text-xs text-slate-400 mb-2">

                        <span>
                            Memory limit utilization
                        </span>

                        <span id="laravel-docker-memory-label">
                            --
                        </span>

                    </div>

                    <div class="w-full bg-slate-100 rounded-full h-2">

                        <div
                            id="laravel-docker-memory-bar"
                            class="bg-purple-500 h-2 rounded-full transition-all duration-500"
                            style="width: 0%"
                        ></div>

                    </div>

                </div>

            </div>

        </div>


        {{-- ========================================================
            JAVA CONTAINER
        ========================================================= --}}

        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">

            <div class="p-6 border-b border-slate-100">

                <div class="flex items-center justify-between">

                    <div class="flex items-center gap-3">

                        <div class="bg-orange-100 text-orange-600 p-3 rounded-xl">
                            <i class="fab fa-java"></i>
                        </div>

                        <div>

                            <h3 class="font-bold text-slate-800">
                                Java Container
                            </h3>

                            <p
                                id="java-container-name"
                                class="text-xs text-slate-400 mt-1"
                            >
                                Checking...
                            </p>

                        </div>

                    </div>

                    <span
                        id="java-container-status"
                        class="px-2 py-1 rounded-full text-xs font-medium bg-slate-100 text-slate-500"
                    >
                        Checking
                    </span>

                </div>

            </div>


            <div class="p-6">

                <div class="grid grid-cols-2 gap-4">

                    {{-- CPU --}}
                    <div class="bg-slate-50 rounded-xl p-4">

                        <p class="text-xs uppercase tracking-wider text-slate-400">
                            CPU
                        </p>

                        <p
                            id="java-docker-cpu"
                            class="text-2xl font-bold text-slate-800 mt-1"
                        >
                            --
                        </p>

                    </div>


                    {{-- Memory --}}
                    <div class="bg-slate-50 rounded-xl p-4">

                        <p class="text-xs uppercase tracking-wider text-slate-400">
                            Memory
                        </p>

                        <p
                            id="java-docker-memory"
                            class="text-2xl font-bold text-slate-800 mt-1"
                        >
                            --
                        </p>

                    </div>


                    {{-- Memory Usage --}}
                    <div class="bg-slate-50 rounded-xl p-4">

                        <p class="text-xs uppercase tracking-wider text-slate-400">
                            Memory Usage
                        </p>

                        <p
                            id="java-docker-memory-usage"
                            class="text-sm font-semibold text-slate-700 mt-1"
                        >
                            --
                        </p>

                    </div>


                    {{-- PIDs --}}
                    <div class="bg-slate-50 rounded-xl p-4">

                        <p class="text-xs uppercase tracking-wider text-slate-400">
                            Processes
                        </p>

                        <p
                            id="java-docker-pids"
                            class="text-2xl font-bold text-slate-800 mt-1"
                        >
                            --
                        </p>

                    </div>

                </div>


                {{-- CPU progress --}}
                <div class="mt-6">

                    <div class="flex justify-between text-xs text-slate-400 mb-2">

                        <span>
                            CPU utilization
                        </span>

                        <span id="java-docker-cpu-label">
                            --
                        </span>

                    </div>

                    <div class="w-full bg-slate-100 rounded-full h-2">

                        <div
                            id="java-docker-cpu-bar"
                            class="bg-orange-500 h-2 rounded-full transition-all duration-500"
                            style="width: 0%"
                        ></div>

                    </div>

                </div>


                {{-- Memory progress --}}
                <div class="mt-4">

                    <div class="flex justify-between text-xs text-slate-400 mb-2">

                        <span>
                            Memory limit utilization
                        </span>

                        <span id="java-docker-memory-label">
                            --
                        </span>

                    </div>

                    <div class="w-full bg-slate-100 rounded-full h-2">

                        <div
                            id="java-docker-memory-bar"
                            class="bg-purple-500 h-2 rounded-full transition-all duration-500"
                            style="width: 0%"
                        ></div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ============================================================
        LIVE STATISTICS
    ============================================================= --}}

    <div>

        <h2 class="text-lg font-bold text-slate-800">
            Live Usage
        </h2>

        <p class="text-sm text-slate-500">
            Current WebSocket usage across the platform.
        </p>

    </div>


    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-6">

        {{-- Connections --}}
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">

            <p class="text-sm font-medium text-slate-500">
                Active Connections
            </p>

            <h3
                id="active-connections"
                class="text-3xl font-bold text-slate-800 mt-2"
            >
                --
            </h3>

            <p class="text-xs text-slate-400 mt-2">
                Currently connected
            </p>

        </div>


        {{-- Students --}}
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">

            <p class="text-sm font-medium text-slate-500">
                Students Online
            </p>

            <h3
                id="active-students"
                class="text-3xl font-bold text-slate-800 mt-2"
            >
                --
            </h3>

            <p class="text-xs text-slate-400 mt-2">
                Active student connections
            </p>

        </div>


        {{-- Lecturers --}}
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">

            <p class="text-sm font-medium text-slate-500">
                Lecturers Online
            </p>

            <h3
                id="active-lecturers"
                class="text-3xl font-bold text-slate-800 mt-2"
            >
                --
            </h3>

            <p class="text-xs text-slate-400 mt-2">
                Active lecturer connections
            </p>

        </div>


        {{-- Classrooms --}}
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">

            <p class="text-sm font-medium text-slate-500">
                Active Classrooms
            </p>

            <h3
                id="active-classrooms"
                class="text-3xl font-bold text-slate-800 mt-2"
            >
                --
            </h3>

            <p class="text-xs text-slate-400 mt-2">
                Classrooms currently in use
            </p>

        </div>


        {{-- Connection Rate --}}
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">

            <p class="text-sm font-medium text-slate-500">
                Connection Rate
            </p>

            <h3
                id="connection-rate"
                class="text-3xl font-bold text-slate-800 mt-2"
            >
                --
            </h3>

            <p class="text-xs text-slate-400 mt-2">
                New connections / minute
            </p>

        </div>

    </div>


    {{-- ============================================================
        ERRORS
    ============================================================= --}}

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        {{-- Rejected --}}
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">

            <div class="flex items-center justify-between">

                <div>

                    <p class="text-sm font-medium text-slate-500">
                        Rejected Connections
                    </p>

                    <h3
                        id="rejected-connections"
                        class="text-3xl font-bold text-red-600 mt-2"
                    >
                        --
                    </h3>

                    <p class="text-xs text-slate-400 mt-2">
                        Today
                    </p>

                </div>

                <div class="bg-red-100 text-red-600 p-3 rounded-xl">
                    <i class="fas fa-ban"></i>
                </div>

            </div>

        </div>


        {{-- Errors Last Hour --}}
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">

            <div class="flex items-center justify-between">

                <div>

                    <p class="text-sm font-medium text-slate-500">
                        Errors
                    </p>

                    <h3
                        id="errors-last-hour"
                        class="text-3xl font-bold text-red-600 mt-2"
                    >
                        --
                    </h3>

                    <p class="text-xs text-slate-400 mt-2">
                        Rejected connections in the last hour
                    </p>

                </div>

                <div class="bg-red-100 text-red-600 p-3 rounded-xl">
                    <i class="fas fa-triangle-exclamation"></i>
                </div>

            </div>

        </div>

    </div>

    {{-- ============================================================
        ERROR TELEMETRY
    ============================================================= --}}

    <div>

        <h2 class="text-lg font-bold text-slate-800">
            Application Errors
        </h2>

        <p class="text-sm text-slate-500">
            Recent application and infrastructure errors.
        </p>

    </div>


    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">

    <div class="p-6 border-b border-slate-100">

        <div class="flex justify-between items-center">

            <div>

                <h3 class="font-bold text-slate-800">
                    Recent Errors
                </h3>

                <p class="text-sm text-slate-500 mt-1">
                    Latest application errors captured by telemetry.
                </p>

            </div>

            <span
                id="error-refresh-status"
                class="text-xs text-slate-400"
            >
                Updating...
            </span>

        </div>

    </div>

    {{-- ============================================================
    SECURITY TELEMETRY
============================================================= --}}

<div>
    <div class="flex items-center justify-between">

        <div>
            <h2 class="text-lg font-bold text-slate-800">
                Security Telemetry
            </h2>

            <p class="text-sm text-slate-500">
                Security events detected across Laravel and Java/WebSocket services.
            </p>
        </div>

        <a
            href="{{ route('admin.telemetry.security.index') }}"
            class="inline-flex items-center px-4 py-2
                   bg-slate-800 text-white text-sm font-medium
                   rounded-lg hover:bg-slate-700 transition"
        >
            View Security Dashboard

            <i class="fas fa-arrow-right ml-2"></i>
        </a>

    </div>
</div>


<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">

    {{-- Login Failures --}}
    <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">

        <div class="flex items-center justify-between">

            <div>

                <p class="text-sm font-medium text-slate-500">
                    Login Failures
                </p>

                <h3
                    id="security-login-failures"
                    class="text-3xl font-bold text-red-600 mt-2"
                >
                    --
                </h3>

                <p class="text-xs text-slate-400 mt-2">
                    Last hour
                </p>

            </div>

            <div class="bg-red-100 text-red-600 p-3 rounded-xl">
                <i class="fas fa-right-to-bracket"></i>
            </div>

        </div>

    </div>


    {{-- Rate Limited --}}
    <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">

        <div class="flex items-center justify-between">

            <div>

                <p class="text-sm font-medium text-slate-500">
                    Rate Limited
                </p>

                <h3
                    id="security-rate-limited"
                    class="text-3xl font-bold text-orange-600 mt-2"
                >
                    --
                </h3>

                <p class="text-xs text-slate-400 mt-2">
                    Last hour
                </p>

            </div>

            <div class="bg-orange-100 text-orange-600 p-3 rounded-xl">
                <i class="fas fa-gauge-high"></i>
            </div>

        </div>

    </div>


    {{-- Unauthorized --}}
    <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">

        <div class="flex items-center justify-between">

            <div>

                <p class="text-sm font-medium text-slate-500">
                    Unauthorized
                </p>

                <h3
                    id="security-unauthorized"
                    class="text-3xl font-bold text-yellow-600 mt-2"
                >
                    --
                </h3>

                <p class="text-xs text-slate-400 mt-2">
                    Last hour
                </p>

            </div>

            <div class="bg-yellow-100 text-yellow-600 p-3 rounded-xl">
                <i class="fas fa-user-lock"></i>
            </div>

        </div>

    </div>


    {{-- WebSocket Rejections --}}
    <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">

        <div class="flex items-center justify-between">

            <div>

                <p class="text-sm font-medium text-slate-500">
                    WebSocket Rejections
                </p>

                <h3
                    id="security-websocket-rejections"
                    class="text-3xl font-bold text-red-600 mt-2"
                >
                    --
                </h3>

                <p class="text-xs text-slate-400 mt-2">
                    Last hour
                </p>

            </div>

                <div class="bg-red-100 text-red-600 p-3 rounded-xl">
                    <i class="fas fa-plug-circle-xmark"></i>
                </div>

            </div>

        </div>

    </div>

    <div class="overflow-x-auto">

        <table class="w-full text-left">

            <thead class="bg-slate-50 text-slate-400 text-xs uppercase">

                <tr>

                    <th class="px-6 py-3">
                        Level
                    </th>

                    <th class="px-6 py-3">
                        Source
                    </th>

                    <th class="px-6 py-3">
                        Type
                    </th>

                    <th class="px-6 py-3">
                        Message
                    </th>

                    <th class="px-6 py-3">
                        User
                    </th>

                    <th class="px-6 py-3">
                        Time
                    </th>

                </tr>

            </thead>

            <tbody
                id="errors-table"
                class="divide-y divide-slate-100"
            >

                <tr>

                    <td
                        colspan="6"
                        class="px-6 py-10 text-center text-slate-400"
                    >
                        Loading errors...
                    </td>

                </tr>

            </tbody>

        </table>

        </div>

    </div>

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">

    {{-- Last Hour --}}
    <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">

        <p class="text-sm font-medium text-slate-500">
            Errors — Last Hour
        </p>

        <h3
            id="errors-last-hour"
            class="text-3xl font-bold text-slate-800 mt-2"
        >
            --
        </h3>

    </div>


    {{-- Today --}}
    <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">

        <p class="text-sm font-medium text-slate-500">
            Errors Today
        </p>

        <h3
            id="errors-today"
            class="text-3xl font-bold text-slate-800 mt-2"
        >
            --
        </h3>

    </div>


    {{-- Critical --}}
    <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">

        <p class="text-sm font-medium text-slate-500">
            Critical
        </p>

        <h3
            id="critical-errors"
            class="text-3xl font-bold text-red-600 mt-2"
        >
            --
        </h3>

    </div>


    {{-- Warnings --}}
    <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">

        <p class="text-sm font-medium text-slate-500">
            Warnings
        </p>

        <h3
            id="warning-errors"
            class="text-3xl font-bold text-amber-600 mt-2"
        >
            --
        </h3>

    </div>

</div>

    {{-- ============================================================
        ACTIVE CONNECTIONS TABLE
    ============================================================= --}}

    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">

        <div class="p-6 border-b border-slate-100">

            <div class="flex justify-between items-center">

                <div>

                    <h3 class="font-bold text-slate-800">
                        Active WebSocket Connections
                    </h3>

                    <p class="text-sm text-slate-500 mt-1">
                        Users currently connected to the classroom server.
                    </p>

                </div>

                <span
                    id="connection-refresh-status"
                    class="text-xs text-slate-400"
                >
                    Updating...
                </span>

            </div>

        </div>


        <div class="overflow-x-auto">

            <table class="w-full text-left">

                <thead class="bg-slate-50 text-slate-400 text-xs uppercase">

                    <tr>

                        <th class="px-6 py-3">
                            User
                        </th>

                        <th class="px-6 py-3">
                            Role
                        </th>

                        <th class="px-6 py-3">
                            Classroom
                        </th>

                        <th class="px-6 py-3">
                            IP Address
                        </th>

                        <th class="px-6 py-3">
                            Connected
                        </th>

                    </tr>

                </thead>

                <tbody
                    id="connections-table"
                    class="divide-y divide-slate-100"
                >

                    <tr>

                        <td
                            colspan="5"
                            class="px-6 py-10 text-center text-slate-400"
                        >
                            Loading connections...
                        </td>

                    </tr>

                </tbody>

            </table>

        </div>

    </div>

    {{-- Security Source Summary --}}

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        {{-- Laravel --}}
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">

            <div class="flex items-center justify-between">

                <div>

                    <p class="text-sm font-medium text-slate-500 uppercase tracking-wider">
                        Laravel Security Events
                    </p>

                    <h3
                        id="security-laravel-events"
                        class="text-3xl font-bold text-slate-800 mt-2"
                    >
                        --
                    </h3>

                    <p class="text-xs text-slate-400 mt-2">
                        Last hour
                    </p>

                </div>

                <div class="bg-indigo-100 text-indigo-600 p-3 rounded-xl">
                    <i class="fab fa-laravel"></i>
                </div>

            </div>

        </div>


        {{-- Java --}}
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">

            <div class="flex items-center justify-between">

                <div>

                    <p class="text-sm font-medium text-slate-500 uppercase tracking-wider">
                        Java Security Events
                    </p>

                    <h3
                        id="security-java-events"
                        class="text-3xl font-bold text-slate-800 mt-2"
                    >
                        --
                    </h3>

                    <p class="text-xs text-slate-400 mt-2">
                        Last hour
                    </p>

                </div>

                <div class="bg-blue-100 text-blue-600 p-3 rounded-xl">
                    <i class="fas fa-server"></i>
                </div>

            </div>

        </div>

    </div>

    {{-- ============================================================
        SECURITY BLACKLIST
    ============================================================= --}}

    <div>

        <div class="flex items-center justify-between">

            <div>

                <h2 class="text-lg font-bold text-slate-800">
                    Security Blacklist
                </h2>

                <p class="text-sm text-slate-500">
                    Manage IP addresses that are blocked from accessing GeneralClass.
                </p>

            </div>

            <a
                href="{{ route('admin.telemetry.security.blacklist.index') }}"
                class="inline-flex items-center px-4 py-2
                    bg-red-600 text-white text-sm font-medium
                    rounded-lg hover:bg-red-700 transition"
            >
                Manage Blacklist

                <i class="fas fa-arrow-right ml-2"></i>
            </a>

        </div>

    </div>


    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

        {{-- Active Blacklisted IPs --}}
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">

            <div class="flex items-center justify-between">

                <div>

                    <p class="text-sm font-medium text-slate-500">
                        Active Blacklisted IPs
                    </p>

                    <h3
                        id="blacklist-active-count"
                        class="text-3xl font-bold text-red-600 mt-2"
                    >
                        --
                    </h3>

                    <p class="text-xs text-slate-400 mt-2">
                        Currently blocked
                    </p>

                </div>

                <div class="bg-red-100 text-red-600 p-3 rounded-xl">
                    <i class="fas fa-ban"></i>
                </div>

            </div>

        </div>


        {{-- Security Action --}}
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">

            <div class="flex items-center justify-between">

                <div>

                    <p class="text-sm font-medium text-slate-500">
                        Access Control
                    </p>

                    <h3 class="text-lg font-bold text-slate-800 mt-2">
                        IP Blocking
                    </h3>

                    <p class="text-xs text-slate-400 mt-2">
                        Block malicious or abusive addresses.
                    </p>

                </div>

                <div class="bg-orange-100 text-orange-600 p-3 rounded-xl">
                    <i class="fas fa-shield-halved"></i>
                </div>

            </div>

        </div>


        {{-- Manage Blacklist --}}
        <div class="bg-slate-800 p-6 rounded-2xl shadow-sm">

            <div class="flex items-center justify-between">

                <div>

                    <p class="text-sm font-medium text-slate-300">
                        Security Controls
                    </p>

                    <h3 class="text-lg font-bold text-white mt-2">
                        Manage Blocked IPs
                    </h3>

                    <p class="text-xs text-slate-400 mt-2">
                        Activate, deactivate or permanently remove IPs.
                    </p>

                </div>

                <div class="bg-white/10 text-white p-3 rounded-xl">
                    <i class="fas fa-list-check"></i>
                </div>

            </div>

            <a
                href="{{ route('admin.telemetry.security.blacklist.index') }}"
                class="inline-flex items-center mt-5 px-4 py-2
                    bg-white text-slate-800 text-sm font-medium
                    rounded-lg hover:bg-slate-100 transition"
            >
                Open Blacklist

                <i class="fas fa-arrow-right ml-2"></i>
            </a>

        </div>

    </div>
    {{-- ============================================================
        RECENT EVENTS
    ============================================================= --}}

    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">

        <div class="p-6 border-b border-slate-100">

            <h3 class="font-bold text-slate-800">
                Recent WebSocket Events
            </h3>

            <p class="text-sm text-slate-500 mt-1">
                Latest connection activity reported by the Java server.
            </p>

        </div>


        <div class="overflow-x-auto">

            <table class="w-full text-left">

                <thead class="bg-slate-50 text-slate-400 text-xs uppercase">

                    <tr>

                        <th class="px-6 py-3">
                            Event
                        </th>

                        <th class="px-6 py-3">
                            User
                        </th>

                        <th class="px-6 py-3">
                            Role
                        </th>

                        <th class="px-6 py-3">
                            Classroom
                        </th>

                        <th class="px-6 py-3">
                            IP
                        </th>

                        <th class="px-6 py-3">
                            Time
                        </th>

                    </tr>

                </thead>

                <tbody
                    id="events-table"
                    class="divide-y divide-slate-100"
                >

                    <tr>

                        <td
                            colspan="6"
                            class="px-6 py-10 text-center text-slate-400"
                        >
                            Loading events...
                        </td>

                    </tr>

                </tbody>

            </table>

        </div>

    </div>

</div>


<script>

document.addEventListener('DOMContentLoaded', () => {

    /*
    |--------------------------------------------------------------------------
    | ROUTES
    |--------------------------------------------------------------------------
    */

    const routes = {

        javaHealth:
            @json(route('admin.telemetry.java-health')),

        websocketConfig:
            @json(route('admin.telemetry.websocket-config')),

        websocketStats:
            @json(route('admin.telemetry.websocket-stats')),

        websocketConnections:
            @json(route('admin.telemetry.websocket-connections')),

        websocketEvents:
            @json(route('admin.telemetry.websocket-events')),

        systemTelemetry:
            @json(route('admin.telemetry.system')),

        errorStats:
            @json(route('admin.telemetry.error-stats')),

        errors:
            @json(route('admin.telemetry.errors')),

        securityStats:
            @json(route('admin.telemetry.security.stats')),

        blacklist:
            @json(route('admin.telemetry.security.blacklist.index')),

        blacklistList:
            @json(route('admin.telemetry.security.blacklist.list')),
    };


    /*
    |--------------------------------------------------------------------------
    | FETCH HELPER
    |--------------------------------------------------------------------------
    */

    async function fetchJson(url) {

        const response = await fetch(url, {

            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
            },

            credentials: 'same-origin',

        });

        if (!response.ok) {
            throw new Error(`HTTP ${response.status}`);
        }

        return await response.json();
    }


    /*
    |--------------------------------------------------------------------------
    | JAVA SERVER HEALTH
    |--------------------------------------------------------------------------
    */

    async function checkJavaHealth() {

        const status =
            document.getElementById('java-status');

        const message =
            document.getElementById('java-status-message');

        const icon =
            document.getElementById('java-status-icon');

        try {

            const data =
                await fetchJson(routes.javaHealth);

            const healthy =
                data.connected === true &&
                data.status === 'operational';

            if (healthy) {

                status.textContent =
                    'Operational';

                status.className =
                    'text-2xl font-bold text-emerald-600 mt-1';

                icon.className =
                    'bg-emerald-100 text-emerald-600 p-3 rounded-xl';

                message.className =
                    'mt-4 flex items-center text-sm text-emerald-600';

                message.innerHTML =
                    '<i class="fas fa-circle text-[8px] mr-2"></i>' +
                    '<span>Java server is operational</span>';

            } else {

                setJavaDown();
            }

        } catch (error) {

            console.error(
                'Java health check failed:',
                error
            );

            setJavaDown();
        }
    }


    function setJavaDown() {

        const status =
            document.getElementById('java-status');

        const message =
            document.getElementById('java-status-message');

        const icon =
            document.getElementById('java-status-icon');

        status.textContent =
            'Offline';

        status.className =
            'text-2xl font-bold text-red-600 mt-1';

        icon.className =
            'bg-red-100 text-red-600 p-3 rounded-xl';

        message.className =
            'mt-4 flex items-center text-sm text-red-600';

        message.innerHTML =
            '<i class="fas fa-circle text-[8px] mr-2"></i>' +
            '<span>Java server is unavailable</span>';
    }


    /*
    |--------------------------------------------------------------------------
    | WEBSOCKET HEALTH
    |--------------------------------------------------------------------------
    */

    let websocketUrl = null;


    async function loadWebSocketConfig() {

        try {

            const data =
                await fetchJson(
                    routes.websocketConfig
                );

            websocketUrl =
                data.websocket_url;

            checkWebSocketHealth();

        } catch (error) {

            console.error(
                'Could not load WebSocket configuration:',
                error
            );

            setWebSocketDown();
        }
    }


    async function checkWebSocketHealth() {

        if (!websocketUrl) {

            setWebSocketDown();

            return;
        }


        return new Promise((resolve) => {

            let finished = false;

            const socket =
                new WebSocket(websocketUrl);


            const timeout =
                setTimeout(() => {

                    if (finished) return;

                    finished = true;

                    try {
                        socket.close();
                    } catch (e) {}

                    setWebSocketDown();

                    resolve(false);

                }, 5000);


            socket.onopen = () => {

                if (finished) return;

                finished = true;

                clearTimeout(timeout);

                setWebSocketOperational();

                socket.close();

                resolve(true);
            };


            socket.onerror = () => {

                if (finished) return;

                finished = true;

                clearTimeout(timeout);

                setWebSocketDown();

                resolve(false);
            };


            socket.onclose = () => {

                if (finished) return;

                finished = true;

                clearTimeout(timeout);

                setWebSocketDown();

                resolve(false);
            };

        });
    }


    function setWebSocketOperational() {

        const status =
            document.getElementById(
                'websocket-status'
            );

        const message =
            document.getElementById(
                'websocket-status-message'
            );

        const icon =
            document.getElementById(
                'websocket-status-icon'
            );

        status.textContent =
            'Operational';

        status.className =
            'text-2xl font-bold text-emerald-600 mt-1';

        icon.className =
            'bg-emerald-100 text-emerald-600 p-3 rounded-xl';

        message.className =
            'mt-4 flex items-center text-sm text-emerald-600';

        message.innerHTML =
            '<i class="fas fa-circle text-[8px] mr-2"></i>' +
            '<span>WebSocket server is functional</span>';
    }


    function setWebSocketDown() {

        const status =
            document.getElementById(
                'websocket-status'
            );

        const message =
            document.getElementById(
                'websocket-status-message'
            );

        const icon =
            document.getElementById(
                'websocket-status-icon'
            );

        status.textContent =
            'Offline';

        status.className =
            'text-2xl font-bold text-red-600 mt-1';

        icon.className =
            'bg-red-100 text-red-600 p-3 rounded-xl';

        message.className =
            'mt-4 flex items-center text-sm text-red-600';

        message.innerHTML =
            '<i class="fas fa-circle text-[8px] mr-2"></i>' +
            '<span>WebSocket server is unavailable</span>';
    }


    /*
    |--------------------------------------------------------------------------
    | SYSTEM TELEMETRY
    |--------------------------------------------------------------------------
    */

    async function loadSystemTelemetry() {

        try {

            const data =
                await fetchJson(
                    routes.systemTelemetry
                );

            updateLaravelStatus(
                data.laravel
            );

            updateHostTelemetry(
                data.system?.host
            );

            updateDockerContainer(
                'laravel',
                data.system?.docker?.laravel
            );

            updateDockerContainer(
                'java',
                data.system?.docker?.java
            );

            updateConnectionTelemetry(
                data.connections
            );

            updateErrorTelemetry(
                data.errors
            );

            document.getElementById(
                'system-refresh-status'
            ).textContent =
                `Updated ${new Date().toLocaleTimeString()}`;

        } catch (error) {

            console.error(
                'System telemetry failed:',
                error
            );

            document.getElementById(
                'system-refresh-status'
            ).textContent =
                'Telemetry unavailable';

            setLaravelDown();

            setHostTelemetryUnavailable();

            setDockerUnavailable('laravel');

            setDockerUnavailable('java');
        }
    }


    /*
    |--------------------------------------------------------------------------
    | LARAVEL STATUS
    |--------------------------------------------------------------------------
    */

    function updateLaravelStatus(data) {

        const status =
            document.getElementById(
                'laravel-status'
            );

        const message =
            document.getElementById(
                'laravel-status-message'
            );

        const icon =
            document.getElementById(
                'laravel-status-icon'
            );

        if (
            data &&
            data.status === 'operational'
        ) {

            status.textContent =
                'Operational';

            status.className =
                'text-2xl font-bold text-emerald-600 mt-1';

            icon.className =
                'bg-emerald-100 text-emerald-600 p-3 rounded-xl';

            message.className =
                'mt-4 flex items-center text-sm text-emerald-600';

            message.innerHTML =
                '<i class="fas fa-circle text-[8px] mr-2"></i>' +
                `<span>Laravel is operational (${escapeHtml(data.environment ?? 'unknown')})</span>`;

        } else {

            setLaravelDown();
        }
    }


    function setLaravelDown() {

        const status =
            document.getElementById(
                'laravel-status'
            );

        const message =
            document.getElementById(
                'laravel-status-message'
            );

        const icon =
            document.getElementById(
                'laravel-status-icon'
            );

        status.textContent =
            'Unavailable';

        status.className =
            'text-2xl font-bold text-red-600 mt-1';

        icon.className =
            'bg-red-100 text-red-600 p-3 rounded-xl';

        message.className =
            'mt-4 flex items-center text-sm text-red-600';

        message.innerHTML =
            '<i class="fas fa-circle text-[8px] mr-2"></i>' +
            '<span>Laravel telemetry unavailable</span>';
    }


    /*
    |--------------------------------------------------------------------------
    | HOST TELEMETRY
    |--------------------------------------------------------------------------
    */

    function updateHostTelemetry(host) {

        if (!host) {

            setHostTelemetryUnavailable();

            return;
        }


        const cpu =
            toNumber(host.cpu);

        const memory =
            host.memory ?? {};


        /*
        | CPU
        */

        if (cpu !== null) {

            const cpuValue =
                clamp(cpu, 0, 100);

            document.getElementById(
                'host-cpu'
            ).textContent =
                `${formatNumber(cpuValue)}%`;

            document.getElementById(
                'host-cpu-label'
            ).textContent =
                `${formatNumber(cpuValue)}%`;

            document.getElementById(
                'host-cpu-bar'
            ).style.width =
                `${cpuValue}%`;

        } else {

            document.getElementById(
                'host-cpu'
            ).textContent = '--';

            document.getElementById(
                'host-cpu-label'
            ).textContent = '--';

            document.getElementById(
                'host-cpu-bar'
            ).style.width = '0%';
        }


        /*
        | Memory
        */

        const memoryPercent =
            toNumber(memory.used_percent);

        if (memoryPercent !== null) {

            const memoryValue =
                clamp(memoryPercent, 0, 100);

            document.getElementById(
                'host-memory'
            ).textContent =
                `${formatNumber(memoryValue)}%`;

            document.getElementById(
                'host-memory-label'
            ).textContent =
                `${formatNumber(memoryValue)}%`;

            document.getElementById(
                'host-memory-bar'
            ).style.width =
                `${memoryValue}%`;

        } else {

            document.getElementById(
                'host-memory'
            ).textContent = '--';

            document.getElementById(
                'host-memory-label'
            ).textContent = '--';

            document.getElementById(
                'host-memory-bar'
            ).style.width = '0%';
        }


        const usedMb =
            toNumber(memory.used_mb);

        const totalMb =
            toNumber(memory.total_mb);

        const freeMb =
            toNumber(memory.free_mb);


        if (
            usedMb !== null &&
            totalMb !== null
        ) {

            document.getElementById(
                'host-memory-details'
            ).textContent =
                `${formatMemory(usedMb)} / ${formatMemory(totalMb)}`;

        } else {

            document.getElementById(
                'host-memory-details'
            ).textContent = '--';
        }

    }


    function setHostTelemetryUnavailable() {

        document.getElementById(
            'host-cpu'
        ).textContent = '--';

        document.getElementById(
            'host-cpu-label'
        ).textContent = 'Unavailable';

        document.getElementById(
            'host-cpu-bar'
        ).style.width = '0%';


        document.getElementById(
            'host-memory'
        ).textContent = '--';

        document.getElementById(
            'host-memory-label'
        ).textContent = 'Unavailable';

        document.getElementById(
            'host-memory-details'
        ).textContent = '--';

        document.getElementById(
            'host-memory-bar'
        ).style.width = '0%';
    }


    /*
    |--------------------------------------------------------------------------
    | DOCKER TELEMETRY
    |--------------------------------------------------------------------------
    */

    function updateDockerContainer(
        type,
        container
    ) {

        if (
            !container ||
            container.available !== true
        ) {

            setDockerUnavailable(type);

            return;
        }


        const prefix =
            type === 'laravel'
                ? 'laravel'
                : 'java';


        const cpu =
            toNumber(container.cpu_percent);

        const memory =
            container.memory ?? {};

        const memoryPercent =
            toNumber(memory.usage_percent);

        const usageMb =
            toNumber(memory.usage_mb);

        const limitMb =
            toNumber(memory.limit_mb);

        const pids =
            container.pids;


        /*
        | Container name
        */

        document.getElementById(
            `${prefix}-container-name`
        ).textContent =
            container.container ?? 'Unknown container';


        /*
        | Status
        */

        const status =
            document.getElementById(
                `${prefix}-container-status`
            );

        status.textContent =
            'Running';

        status.className =
            'px-2 py-1 rounded-full text-xs font-medium bg-emerald-100 text-emerald-700';


        /*
        | CPU
        */

        if (cpu !== null) {

            document.getElementById(
                `${prefix}-docker-cpu`
            ).textContent =
                `${formatNumber(cpu)}%`;

            document.getElementById(
                `${prefix}-docker-cpu-label`
            ).textContent =
                `${formatNumber(cpu)}%`;

            /*
            Docker CPU can theoretically exceed
            100% when multiple CPU cores are used.
            For the visual progress bar we cap it.
            */

            document.getElementById(
                `${prefix}-docker-cpu-bar`
            ).style.width =
                `${clamp(cpu, 0, 100)}%`;

        } else {

            document.getElementById(
                `${prefix}-docker-cpu`
            ).textContent = '--';

            document.getElementById(
                `${prefix}-docker-cpu-label`
            ).textContent = '--';

            document.getElementById(
                `${prefix}-docker-cpu-bar`
            ).style.width = '0%';
        }


        /*
        | Memory percentage
        */

        if (memoryPercent !== null) {

            document.getElementById(
                `${prefix}-docker-memory`
            ).textContent =
                `${formatNumber(memoryPercent)}%`;

            document.getElementById(
                `${prefix}-docker-memory-label`
            ).textContent =
                `${formatNumber(memoryPercent)}%`;

            document.getElementById(
                `${prefix}-docker-memory-bar`
            ).style.width =
                `${clamp(memoryPercent, 0, 100)}%`;

        } else {

            document.getElementById(
                `${prefix}-docker-memory`
            ).textContent = '--';

            document.getElementById(
                `${prefix}-docker-memory-label`
            ).textContent = '--';

            document.getElementById(
                `${prefix}-docker-memory-bar`
            ).style.width = '0%';
        }


        /*
        | Memory usage / limit
        */

        if (usageMb !== null) {

            if (limitMb !== null) {

                document.getElementById(
                    `${prefix}-docker-memory-usage`
                ).textContent =
                    `${formatMemory(usageMb)} / ${formatMemory(limitMb)}`;

            } else {

                document.getElementById(
                    `${prefix}-docker-memory-usage`
                ).textContent =
                    formatMemory(usageMb);
            }

        } else {

            document.getElementById(
                `${prefix}-docker-memory-usage`
            ).textContent = '--';
        }


        /*
        | PIDs
        */

        document.getElementById(
            `${prefix}-docker-pids`
        ).textContent =
            pids !== null && pids !== undefined
                ? pids
                : '--';
    }


    function setDockerUnavailable(type) {

        const prefix =
            type === 'laravel'
                ? 'laravel'
                : 'java';


        document.getElementById(
            `${prefix}-container-name`
        ).textContent =
            'Unavailable';


        const status =
            document.getElementById(
                `${prefix}-container-status`
            );

        status.textContent =
            'Unavailable';

        status.className =
            'px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-700';


        document.getElementById(
            `${prefix}-docker-cpu`
        ).textContent = '--';

        document.getElementById(
            `${prefix}-docker-cpu-label`
        ).textContent = 'Unavailable';

        document.getElementById(
            `${prefix}-docker-cpu-bar`
        ).style.width = '0%';


        document.getElementById(
            `${prefix}-docker-memory`
        ).textContent = '--';

        document.getElementById(
            `${prefix}-docker-memory-label`
        ).textContent = 'Unavailable';

        document.getElementById(
            `${prefix}-docker-memory-bar`
        ).style.width = '0%';


        document.getElementById(
            `${prefix}-docker-memory-usage`
        ).textContent = '--';


        document.getElementById(
            `${prefix}-docker-pids`
        ).textContent = '--';
    }


    /*
    |--------------------------------------------------------------------------
    | CONNECTION TELEMETRY
    |--------------------------------------------------------------------------
    */

    function updateConnectionTelemetry(
        connections
    ) {

        if (!connections) {
            return;
        }


        document.getElementById(
            'connection-rate'
        ).textContent =
            connections.rate_per_minute ?? 0;
    }


    /*
    |--------------------------------------------------------------------------
    | ERROR TELEMETRY
    |--------------------------------------------------------------------------
    */

    function updateErrorTelemetry(
        errors
    ) {

        if (!errors) {
            return;
        }


        document.getElementById(
            'errors-last-hour'
        ).textContent =
            errors.last_hour ?? 0;
    }

    async function loadErrors() {

        const table =
            document.getElementById(
                'errors-table'
            );

        try {

            const data =
                await fetchJson(
                    routes.errors
                );

            const errors =
                data.errors ?? [];


            if (errors.length === 0) {

                table.innerHTML = `
                    <tr>
                        <td
                            colspan="6"
                            class="px-6 py-10 text-center text-slate-400"
                        >
                            No application errors recorded.
                        </td>
                    </tr>
                `;

            } else {

                table.innerHTML =
                    errors.map(error => {

                        let levelClass =
                            'bg-slate-100 text-slate-700';

                        if (error.level === 'critical') {

                            levelClass =
                                'bg-red-100 text-red-700';

                        } else if (error.level === 'error') {

                            levelClass =
                                'bg-orange-100 text-orange-700';

                        } else if (error.level === 'warning') {

                            levelClass =
                                'bg-amber-100 text-amber-700';
                        }


                        const user =
                            error.user?.name ??
                            (
                                error.user_id
                                    ? `User #${error.user_id}`
                                    : 'System'
                            );


                        return `
                            <tr>

                                <td class="px-6 py-4">

                                    <span
                                        class="px-2 py-1 rounded-full text-xs font-medium ${levelClass}"
                                    >
                                        ${escapeHtml(error.level)}
                                    </span>

                                </td>


                                <td class="px-6 py-4 text-sm text-slate-700">
                                    ${escapeHtml(error.source)}
                                </td>


                                <td class="px-6 py-4">

                                    <span
                                        class="text-xs font-mono text-slate-500"
                                    >
                                        ${escapeHtml(error.type ?? 'N/A')}
                                    </span>

                                </td>


                                <td class="px-6 py-4">

                                    <div
                                        class="max-w-md text-sm text-slate-700 truncate"
                                        title="${escapeHtml(error.message)}"
                                    >
                                        ${escapeHtml(error.message)}
                                    </div>

                                </td>


                                <td class="px-6 py-4 text-sm text-slate-500">
                                    ${escapeHtml(user)}
                                </td>


                                <td class="px-6 py-4 text-sm text-slate-500">
                                    ${formatDate(error.occurred_at)}
                                </td>

                            </tr>
                        `;

                    }).join('');
            }


            document.getElementById(
                'error-refresh-status'
            ).textContent =
                `Updated ${new Date().toLocaleTimeString()}`;


        } catch (error) {

            console.error(
                'Errors failed:',
                error
            );

            table.innerHTML = `
                <tr>
                    <td
                        colspan="6"
                        class="px-6 py-10 text-center text-red-500"
                    >
                        Unable to load application errors.
                    </td>
                </tr>
            `;
        }
    }

    async function loadErrorStats() {

        try {

            const data =
                await fetchJson(
                    routes.errorStats
                );

            document.getElementById(
                'errors-last-hour'
            ).textContent =
                data.last_hour ?? 0;

            document.getElementById(
                'errors-today'
            ).textContent =
                data.today ?? 0;

            document.getElementById(
                'critical-errors'
            ).textContent =
                data.critical ?? 0;

            document.getElementById(
                'warning-errors'
            ).textContent =
                data.warnings ?? 0;

        } catch (error) {

            console.error(
                'Error statistics failed:',
                error
            );
        }
    }

    /*
    |--------------------------------------------------------------------------
    | STATISTICS
    |--------------------------------------------------------------------------
    */

    async function loadStats() {

        try {

            const data =
                await fetchJson(
                    routes.websocketStats
                );


            document.getElementById(
                'active-connections'
            ).textContent =
                data.active_connections ?? 0;


            document.getElementById(
                'active-students'
            ).textContent =
                data.students ?? 0;


            document.getElementById(
                'active-lecturers'
            ).textContent =
                data.lecturers ?? 0;


            document.getElementById(
                'active-classrooms'
            ).textContent =
                data.active_classrooms ?? 0;


            document.getElementById(
                'rejected-connections'
            ).textContent =
                data.rejected_today ?? 0;


        } catch (error) {

            console.error(
                'Telemetry statistics failed:',
                error
            );
        }
    }

    /*
    |--------------------------------------------------------------------------
    | SECURITY TELEMETRY
    |--------------------------------------------------------------------------
    */

    async function loadSecurityStats() {

        try {

            const data =
                await fetchJson(
                    routes.securityStats
                );


            document.getElementById(
                'security-login-failures'
            ).textContent =
                data.login_failures ?? 0;


            document.getElementById(
                'security-rate-limited'
            ).textContent =
                data.rate_limited ?? 0;


            document.getElementById(
                'security-unauthorized'
            ).textContent =
                data.unauthorized ?? 0;


            document.getElementById(
                'security-websocket-rejections'
            ).textContent =
                data.websocket_rejections ?? 0;


            document.getElementById(
                'security-laravel-events'
            ).textContent =
                data.laravel_events ?? 0;


            document.getElementById(
                'security-java-events'
            ).textContent =
                data.java_events ?? 0;

        } catch (error) {

            console.error(
                'Security telemetry failed:',
                error
            );

        }
    }

    /*
    |--------------------------------------------------------------------------
    | SECURITY BLACKLIST
    |--------------------------------------------------------------------------
    */

    async function loadBlacklistSummary() {

        try {

            const data =
                await fetchJson(
                    routes.blacklistList
                );

            const blacklistedIps =
                data.blacklisted_ips ?? [];

            const activeCount =
                blacklistedIps.filter(
                    ip => ip.is_active === true
                ).length;

            document.getElementById(
                'blacklist-active-count'
            ).textContent =
                activeCount;

        } catch (error) {

            console.error(
                'Blacklist telemetry failed:',
                error
            );

            document.getElementById(
                'blacklist-active-count'
            ).textContent =
                '--';
        }
    }

    /*
    |--------------------------------------------------------------------------
    | ACTIVE CONNECTIONS
    |--------------------------------------------------------------------------
    */

    async function loadConnections() {

        const table =
            document.getElementById(
                'connections-table'
            );


        try {

            const data =
                await fetchJson(
                    routes.websocketConnections
                );

            const connections =
                data.connections ?? [];


            if (connections.length === 0) {

                table.innerHTML = `
                    <tr>
                        <td
                            colspan="5"
                            class="px-6 py-10 text-center text-slate-400"
                        >
                            No active WebSocket connections.
                        </td>
                    </tr>
                `;

            } else {

                table.innerHTML =
                    connections.map(connection => {

                        const user =
                            connection.user;

                        const name =
                            user?.name ??
                            `User #${connection.user_id ?? 'Unknown'}`;

                        const role =
                            connection.role ??
                            'Unknown';

                        const roleClass =
                            role.toLowerCase() === 'lecturer'
                                ? 'bg-indigo-100 text-indigo-700'
                                : 'bg-blue-100 text-blue-700';

                        const connected =
                            formatDate(
                                connection.connected_at
                            );


                        return `
                            <tr>

                                <td class="px-6 py-4">

                                    <div class="font-medium text-slate-700">
                                        ${escapeHtml(name)}
                                    </div>

                                    <div class="text-xs text-slate-400">
                                        ID: ${connection.user_id ?? 'N/A'}
                                    </div>

                                </td>

                                <td class="px-6 py-4">

                                    <span
                                        class="px-2 py-1 rounded-full text-xs font-medium ${roleClass}"
                                    >
                                        ${escapeHtml(role)}
                                    </span>

                                </td>

                                <td class="px-6 py-4">

                                    <span class="text-xs font-mono text-slate-500">
                                        ${escapeHtml(connection.classroom_id ?? 'N/A')}
                                    </span>

                                </td>

                                <td class="px-6 py-4">

                                    <span class="text-xs font-mono text-slate-500">
                                        ${escapeHtml(connection.ip_address ?? 'N/A')}
                                    </span>

                                </td>

                                <td class="px-6 py-4 text-sm text-slate-500">
                                    ${connected}
                                </td>

                            </tr>
                        `;

                    }).join('');
            }


            document.getElementById(
                'connection-refresh-status'
            ).textContent =
                `Updated ${new Date().toLocaleTimeString()}`;


        } catch (error) {

            console.error(
                'Active connections failed:',
                error
            );


            table.innerHTML = `
                <tr>
                    <td
                        colspan="5"
                        class="px-6 py-10 text-center text-red-500"
                    >
                        Unable to load active connections.
                    </td>
                </tr>
            `;
        }
    }


    /*
    |--------------------------------------------------------------------------
    | RECENT EVENTS
    |--------------------------------------------------------------------------
    */

    async function loadEvents() {

        const table =
            document.getElementById(
                'events-table'
            );


        try {

            const data =
                await fetchJson(
                    routes.websocketEvents
                );

            const events =
                data.events ?? [];


            if (events.length === 0) {

                table.innerHTML = `
                    <tr>
                        <td
                            colspan="6"
                            class="px-6 py-10 text-center text-slate-400"
                        >
                            No WebSocket events recorded.
                        </td>
                    </tr>
                `;

                return;
            }


            table.innerHTML =
                events.map(event => {

                    const user =
                        event.user;

                    const name =
                        user?.name ??
                        (
                            event.user_id
                                ? `User #${event.user_id}`
                                : 'Unknown'
                        );


                    let eventClass =
                        'bg-slate-100 text-slate-700';


                    if (event.event === 'connected') {

                        eventClass =
                            'bg-emerald-100 text-emerald-700';

                    } else if (event.event === 'disconnected') {

                        eventClass =
                            'bg-slate-100 text-slate-700';

                    } else if (event.event === 'rejected') {

                        eventClass =
                            'bg-red-100 text-red-700';
                    }


                    return `
                        <tr>

                            <td class="px-6 py-4">

                                <span
                                    class="px-2 py-1 rounded-full text-xs font-medium ${eventClass}"
                                >
                                    ${escapeHtml(event.event)}
                                </span>

                            </td>

                            <td class="px-6 py-4 text-sm text-slate-700">
                                ${escapeHtml(name)}
                            </td>

                            <td class="px-6 py-4 text-sm text-slate-500">
                                ${escapeHtml(event.role ?? 'N/A')}
                            </td>

                            <td class="px-6 py-4">

                                <span class="text-xs font-mono text-slate-500">
                                    ${escapeHtml(event.classroom_id ?? 'N/A')}
                                </span>

                            </td>

                            <td class="px-6 py-4">

                                <span class="text-xs font-mono text-slate-500">
                                    ${escapeHtml(event.ip_address ?? 'N/A')}
                                </span>

                            </td>

                            <td class="px-6 py-4 text-sm text-slate-500">
                                ${formatDate(event.occurred_at)}
                            </td>

                        </tr>
                    `;

                }).join('');


        } catch (error) {

            console.error(
                'WebSocket events failed:',
                error
            );


            table.innerHTML = `
                <tr>
                    <td
                        colspan="6"
                        class="px-6 py-10 text-center text-red-500"
                    >
                        Unable to load WebSocket events.
                    </td>
                </tr>
            `;
        }
    }


    /*
    |--------------------------------------------------------------------------
    | FORMATTING
    |--------------------------------------------------------------------------
    */

    function formatDate(value) {

        if (!value) {
            return 'N/A';
        }

        const date =
            new Date(value);

        if (Number.isNaN(date.getTime())) {
            return value;
        }

        return date.toLocaleString();
    }


    function escapeHtml(value) {

        const div =
            document.createElement('div');

        div.textContent =
            value ?? '';

        return div.innerHTML;
    }


    function toNumber(value) {

        if (
            value === null ||
            value === undefined ||
            value === ''
        ) {
            return null;
        }

        const number =
            Number(value);

        return Number.isFinite(number)
            ? number
            : null;
    }


    function clamp(
        value,
        min,
        max
    ) {

        return Math.min(
            Math.max(value, min),
            max
        );
    }


    function formatNumber(value) {

        return Number(value).toFixed(2);
    }


    function formatMemory(mb) {

        if (mb === null || mb === undefined) {
            return '--';
        }

        const value =
            Number(mb);

        if (!Number.isFinite(value)) {
            return '--';
        }

        if (value >= 1024) {

            return `${(value / 1024).toFixed(2)} GB`;

        }

        return `${value.toFixed(2)} MB`;
    }


    /*
    |--------------------------------------------------------------------------
    | REFRESH
    |--------------------------------------------------------------------------
    */

    async function refreshTelemetry() {

        await Promise.all([

            checkJavaHealth(),

            loadStats(),

            loadConnections(),

            loadEvents(),

            loadSystemTelemetry(),

            loadErrorStats(),

            loadErrors(),

            loadSecurityStats(),
            
            loadBlacklistSummary(),
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | INITIAL LOAD
    |--------------------------------------------------------------------------
    */

    loadWebSocketConfig();

    refreshTelemetry();


    /*
    |--------------------------------------------------------------------------
    | POLLING
    |--------------------------------------------------------------------------
    */

    setInterval(() => {

        refreshTelemetry();

        checkWebSocketHealth();

    }, 5000);

});

</script>

@endsection