@extends('layouts.app')

@section('title', 'Services - Motorshop POS')

@section('content')
<h2 style="margin-bottom: 1.5rem;">Service Management</h2>

@if(session('success'))
<div style="background: #d4edda; border: 1px solid #c3e6cb; color: #155724; padding: 1rem; border-radius: 4px; margin-bottom: 1rem;">
    {{ session('success') }}
</div>
@endif

@if($errors->any())
<div style="background: #f8d7da; border: 1px solid #f5c6cb; color: #721c24; padding: 1rem; border-radius: 4px; margin-bottom: 1rem;">
    <ul style="margin: 0; padding-left: 1.5rem;">
        @foreach($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<div class="card">
    <div class="card-header">Add New Service</div>
    
    <form action="{{ route('services.store') }}" method="POST">
        @csrf
        <div class="form-row">
            <div class="form-group">
                <label class="form-label">Service Name *</label>
                <input type="text" name="name" class="form-control" placeholder="e.g., Oil Change, Tire Replacement" value="{{ old('name') }}" required>
                <span class="form-hint">💡 Name of the service offered</span>
            </div>

            <div class="form-group">
                <label class="form-label">Service Code</label>
                <input type="text" name="code" class="form-control" placeholder="e.g., SVC-001" value="{{ old('code') }}">
                <span class="form-hint">💡 Unique code for this service</span>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label class="form-label">Service Category</label>
                <select name="category" class="form-control">
                    <option value="">-- Select Category --</option>
                    <option value="Maintenance" {{ old('category') == 'Maintenance' ? 'selected' : '' }}>Maintenance</option>
                    <option value="Repair" {{ old('category') == 'Repair' ? 'selected' : '' }}>Repair</option>
                    <option value="Customization" {{ old('category') == 'Customization' ? 'selected' : '' }}>Customization</option>
                    <option value="Installation" {{ old('category') == 'Installation' ? 'selected' : '' }}>Installation</option>
                    <option value="Inspection" {{ old('category') == 'Inspection' ? 'selected' : '' }}>Inspection</option>
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">Service Price (₱) *</label>
                <input type="number" name="labor_fee" class="form-control" placeholder="0.00" step="0.01" value="{{ old('labor_fee') }}" required>
                <span class="form-hint">💡 Standard price for this service</span>
            </div>
        </div>

        <div class="form-group">
            <label class="form-label">Estimated Duration</label>
            <input type="text" name="estimated_duration" class="form-control" placeholder="e.g., 30 minutes, 1 hour, 2-3 hours" value="{{ old('estimated_duration') }}">
            <span class="form-hint">💡 How long this service typically takes</span>
        </div>

        <div class="form-group">
            <label class="form-label">Service Description</label>
            <textarea name="description" class="form-control" rows="3" placeholder="Detailed description of what's included in this service...">{{ old('description') }}</textarea>
            <span class="form-hint">💡 What does this service include?</span>
        </div>

        <button type="submit" class="btn btn-primary">Add Service</button>
    </form>
</div>

<div class="card" style="margin-top: 1.5rem;">
    <div class="card-header">Available Services ({{ $services->total() }} services)</div>
    
    <div style="margin-bottom: 1rem;">
        <form action="{{ route('services') }}" method="GET" style="display: flex; gap: 0.5rem;">
            <input type="text" name="search" class="form-control" placeholder="Search by name, code, or category..." value="{{ request('search') }}" style="flex: 1;">
            <button type="submit" class="btn btn-primary">Search</button>
            @if(request('search'))
            <a href="{{ route('services') }}" class="btn btn-danger">Clear</a>
            @endif
        </form>
    </div>

    @if(count($services) > 0)
    <table class="table">
        <thead>
            <tr>
                <th>Code</th>
                <th>Service Name</th>
                <th>Category</th>
                <th>Labor Fee</th>
                <th>Duration</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($services as $service)
            <tr>
                <td>{{ $service->code ?? 'N/A' }}</td>
                <td><strong>{{ $service->name }}</strong></td>
                <td>{{ $service->category ?? 'N/A' }}</td>
                <td>₱{{ number_format($service->labor_fee, 2) }}</td>
                <td>{{ $service->estimated_duration ?? 'N/A' }}</td>
                <td>
                    <form action="{{ route('services.destroy', $service->id) }}" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" style="padding: 0.5rem 1rem; font-size: 0.9rem;" onclick="return confirm('Are you sure you want to delete this service?')">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div style="margin-top: 1rem;">
        {{ $services->links() }}
    </div>
    @else
    <p style="text-align: center; color: #7f8c8d; padding: 1rem;">
        @if(request('search'))
        No services found matching "{{ request('search') }}"
        @else
        No services available yet
        @endif
    </p>
    @endif
</div>
@endsection