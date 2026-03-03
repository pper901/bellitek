<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>{{ $classroom->title }} | Bellitek Classes</title>

    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    <link rel="icon" href="{{ asset('images/favicon.svg') }}" type="image/x-icon">

    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>

<body>

<!-- =========================================================
     HIDDEN CONTEXT (ENGINE CONSUMES THIS)
========================================================= -->
<form id="runtimeContext" style="display:none;">
    <input type="hidden" id="name" value="{{ $studentName }}">
    <input type="hidden" id="classTitle" value="{{ $classroom->title }}">
    <input type="hidden" id="classUuid" value="{{ $classroom->uuid }}">
    <input type="hidden" id="userId" value="{{ $studentId }}">
    <input type="hidden" id="role" value="Student">
</form>

<!-- =========================================================
     MODAL
========================================================= -->
<div class="modal-panel"></div>

<!-- =========================================================
     HEADER
========================================================= -->
<header>
    <h3 id="headerClass">{{ $classroom->title }}</h3>
</header>

<!-- =========================================================
     MAIN LAYOUT
========================================================= -->
<div class="main-panel">

    <!-- VIDEO / BOARD -->
    <div class="f-panel">
        <div class="f-item" id="preview">
            <div id="connecting">Connecting to classroom...</div>
        </div>
        <div class="s-item" id="preview-control"></div>
    </div>

    <!-- SHARED BOARD / RESOURCES -->
    <div class="s-panel" id="targetDiv1" onscroll="handleScroll(this)">
    </div>

    <!-- CHAT -->
    <div class="t-panel" id="t-panel">
        <div class="fo-item" id="chat" onscroll="handleScroll(this)"></div>

        <div class="fi-item">
            <input
                type="text"
                class="chat"
                id="message"
                placeholder="Type a message…"
                autocomplete="off"
            >
            <button class="chat-btn" id="chatBtn">
                Send
            </button>
        </div>
    </div>

</div>

<footer class="bg-white border-t text-center py-2 text-xs text-gray-400 shrink-0">
        © {{ date('Y') }} Bellitek Classes • Secure Classroom Environment
    </footer>

<!-- =========================================================
     HELPERS
========================================================= -->
<script>
    function handleScroll(container) {
        container.style.overflowY =
            container.scrollHeight > container.clientHeight
                ? 'scroll'
                : 'auto';
    }
</script>

<!-- =========================================================
     ENGINE
========================================================= -->
<script src="{{ asset('js/engine.js') }}"></script>
<script src="{{ asset('js/adapter-latest.js') }}"></script>

</body>
</html>
