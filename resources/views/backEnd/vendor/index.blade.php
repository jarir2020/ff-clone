@extends('backEnd.layouts.master')

@section('title', 'Vendor Management')

@section('css')
<style>
    .vendor-page .page-title-box {
        margin: 18px 0 22px;
    }
    .vendor-page .page-title-box h4 {
        color: #2d3436;
        font-weight: 700;
        margin-bottom: 4px;
    }
    .vendor-page .page-title-box p {
        color: #8391a2;
        font-size: 13px;
    }
    .vendor-stat,
    .vendor-card {
        border: 0;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(18, 38, 63, .05);
        background: #fff;
    }
    .vendor-stat {
        padding: 18px 20px;
        height: 100%;
        border-left: 4px solid #727cf5;
    }
    .vendor-stat.active { border-left-color: #0acf97; }
    .vendor-stat.verified { border-left-color: #39afd1; }
    .vendor-stat.pending { border-left-color: #ffbc00; }
    .vendor-stat-label {
        color: #8391a2;
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: .45px;
        font-weight: 600;
    }
    .vendor-stat-value {
        color: #313a5e;
        font-size: 25px;
        font-weight: 700;
        line-height: 1.2;
        margin-top: 5px;
    }
    .vendor-card .card-header {
        background: #fff;
        border-bottom: 1px solid #eef2f7;
        padding: 17px 20px;
        font-weight: 700;
        color: #313a5e;
    }
    .vendor-card .card-body { padding: 20px; }
    .vendor-filter .form-control {
        border-color: #e6eaf0;
        border-radius: 8px;
        min-height: 40px;
    }
    .vendor-table { margin-bottom: 0; }
    .vendor-table thead th {
        background: #f9fbfd;
        color: #8391a2;
        border-bottom: 1px solid #eef2f7;
        font-size: 11px;
        font-weight: 700;
        letter-spacing: .4px;
        padding: 13px 14px;
        text-transform: uppercase;
        white-space: nowrap;
    }
    .vendor-table tbody td {
        border-bottom: 1px solid #f1f4f7;
        color: #313a5e;
        font-size: 13px;
        padding: 14px;
        vertical-align: middle;
    }
    .vendor-table tbody tr:last-child td { border-bottom: 0; }
    .vendor-profile {
        align-items: center;
        display: flex;
        gap: 10px;
        min-width: 190px;
    }
    .vendor-avatar {
        align-items: center;
        background: #eef2ff;
        border-radius: 10px;
        color: #5362d8;
        display: inline-flex;
        flex: 0 0 42px;
        font-size: 16px;
        font-weight: 700;
        height: 42px;
        justify-content: center;
        object-fit: cover;
        width: 42px;
    }
    .vendor-name { color: #343a40; font-weight: 700; }
    .vendor-meta { color: #98a6ad; font-size: 11px; margin-top: 2px; }
    .vendor-contact { line-height: 1.7; white-space: nowrap; }
    .vendor-contact i { color: #98a6ad; width: 16px; }
    .vendor-pill {
        border-radius: 50rem;
        display: inline-block;
        font-size: 11px;
        font-weight: 600;
        padding: 5px 9px;
        white-space: nowrap;
    }
    .vendor-pill.active,
    .vendor-pill.verified { background: rgba(10, 207, 151, .14); color: #07966e; }
    .vendor-pill.inactive,
    .vendor-pill.rejected { background: rgba(250, 92, 124, .14); color: #d83d60; }
    .vendor-pill.pending { background: rgba(255, 188, 0, .17); color: #a97600; }
    .vendor-actions { display: inline-flex; gap: 6px; white-space: nowrap; }
    .vendor-action {
        align-items: center;
        background: #f9fbfd;
        border: 0;
        border-radius: 50%;
        color: #6c757d;
        display: inline-flex;
        height: 31px;
        justify-content: center;
        text-decoration: none;
        transition: all .2s ease;
        width: 31px;
    }
    .vendor-action:hover { background: #eef2f7; color: #343a40; transform: translateY(-1px); }
    .vendor-action.activate:hover { color: #07966e; }
    .vendor-action.deactivate:hover { color: #a97600; }
    .vendor-action.delete:hover { color: #d83d60; }
    .vendor-empty { color: #8391a2; padding: 48px 20px !important; text-align: center; }
    .vendor-empty i { display: block; font-size: 30px; margin-bottom: 10px; opacity: .35; }
    @media (max-width: 767.98px) {
        .vendor-page .page-title-box { align-items: flex-start !important; }
        .vendor-table { min-width: 980px; }
    }
</style>
@endsection

@section('content')
<div class="container-fluid vendor-page">
    <div class="page-title-box d-flex flex-wrap align-items-center justify-content-between gap-2">
        <div>
            <h4>Vendor Management</h4>
            <p class="mb-0">Manage vendor accounts, products, wallets, and verification status.</p>
        </div>
        <a href="{{ route('admin.vendor.verification.index') }}" class="btn btn-outline-primary rounded-pill px-3">
            <i class="fas fa-shield-alt me-1"></i> Verification Queue
        </a>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-6 col-xl-3">
            <div class="vendor-stat">
                <div class="vendor-stat-label">Total Vendors</div>
                <div class="vendor-stat-value">{{ $stats['total'] }}</div>
            </div>
        </div>
        <div class="col-6 col-xl-3">
            <div class="vendor-stat active">
                <div class="vendor-stat-label">Active Vendors</div>
                <div class="vendor-stat-value">{{ $stats['active'] }}</div>
            </div>
        </div>
        <div class="col-6 col-xl-3">
            <div class="vendor-stat verified">
                <div class="vendor-stat-label">Verified Vendors</div>
                <div class="vendor-stat-value">{{ $stats['verified'] }}</div>
            </div>
        </div>
        <div class="col-6 col-xl-3">
            <div class="vendor-stat pending">
                <div class="vendor-stat-label">Pending Review</div>
                <div class="vendor-stat-value">{{ $stats['pending'] }}</div>
            </div>
        </div>
    </div>

    <div class="vendor-card mb-4">
        <div class="card-header"><i class="fas fa-search me-2 text-primary"></i>Search Vendors</div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.vendors.index') }}" class="vendor-filter row g-2 align-items-end">
                <div class="col-md-8 col-lg-9">
                    <label for="vendor-keyword" class="form-label small text-muted mb-1">Keyword</label>
                    <input id="vendor-keyword" type="text" name="keyword" class="form-control"
                           value="{{ request('keyword') }}" placeholder="Shop name, owner, email, or phone">
                </div>
                <div class="col-md-4 col-lg-3 d-flex gap-2">
                    <button type="submit" class="btn btn-primary flex-grow-1"><i class="fas fa-search me-1"></i> Search</button>
                    @if(request('keyword'))
                        <a href="{{ route('admin.vendors.index') }}" class="btn btn-light border" title="Reset search"><i class="fas fa-redo"></i></a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <div class="vendor-card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span><i class="fas fa-store me-2 text-primary"></i>Vendor List</span>
            <span class="badge bg-light text-dark border">{{ $vendors->total() }} vendors</span>
        </div>
        <div class="table-responsive">
            <table class="table vendor-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Vendor</th>
                        <th>Contact</th>
                        <th>Products</th>
                        <th>Wallet</th>
                        <th>Verification</th>
                        <th>Status</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($vendors as $vendor)
                        @php
                            $vendorName = $vendor->shop_name ?: $vendor->owner_name;
                            $initial = strtoupper(substr($vendorName ?: 'V', 0, 1));
                        @endphp
                        <tr>
                            <td class="text-muted">{{ $loop->iteration + (($vendors->currentPage() - 1) * $vendors->perPage()) }}</td>
                            <td>
                                <div class="vendor-profile">
                                    @if($vendor->logo)
                                        <img src="{{ asset($vendor->logo) }}" alt="{{ $vendorName }}" class="vendor-avatar">
                                    @else
                                        <span class="vendor-avatar">{{ $initial }}</span>
                                    @endif
                                    <div>
                                        <div class="vendor-name">{{ $vendorName }}</div>
                                        <div class="vendor-meta">ID #{{ $vendor->id }} · {{ $vendor->owner_name }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="vendor-contact">
                                    <div><i class="fas fa-phone-alt"></i>{{ $vendor->phone }}</div>
                                    <div class="text-muted"><i class="fas fa-envelope"></i>{{ Str::limit($vendor->email, 26) }}</div>
                                </div>
                            </td>
                            <td>{{ $vendor->products->count() }}</td>
                            <td>৳{{ number_format((float) optional($vendor->wallet)->balance, 2) }}</td>
                            <td>
                                @if($vendor->verification_status === 'approved')
                                    <span class="vendor-pill verified">Verified</span>
                                @elseif($vendor->verification_status === 'rejected')
                                    <span class="vendor-pill rejected">Rejected</span>
                                @else
                                    <span class="vendor-pill pending">Pending</span>
                                @endif
                            </td>
                            <td>
                                @if((int) $vendor->status === 1)
                                    <span class="vendor-pill active">Active</span>
                                @else
                                    <span class="vendor-pill inactive">Inactive</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <div class="vendor-actions">
                                    <form method="POST" action="{{ route('admin.vendors.toggle-status', $vendor->id) }}" class="d-inline">
                                        @csrf
                                        <button type="submit" class="vendor-action {{ (int) $vendor->status === 1 ? 'deactivate' : 'activate' }}" title="{{ (int) $vendor->status === 1 ? 'Deactivate' : 'Activate' }}">
                                            <i class="fas {{ (int) $vendor->status === 1 ? 'fa-ban' : 'fa-check' }}"></i>
                                        </button>
                                    </form>
                                    <a href="{{ route('admin.vendors.edit', $vendor->id) }}" class="vendor-action" title="Edit"><i class="fas fa-pen"></i></a>
                                    <form method="POST" action="{{ route('admin.vendors.destroy', $vendor->id) }}" class="d-inline" onsubmit="return confirm('Delete this vendor and its linked account?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="vendor-action delete" title="Delete"><i class="fas fa-trash-alt"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="vendor-empty"><i class="fas fa-store-slash"></i>No vendors found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($vendors->hasPages())
            <div class="card-body border-top d-flex justify-content-end">
                {{ $vendors->links('pagination::bootstrap-4') }}
            </div>
        @endif
    </div>
</div>
@endsection
