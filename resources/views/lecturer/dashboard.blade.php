@extends('lecturer.layout')

@section('header_title', 'Dashboard Overview')

@section('content')

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">

        {{-- WebSocket Server Status --}}
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">

            <div class="flex justify-between items-start">

                <div>
                    <p class="text-sm font-medium text-slate-500 uppercase tracking-wider">
                        WebSocket Server
                    </p>

                    <h3
                        id="websocket-status-title"
                        class="text-2xl font-bold text-slate-800 mt-1"
                    >
                        Checking...
                    </h3>
                </div>

                <div
                    id="websocket-status-icon"
                    class="bg-slate-100 text-slate-500 p-3 rounded-xl"
                >
                    <i class="fas fa-server"></i>
                </div>

            </div>

            <div
                id="websocket-status-message"
                class="mt-4 flex items-center text-sm text-slate-500"
            >
                <i class="fas fa-circle text-[8px] mr-2"></i>

                <span>
                    Checking connection to Java Microservice...
                </span>
            </div>

        </div>


        {{-- Launch Class --}}
        <div class="bg-indigo-600 p-6 rounded-2xl shadow-lg shadow-indigo-200">

            <h3 class="text-xl font-bold text-white mb-2">
                Ready to Teach?
            </h3>

            <p class="text-indigo-100 text-sm mb-4">
                Start your live session and the class ID will be generated automatically.
            </p>

            <a
                id="launch-class-button"
                href="{{ route('lecturer.classes.create') }}"
                class="bg-white text-indigo-600 px-4 py-2 rounded-lg font-bold text-sm inline-block hover:bg-indigo-50 transition"
            >
                <i class="fas fa-play mr-2 text-xs"></i>
                Launch Class
            </a>

            {{-- Server unavailable message --}}
            <div
                id="launch-class-warning"
                class="hidden mt-3 p-3 bg-red-500/20 border border-red-300/30 rounded-lg"
            >
                <div class="flex items-start gap-2 text-red-100 text-xs">
                    <i class="fas fa-triangle-exclamation mt-0.5"></i>

                    <p>
                        The WebSocket server is currently unavailable.
                        Please
                        <a
                            href="{{ route('contact') }}"
                            class="font-semibold underline hover:text-white transition"
                        >
                            contact the administrator
                        </a>
                        for assistance.
                    </p>
                </div>
            </div>

        </div>

    </div>


    {{-- Active Classes --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">

        <div class="p-6 border-b border-slate-100 flex justify-between items-center">

            <h3 class="font-bold text-slate-800">
                Your Active Classes
            </h3>

            <span class="text-xs font-bold px-2 py-1 bg-slate-100 text-slate-500 rounded text-uppercase">
                Recent
            </span>

        </div>

        <div class="p-0">

            <table class="w-full text-left border-collapse">

                <thead class="bg-slate-50 text-slate-400 text-xs uppercase font-semibold">

                    <tr>
                        <th class="px-6 py-3">Class Title</th>
                        <th class="px-6 py-3">UUID</th>
                        <th class="px-6 py-3">Status</th>
                        <th class="px-6 py-3 text-right">Actions</th>
                    </tr>

                </thead>

                <tbody class="divide-y divide-slate-100">

                    @forelse($classes ?? [] as $class)

                        <tr>

                            <td class="px-6 py-4 font-medium text-slate-700">
                                {{ $class->title }}
                            </td>

                            <td class="px-6 py-4 text-xs font-mono text-slate-400">
                                {{ $class->uuid }}
                            </td>

                            <td class="px-6 py-4">

                                @if($class->is_active)

                                    <span class="px-2 py-1 bg-green-100 text-green-700 text-xs rounded-full">
                                        Active
                                    </span>

                                @else

                                    <span class="px-2 py-1 bg-slate-200 text-slate-600 text-xs rounded-full">
                                        Ended
                                    </span>

                                @endif

                            </td>

                            <td class="px-6 py-4 text-right">

                                <div class="flex justify-end gap-4">

                                    @if($class->is_active)

                                        <a
                                            href="{{ route('lecturer.classes.show', $class) }}"
                                            class="text-indigo-600 hover:text-indigo-900 text-sm font-bold"
                                        >
                                            Join Session
                                        </a>

                                        <form
                                            method="POST"
                                            action="{{ route('lecturer.classes.end', $class) }}"
                                            onsubmit="return confirm('Are you sure you want to end this class?')"
                                        >
                                            @csrf

                                            <button
                                                type="submit"
                                                class="text-red-600 hover:text-red-800 text-sm font-bold"
                                            >
                                                End Class
                                            </button>
                                        </form>

                                    @else

                                        <form
                                            method="POST"
                                            action="{{ route('lecturer.classes.restart', $class) }}"
                                            onsubmit="return confirm('Start this class again?')"
                                        >
                                            @csrf

                                            <button
                                                type="submit"
                                                class="text-emerald-600 hover:text-emerald-800 text-sm font-bold"
                                            >
                                                Start Class
                                            </button>
                                        </form>

                                    @endif


                                    <form
                                        method="POST"
                                        action="{{ route('lecturer.classes.destroy', $class) }}"
                                        onsubmit="return confirm('This will permanently delete the class. Continue?')"
                                    >
                                        @csrf
                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="text-red-700 hover:text-red-900 text-sm font-bold"
                                        >
                                            Delete
                                        </button>
                                    </form>

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="4"
                                class="px-6 py-10 text-center text-slate-400"
                            >
                                <i class="fas fa-folder-open block text-3xl mb-3"></i>

                                No classes created yet.
                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

            @if(isset($classes) && $classes->hasPages())
                <div class="p-6">
                    {{ $classes->links() }}
                </div>
            @endif

        </div>

    </div>


    {{-- WebSocket Health Check --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {

            const healthUrl = @json(route('lecturer.websocket.health'));

            const statusTitle = document.getElementById(
                'websocket-status-title'
            );

            const statusIcon = document.getElementById(
                'websocket-status-icon'
            );

            const statusMessage = document.getElementById(
                'websocket-status-message'
            );

            const launchButton = document.getElementById(
                'launch-class-button'
            );

            const launchWarning = document.getElementById(
                'launch-class-warning'
            );


            /*
             * Initially prevent launching a class while
             * the health check is running.
             */
            let websocketAvailable = false;


            function setServerOperational() {

                websocketAvailable = true;

                // Status title
                statusTitle.textContent = 'Operational';

                statusTitle.classList.remove(
                    'text-slate-800',
                    'text-red-600'
                );

                statusTitle.classList.add(
                    'text-emerald-600'
                );


                // Server icon
                statusIcon.classList.remove(
                    'bg-slate-100',
                    'text-slate-500',
                    'bg-red-100',
                    'text-red-600'
                );

                statusIcon.classList.add(
                    'bg-emerald-100',
                    'text-emerald-600'
                );


                // Status message
                statusMessage.classList.remove(
                    'text-slate-500',
                    'text-red-600'
                );

                statusMessage.classList.add(
                    'text-emerald-600'
                );

                statusMessage.innerHTML = `
                    <i class="fas fa-circle text-[8px] mr-2"></i>
                    <span>Connected to Java Microservice</span>
                `;


                // Enable launch button
                launchButton.classList.remove(
                    'opacity-50',
                    'cursor-not-allowed'
                );

                launchButton.classList.add(
                    'hover:bg-indigo-50'
                );

                launchWarning.classList.add('hidden');
            }


            function setServerDown() {

                websocketAvailable = false;

                // Status title
                statusTitle.textContent = 'Server Down';

                statusTitle.classList.remove(
                    'text-slate-800',
                    'text-emerald-600'
                );

                statusTitle.classList.add(
                    'text-red-600'
                );


                // Server icon
                statusIcon.classList.remove(
                    'bg-slate-100',
                    'text-slate-500',
                    'bg-emerald-100',
                    'text-emerald-600'
                );

                statusIcon.classList.add(
                    'bg-red-100',
                    'text-red-600'
                );


                // Status message
                statusMessage.classList.remove(
                    'text-slate-500',
                    'text-emerald-600'
                );

                statusMessage.classList.add(
                    'text-red-600'
                );

                statusMessage.innerHTML = `
                    <i class="fas fa-circle text-[8px] mr-2"></i>
                    <span>WebSocket server down. Contact admin.</span>
                `;


                // Disable launch button visually
                launchButton.classList.add(
                    'opacity-50',
                    'cursor-not-allowed'
                );

                launchButton.classList.remove(
                    'hover:bg-indigo-50'
                );


                launchWarning.classList.remove('hidden');
            }


            /*
             * Prevent navigation when the Java server
             * is unavailable.
             */
            launchButton.addEventListener('click', function (event) {

                if (!websocketAvailable) {

                    event.preventDefault();

                    launchWarning.classList.remove('hidden');

                }

            });


            /*
             * Check Java server health.
             */
            async function checkWebSocketServer() {

                try {

                    const response = await fetch(healthUrl, {
                        method: 'GET',

                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        },

                        credentials: 'same-origin'
                    });


                    const data = await response.json();


                    if (response.ok && data.connected === true) {

                        setServerOperational();

                    } else {

                        setServerDown();

                    }

                } catch (error) {

                    console.error(
                        'Java WebSocket server health check failed:',
                        error
                    );

                    setServerDown();

                }

            }


            checkWebSocketServer();

        });
    </script>

@endsection