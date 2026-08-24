@extends('admin.layout')

@section('content')

<div class="container-fluid py-4">

    {{-- ========================================================= --}}
    {{-- HEADER --}}
    {{-- ========================================================= --}}

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h2 class="mb-1">
                IP Blacklist
            </h2>

            <p class="text-muted mb-0">
                Manage IP addresses blocked from accessing Laravel
                and the Java WebSocket server.
            </p>
        </div>

        <a href="{{ route('admin.telemetry.security.index') }}"
           class="btn btn-outline-secondary">
            ← Security Telemetry
        </a>

    </div>


    {{-- ========================================================= --}}
    {{-- ADD IP --}}
    {{-- ========================================================= --}}

    <div class="card shadow-sm mb-4">

        <div class="card-header">
            <strong>Add IP Address to Blacklist</strong>
        </div>

        <div class="card-body">

            <form method="POST"
                  action="{{ route('admin.telemetry.security.blacklist.store') }}">

                @csrf

                <div class="row g-3">

                    <div class="col-md-5">

                        <label for="ip_address"
                               class="form-label">
                            IP Address
                        </label>

                        <input
                            type="text"
                            name="ip_address"
                            id="ip_address"
                            class="form-control @error('ip_address') is-invalid @enderror"
                            placeholder="192.168.1.100"
                            value="{{ old('ip_address') }}"
                            required
                        >

                        @error('ip_address')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    <div class="col-md-5">

                        <label for="reason"
                               class="form-label">
                            Reason
                        </label>

                        <input
                            type="text"
                            name="reason"
                            id="reason"
                            class="form-control @error('reason') is-invalid @enderror"
                            placeholder="Suspicious activity"
                            value="{{ old('reason') }}"
                            maxlength="255"
                        >

                        @error('reason')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    <div class="col-md-2 d-flex align-items-end">

                        <button
                            type="submit"
                            class="btn btn-danger w-100">
                            Block IP
                        </button>

                    </div>

                </div>

            </form>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- FLASH MESSAGES --}}
    {{-- ========================================================= --}}

    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show">

            {{ session('success') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    @if(session('error'))

        <div class="alert alert-danger alert-dismissible fade show">

            {{ session('error') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    {{-- ========================================================= --}}
    {{-- VALIDATION ERRORS --}}
    {{-- ========================================================= --}}

    @if($errors->any())

        <div class="alert alert-danger">

            <strong>Please correct the following:</strong>

            <ul class="mb-0 mt-2">

                @foreach($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    @endif


    {{-- ========================================================= --}}
    {{-- BLACKLIST TABLE --}}
    {{-- ========================================================= --}}

    <div class="card shadow-sm">

        <div class="card-header d-flex justify-content-between align-items-center">

            <strong>
                Blacklisted IP Addresses
            </strong>

            <span
                id="blacklist-count"
                class="badge bg-secondary">
                Loading...
            </span>

        </div>


        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover mb-0 align-middle">

                    <thead class="table-light">

                        <tr>

                            <th>
                                IP Address
                            </th>

                            <th>
                                Reason
                            </th>

                            <th>
                                Status
                            </th>

                            <th>
                                Added By
                            </th>

                            <th>
                                Created
                            </th>

                            <th class="text-end">
                                Actions
                            </th>

                        </tr>

                    </thead>


                    <tbody id="blacklist-table-body">

                        <tr>

                            <td
                                colspan="6"
                                class="text-center py-4 text-muted">

                                Loading blacklist...

                            </td>

                        </tr>

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>


{{-- ============================================================= --}}
{{-- JAVASCRIPT --}}
{{-- ============================================================= --}}

<script>

document.addEventListener('DOMContentLoaded', function () {

    loadBlacklist();

});


/*
|--------------------------------------------------------------------------
| Load blacklist
|--------------------------------------------------------------------------
*/

async function loadBlacklist()
{
    const tbody =
        document.getElementById('blacklist-table-body');

    const count =
        document.getElementById('blacklist-count');

    try {

        const response = await fetch(
            "{{ route('admin.telemetry.security.blacklist.list') }}",
            {
                headers: {
                    'Accept': 'application/json'
                }
            }
        );


        if (!response.ok) {
            throw new Error(
                'Unable to load blacklist.'
            );
        }


        const data =
            await response.json();


        /*
        |--------------------------------------------------------------------------
        | Support either:
        |
        | { blacklisted_ips: [...] }
        |
        | or
        |
        | { data: [...] }
        |--------------------------------------------------------------------------
        */

        const ips =
            data.blacklisted_ips
            ?? data.data
            ?? [];


        count.textContent =
            ips.length + ' entries';


        if (ips.length === 0) {

            tbody.innerHTML = `
                <tr>
                    <td colspan="6"
                        class="text-center py-4 text-muted">
                        No blacklisted IP addresses.
                    </td>
                </tr>
            `;

            return;
        }


        tbody.innerHTML =
            ips.map(ip => renderBlacklistRow(ip)).join('');

    }

    catch (error) {

        console.error(error);

        tbody.innerHTML = `
            <tr>
                <td colspan="6"
                    class="text-center py-4 text-danger">
                    Failed to load blacklist.
                </td>
            </tr>
        `;

        count.textContent = 'Error';

    }

}


/*
|--------------------------------------------------------------------------
| Render row
|--------------------------------------------------------------------------
*/

function renderBlacklistRow(ip)
{

    const active =
        ip.is_active ??
        ip.active ??
        false;


    const status =
        active
            ? `
                <span class="badge bg-danger">
                    Active
                </span>
              `
            : `
                <span class="badge bg-secondary">
                    Inactive
                </span>
              `;


    const created =
        ip.created_at
            ? new Date(ip.created_at)
                .toLocaleString()
            : '—';


    const addedBy =
        ip.user?.name
        ?? ip.user?.email
        ?? 'System';


    let actions = '';


    if (active) {

        actions += `
            <form method="POST"
                  action="/admin/telemetry/security/blacklist/${ip.id}/deactivate"
                  class="d-inline">

                <input type="hidden"
                       name="_token"
                       value="${csrfToken()}">

                <input type="hidden"
                       name="_method"
                       value="PATCH">

                <button
                    type="submit"
                    class="btn btn-sm btn-warning">
                    Deactivate
                </button>

            </form>
        `;

    } else {

        actions += `
            <form method="POST"
                  action="/admin/telemetry/security/blacklist/${ip.id}/activate"
                  class="d-inline">

                <input type="hidden"
                       name="_token"
                       value="${csrfToken()}">

                <input type="hidden"
                       name="_method"
                       value="PATCH">

                <button
                    type="submit"
                    class="btn btn-sm btn-success">
                    Activate
                </button>

            </form>
        `;

    }


    actions += `
        <form method="POST"
              action="/admin/telemetry/security/blacklist/${ip.id}"
              class="d-inline"
              onsubmit="return confirm(
                  'Permanently delete this blacklist entry?'
              );">

            <input type="hidden"
                   name="_token"
                   value="${csrfToken()}">

            <input type="hidden"
                   name="_method"
                   value="DELETE">

            <button
                type="submit"
                class="btn btn-sm btn-outline-danger">
                Delete
            </button>

        </form>
    `;


    return `
        <tr>

            <td>
                <code>
                    ${escapeHtml(ip.ip_address ?? '—')}
                </code>
            </td>

            <td>
                ${escapeHtml(ip.reason ?? '—')}
            </td>

            <td>
                ${status}
            </td>

            <td>
                ${escapeHtml(addedBy)}
            </td>

            <td>
                ${escapeHtml(created)}
            </td>

            <td class="text-end">
                ${actions}
            </td>

        </tr>
    `;

}


/*
|--------------------------------------------------------------------------
| CSRF token
|--------------------------------------------------------------------------
*/

function csrfToken()
{
    return document
        .querySelector('meta[name="csrf-token"]')
        ?.getAttribute('content')
        ?? '';
}


/*
|--------------------------------------------------------------------------
| Escape HTML
|--------------------------------------------------------------------------
*/

function escapeHtml(value)
{

    const div =
        document.createElement('div');

    div.textContent =
        value;

    return div.innerHTML;

}

</script>

@endsection