@extends('admin.layout')

@section('title', 'Security Telemetry')

@section('content')

<div class="space-y-6">

    {{-- ============================================================
        HEADER
    ============================================================= --}}

    <div>
        <h2 class="text-lg font-bold text-slate-800">
            Security Telemetry
        </h2>

        <p class="text-sm text-slate-500">
            Monitor authentication, rate limiting, authorization,
            and WebSocket security events.
        </p>
    </div>


    {{-- ============================================================
        SECURITY STATISTICS
    ============================================================= --}}

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">

        {{-- Total Events --}}
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">

            <p class="text-sm font-medium text-slate-500">
                Total Security Events
            </p>

            <h3
                id="security-total"
                class="text-3xl font-bold text-slate-800 mt-2"
            >
                --
            </h3>

            <p class="text-xs text-slate-400 mt-2">
                All recorded events
            </p>

        </div>


        {{-- Last Hour --}}
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">

            <p class="text-sm font-medium text-slate-500">
                Events — Last Hour
            </p>

            <h3
                id="security-last-hour"
                class="text-3xl font-bold text-slate-800 mt-2"
            >
                --
            </h3>

            <p class="text-xs text-slate-400 mt-2">
                Security activity
            </p>

        </div>


        {{-- Login Failures --}}
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">

            <div class="flex justify-between items-start">

                <div>

                    <p class="text-sm font-medium text-slate-500">
                        Login Failures
                    </p>

                    <h3
                        id="login-failures"
                        class="text-3xl font-bold text-red-600 mt-2"
                    >
                        --
                    </h3>

                    <p class="text-xs text-slate-400 mt-2">
                        Last hour
                    </p>

                </div>

                <div class="bg-red-100 text-red-600 p-3 rounded-xl">
                    <i class="fas fa-user-lock"></i>
                </div>

            </div>

        </div>


        {{-- Rate Limited --}}
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">

            <div class="flex justify-between items-start">

                <div>

                    <p class="text-sm font-medium text-slate-500">
                        Rate Limited
                    </p>

                    <h3
                        id="rate-limited"
                        class="text-3xl font-bold text-orange-600 mt-2"
                    >
                        --
                    </h3>

                    <p class="text-xs text-slate-400 mt-2">
                        Last hour
                    </p>

                </div>

                <div class="bg-orange-100 text-orange-600 p-3 rounded-xl">
                    <i class="fas fa-stopwatch"></i>
                </div>

            </div>

        </div>

    </div>


    {{-- ============================================================
        ACCESS CONTROL STATISTICS
    ============================================================= --}}

    <div>

        <h2 class="text-lg font-bold text-slate-800">
            Access Control
        </h2>

        <p class="text-sm text-slate-500">
            Authentication and authorization security events.
        </p>

    </div>


    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">

        {{-- Unauthorized --}}
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">

            <p class="text-sm font-medium text-slate-500">
                Unauthorized
            </p>

            <h3
                id="unauthorized"
                class="text-3xl font-bold text-red-600 mt-2"
            >
                --
            </h3>

            <p class="text-xs text-slate-400 mt-2">
                Last hour
            </p>

        </div>


        {{-- Forbidden --}}
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">

            <p class="text-sm font-medium text-slate-500">
                Forbidden
            </p>

            <h3
                id="forbidden"
                class="text-3xl font-bold text-red-600 mt-2"
            >
                --
            </h3>

            <p class="text-xs text-slate-400 mt-2">
                Last hour
            </p>

        </div>


        {{-- WebSocket Rejections --}}
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">

            <p class="text-sm font-medium text-slate-500">
                WebSocket Rejections
            </p>

            <h3
                id="websocket-rejections"
                class="text-3xl font-bold text-purple-600 mt-2"
            >
                --
            </h3>

            <p class="text-xs text-slate-400 mt-2">
                Last hour
            </p>

        </div>


        {{-- Account Lockouts --}}
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">

            <p class="text-sm font-medium text-slate-500">
                Account Lockouts
            </p>

            <h3
                id="account-lockouts"
                class="text-3xl font-bold text-red-600 mt-2"
            >
                --
            </h3>

            <p class="text-xs text-slate-400 mt-2">
                Last hour
            </p>

        </div>

    </div>


    {{-- ============================================================
        EVENT SOURCES
    ============================================================= --}}

    <div>

        <h2 class="text-lg font-bold text-slate-800">
            Event Sources
        </h2>

        <p class="text-sm text-slate-500">
            Distribution of security events between Laravel and Java.
        </p>

    </div>


    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        {{-- Laravel --}}
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">

            <div class="flex justify-between items-center">

                <div>

                    <p class="text-sm font-medium text-slate-500 uppercase tracking-wider">
                        Laravel Security Events
                    </p>

                    <h3
                        id="laravel-events"
                        class="text-3xl font-bold text-indigo-600 mt-2"
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

            <div class="flex justify-between items-center">

                <div>

                    <p class="text-sm font-medium text-slate-500 uppercase tracking-wider">
                        Java / WebSocket Events
                    </p>

                    <h3
                        id="java-events"
                        class="text-3xl font-bold text-orange-600 mt-2"
                    >
                        --
                    </h3>

                    <p class="text-xs text-slate-400 mt-2">
                        Last hour
                    </p>

                </div>

                <div class="bg-orange-100 text-orange-600 p-3 rounded-xl">
                    <i class="fas fa-network-wired"></i>
                </div>

            </div>

        </div>

    </div>


    {{-- ============================================================
        SECURITY EVENTS TABLE
    ============================================================= --}}

    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">

        <div class="p-6 border-b border-slate-100">

            <div class="flex justify-between items-center">

                <div>

                    <h3 class="font-bold text-slate-800">
                        Recent Security Events
                    </h3>

                    <p class="text-sm text-slate-500 mt-1">
                        Latest authentication, rate-limit,
                        authorization and WebSocket security activity.
                    </p>

                </div>

                <span
                    id="security-refresh-status"
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
                            Event
                        </th>

                        <th class="px-6 py-3">
                            Source
                        </th>

                        <th class="px-6 py-3">
                            User
                        </th>

                        <th class="px-6 py-3">
                            IP Address
                        </th>

                        <th class="px-6 py-3">
                            Class
                        </th>

                        <th class="px-6 py-3">
                            Reason
                        </th>

                        <th class="px-6 py-3">
                            Time
                        </th>

                    </tr>

                </thead>

                <tbody
                    id="security-events-table"
                    class="divide-y divide-slate-100"
                >

                    <tr>

                        <td
                            colspan="7"
                            class="px-6 py-10 text-center text-slate-400"
                        >
                            Loading security events...
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
    | Routes
    |--------------------------------------------------------------------------
    */

    const routes = {

        stats:
            @json(route('admin.telemetry.security.stats')),

        events:
            @json(route('admin.telemetry.security.events')),

    };


    /*
    |--------------------------------------------------------------------------
    | Fetch Helper
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

            throw new Error(
                `HTTP ${response.status}`
            );

        }


        return await response.json();

    }


    /*
    |--------------------------------------------------------------------------
    | Security Statistics
    |--------------------------------------------------------------------------
    */

    async function loadSecurityStats() {

        try {

            const data =
                await fetchJson(routes.stats);


            document.getElementById(
                'security-total'
            ).textContent =
                data.total ?? 0;


            document.getElementById(
                'security-last-hour'
            ).textContent =
                data.last_hour ?? 0;


            document.getElementById(
                'login-failures'
            ).textContent =
                data.login_failures ?? 0;


            document.getElementById(
                'rate-limited'
            ).textContent =
                data.rate_limited ?? 0;


            document.getElementById(
                'unauthorized'
            ).textContent =
                data.unauthorized ?? 0;


            document.getElementById(
                'forbidden'
            ).textContent =
                data.forbidden ?? 0;


            document.getElementById(
                'websocket-rejections'
            ).textContent =
                data.websocket_rejections ?? 0;


            document.getElementById(
                'laravel-events'
            ).textContent =
                data.laravel_events ?? 0;


            document.getElementById(
                'java-events'
            ).textContent =
                data.java_events ?? 0;


            /*
            |------------------------------------------------------------------
            | Account lockouts
            |------------------------------------------------------------------
            */

            /*
             * The current stats endpoint does not expose
             * account_lockout yet.
             *
             * We therefore calculate it from the events
             * endpoint rather than displaying an incorrect value.
             */

            loadSecurityEvents();


        } catch (error) {

            console.error(
                'Security statistics failed:',
                error
            );

        }

    }


    /*
    |--------------------------------------------------------------------------
    | Security Events
    |--------------------------------------------------------------------------
    */

    async function loadSecurityEvents() {

        const table =
            document.getElementById(
                'security-events-table'
            );


        try {

            const data =
                await fetchJson(routes.events);


            const events =
                data.events ?? [];


            /*
            |------------------------------------------------------------------
            | Account lockout
            |------------------------------------------------------------------
            */

            const oneHourAgo =
                Date.now() -
                (60 * 60 * 1000);


            const lockouts =
                events.filter(event => {

                    if (
                        event.event !==
                        'account_lockout'
                    ) {
                        return false;
                    }


                    const time =
                        new Date(
                            event.occurred_at
                        ).getTime();


                    return time >= oneHourAgo;

                }).length;


            document.getElementById(
                'account-lockouts'
            ).textContent =
                lockouts;


            /*
            |------------------------------------------------------------------
            | Empty state
            |------------------------------------------------------------------
            */

            if (events.length === 0) {

                table.innerHTML = `

                    <tr>

                        <td
                            colspan="7"
                            class="px-6 py-10 text-center text-slate-400"
                        >
                            No security events recorded.
                        </td>

                    </tr>

                `;

            } else {

                table.innerHTML =
                    events.map(event => {

                        const user =
                            event.user;


                        const name =
                            user?.name ??
                            (
                                event.user_id
                                    ? `User #${event.user_id}`
                                    : 'Guest'
                            );


                        /*
                        |------------------------------------------------------
                        | Event styling
                        |------------------------------------------------------
                        */

                        let eventClass =
                            'bg-slate-100 text-slate-700';


                        if (
                            event.event ===
                            'login_success'
                        ) {

                            eventClass =
                                'bg-emerald-100 text-emerald-700';

                        } else if (
                            event.event ===
                            'logout'
                        ) {

                            eventClass =
                                'bg-blue-100 text-blue-700';

                        } else if (
                            event.event ===
                            'login_failed'
                        ) {

                            eventClass =
                                'bg-red-100 text-red-700';

                        } else if (
                            event.event ===
                            'account_lockout'
                        ) {

                            eventClass =
                                'bg-red-100 text-red-700';

                        } else if (
                            event.event ===
                            'rate_limited'
                        ) {

                            eventClass =
                                'bg-orange-100 text-orange-700';

                        } else if (
                            event.event ===
                            'unauthorized'
                        ) {

                            eventClass =
                                'bg-yellow-100 text-yellow-700';

                        } else if (
                            event.event ===
                            'forbidden'
                        ) {

                            eventClass =
                                'bg-red-100 text-red-700';

                        } else if (
                            event.event ===
                            'websocket_rejected'
                        ) {

                            eventClass =
                                'bg-purple-100 text-purple-700';

                        }


                        /*
                        |------------------------------------------------------
                        | Source
                        |------------------------------------------------------
                        */

                        const source =
                            event.source ??
                            'unknown';


                        const sourceClass =
                            source === 'java'
                                ? 'bg-orange-100 text-orange-700'
                                : 'bg-indigo-100 text-indigo-700';


                        /*
                        |------------------------------------------------------
                        | Context
                        |------------------------------------------------------
                        */

                        const context =
                            event.context ?? {};


                        const classroom =
                            event.classroom_id ??
                            context.classroom_id ??
                            'N/A';


                        const reason =
                            event.reason ??
                            'N/A';


                        return `

                            <tr>

                                <td class="px-6 py-4">

                                    <span
                                        class="px-2 py-1 rounded-full text-xs font-medium ${eventClass}"
                                    >
                                        ${escapeHtml(
                                            event.event ?? 'unknown'
                                        )}
                                    </span>

                                </td>


                                <td class="px-6 py-4">

                                    <span
                                        class="px-2 py-1 rounded-full text-xs font-medium ${sourceClass}"
                                    >
                                        ${escapeHtml(source)}
                                    </span>

                                </td>


                                <td class="px-6 py-4">

                                    <div class="font-medium text-slate-700">
                                        ${escapeHtml(name)}
                                    </div>

                                    ${
                                        event.user_id
                                            ? `
                                                <div class="text-xs text-slate-400">
                                                    ID: ${escapeHtml(
                                                        String(event.user_id)
                                                    )}
                                                </div>
                                              `
                                            : ''
                                    }

                                </td>


                                <td class="px-6 py-4">

                                    <span
                                        class="text-xs font-mono text-slate-500"
                                    >
                                        ${escapeHtml(
                                            event.ip_address ?? 'N/A'
                                        )}
                                    </span>

                                </td>


                                <td class="px-6 py-4">

                                    <span
                                        class="text-xs font-mono text-slate-500"
                                    >
                                        ${escapeHtml(classroom)}
                                    </span>

                                </td>


                                <td class="px-6 py-4">

                                    <span class="text-sm text-slate-500">
                                        ${escapeHtml(reason)}
                                    </span>

                                </td>


                                <td class="px-6 py-4 text-sm text-slate-500">

                                    ${formatDate(
                                        event.occurred_at
                                    )}

                                </td>

                            </tr>

                        `;

                    }).join('');

            }


            document.getElementById(
                'security-refresh-status'
            ).textContent =
                `Updated ${new Date().toLocaleTimeString()}`;


        } catch (error) {

            console.error(
                'Security events failed:',
                error
            );


            table.innerHTML = `

                <tr>

                    <td
                        colspan="7"
                        class="px-6 py-10 text-center text-red-500"
                    >
                        Unable to load security events.
                    </td>

                </tr>

            `;

        }

    }


    /*
    |--------------------------------------------------------------------------
    | Formatting
    |--------------------------------------------------------------------------
    */

    function formatDate(value) {

        if (!value) {
            return 'N/A';
        }


        const date =
            new Date(value);


        if (
            Number.isNaN(
                date.getTime()
            )
        ) {

            return value;

        }


        return date.toLocaleString();

    }


    /*
    |--------------------------------------------------------------------------
    | HTML Escaping
    |--------------------------------------------------------------------------
    */

    function escapeHtml(value) {

        const div =
            document.createElement('div');


        div.textContent =
            value ?? '';


        return div.innerHTML;

    }


    /*
    |--------------------------------------------------------------------------
    | Initial Load
    |--------------------------------------------------------------------------
    */

    async function refreshSecurityTelemetry() {

        await loadSecurityStats();

        /*
         * loadSecurityStats() also loads events because
         * account_lockout is currently calculated from them.
         */

    }


    refreshSecurityTelemetry();


    /*
    |--------------------------------------------------------------------------
    | Refresh every 5 seconds
    |--------------------------------------------------------------------------
    */

    setInterval(() => {

        refreshSecurityTelemetry();

    }, 5000);

});

</script>

@endsection