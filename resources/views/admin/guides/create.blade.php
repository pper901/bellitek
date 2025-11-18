@extends('admin.layout')

@section('title','Create Guide')

@section('content')

<div class="bg-white p-6 rounded shadow">

<h2 class="text-xl font-bold mb-4">Create New Guide</h2>

<form method="POST" action="{{ route('admin.guides.store') }}">
    @csrf

    <div class="grid grid-cols-2 gap-4">

        <div>
            <label>Device</label>
            <input name="device" class="input" placeholder="Phone / Laptop">
        </div>

        <div>
            <label>Category</label>
            <input name="category" class="input" placeholder="Android / iPhone">
        </div>

        <div>
            <label>Brand</label>
            <input name="brand" class="input" placeholder="Samsung / HP">
        </div>

        <div>
            <label>Series</label>
            <input name="series" class="input" placeholder="S Series">
        </div>

        <div>
            <label>Model</label>
            <input name="model" class="input" placeholder="Galaxy S25">
        </div>

        <div>
            <label>Issue</label>
            <input name="issue" class="input" placeholder="Screen has lines">
        </div>

    </div>

    <h3 class="font-bold mt-6 mb-2">Causes & Solutions</h3>

    <div id="resource-wrapper"></div>

    <button type="button" id="add-resource"
        class="mt-2 bg-gray-700 text-white px-4 py-2 rounded">+ Add Cause & Solution</button>

    <button class="mt-4 bg-blue-500 text-white px-6 py-2 rounded">Save Guide</button>

</form>
</div>

<script>
document.getElementById('add-resource').onclick = () => {
    let box = document.createElement('div');
    box.className = "border p-4 mt-4 rounded bg-gray-50";

    box.innerHTML = `
        <div class="grid grid-cols-2 gap-4">

            <div>
                <label class="font-semibold">Cause</label>
                <input name="resources[][cause]" class="input" placeholder="e.g. software bug">
            </div>

            <div>
                <label class="font-semibold">Solution</label>
                <input name="resources[][solution]" class="input" placeholder="update phone">
            </div>

            <!-- FULL WIDTH TEXTAREA -->
            <div class="col-span-2">
                <label class="font-semibold">Detailed Steps</label>
                <textarea
                    name="resources[][details]"
                    class="input h-40 w-full"
                    placeholder="Write detailed troubleshooting steps…"
                ></textarea>
            </div>

        </div>
    `;

    document.getElementById('resource-wrapper').appendChild(box);
}
</script>


@endsection
