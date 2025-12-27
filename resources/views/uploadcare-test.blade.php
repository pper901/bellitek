<!DOCTYPE html>
<html>
<head>
    <title>Uploadcare Test</title>
</head>
<body style="font-family: sans-serif; padding: 40px;">

    <h2>Uploadcare Upload Test</h2>

    @if (session('success'))
        <p style="color: green;">{{ session('success') }}</p>

        <p><strong>UUID:</strong> {{ session('uuid') }}</p>

        <p>
            <strong>CDN URL:</strong><br>
            <a href="{{ session('url') }}" target="_blank">
                {{ session('url') }}
            </a>
        </p>

        @if(Str::contains(session('url'), ['.jpg', '.png', '.webp']))
            <img src="{{ session('url') }}" style="max-width: 300px;">
        @endif
    @endif

    @if (session('error'))
        <p style="color: red;">{{ session('error') }}</p>
    @endif

    <form action="{{ route('uploadcare.test.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <input type="file" name="file" required>
        <br><br>

        <button type="submit">Upload</button>
    </form>

</body>
</html>
