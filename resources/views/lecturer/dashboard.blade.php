@extends('lecturer.layout')

@section('header_title', 'Dashboard Overview')

@section('content')
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-sm font-medium text-slate-500 uppercase tracking-wider">WebSocket Server</p>
                    <h3 class="text-2xl font-bold text-slate-800 mt-1">Operational</h3>
                </div>
                <div class="bg-emerald-100 text-emerald-600 p-3 rounded-xl">
                    <i class="fas fa-server"></i>
                </div>
            </div>
            <div class="mt-4 flex items-center text-sm text-emerald-600">
                <i class="fas fa-circle text-[8px] mr-2"></i>
                <span>Connected to Java Microservice</span>
            </div>
        </div>

        <div class="bg-indigo-600 p-6 rounded-2xl shadow-lg shadow-indigo-200">
            <h3 class="text-xl font-bold text-white mb-2">Ready to Teach?</h3>
            <p class="text-indigo-100 text-sm mb-4">Start your live session and the class ID will be generated automatically.</p>
            <a href="{{ route('lecturer.classes.create') }}" class="bg-white text-indigo-600 px-4 py-2 rounded-lg font-bold text-sm inline-block hover:bg-indigo-50 transition">
                <i class="fas fa-play mr-2 text-xs"></i> Launch Class
            </a>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-slate-100 flex justify-between items-center">
            <h3 class="font-bold text-slate-800">Your Active Classes</h3>
            <span class="text-xs font-bold px-2 py-1 bg-slate-100 text-slate-500 rounded text-uppercase">Recent</span>
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
                            <td class="px-6 py-4 font-medium text-slate-700">{{ $class->title }}</td>
                            <td class="px-6 py-4 text-xs font-mono text-slate-400">{{ $class->uuid }}</td>
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
                                        <a href="{{ route('lecturer.classes.show', $class) }}"
                                        class="text-indigo-600 hover:text-indigo-900 text-sm font-bold">
                                            Join Session
                                        </a>

                                        <form method="POST"
                                            action="{{ route('lecturer.classes.end', $class) }}"
                                            onsubmit="return confirm('Are you sure you want to end this class?')">
                                            @csrf
                                            <button type="submit"
                                                    class="text-red-600 hover:text-red-800 text-sm font-bold">
                                                End Class
                                            </button>
                                        </form>
                                    @else
                                        <form method="POST"
                                            action="{{ route('lecturer.classes.restart', $class) }}"
                                            onsubmit="return confirm('Start this class again?')">
                                            @csrf
                                            <button type="submit"
                                                    class="text-emerald-600 hover:text-emerald-800 text-sm font-bold">
                                                Start Class
                                            </button>
                                        </form>
                                    @endif

                                    <form method="POST"
                                        action="{{ route('lecturer.classes.destroy', $class) }}"
                                        onsubmit="return confirm('This will permanently delete the class. Continue?')">
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                                class="text-red-700 hover:text-red-900 text-sm font-bold">
                                            Delete
                                        </button>
                                    </form>

                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-10 text-center text-slate-400">
                                <i class="fas fa-folder-open block text-3xl mb-3"></i>
                                No classes created yet.
                            </td>
                        </tr>
                    @endforelse
                    
                    <div class="p-6">
                        {{ $classes->links() }}
                    </div>
                </tbody>
            </table>
        </div>
    </div>
@endsection