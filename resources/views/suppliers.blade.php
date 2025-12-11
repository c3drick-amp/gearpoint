@extends('layouts.app')

@section('title', 'Suppliers - Motorshop POS')

@section('content')
<h2 style="margin-bottom: 1.5rem;">Supplier Management</h2>

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
    <div class="card-header">Add New Supplier</div>
    
    <form action="{{ route('suppliers.store') }}" method="POST">
        @csrf
        <div class="form-row">
            <div class="form-group">
                <label class="form-label">Company Name *</label>
                <input type="text" name="name" class="form-control" placeholder="e.g., ABC Motor Parts Supplier" value="{{ old('name') }}" required>
                <span class="form-hint">💡 Official supplier company name</span>
            </div>

            <div class="form-group">
                <label class="form-label">Contact Person</label>
                <input type="text" name="contact_person" class="form-control" placeholder="e.g., Maria Santos" value="{{ old('contact_person') }}">
                <span class="form-hint">💡 Main contact person's name</span>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label class="form-label">Phone Number *</label>
                <input type="tel" name="phone" class="form-control" placeholder="e.g., 02-1234-5678" value="{{ old('phone') }}" required>
                <span class="form-hint">💡 Primary contact number</span>
            </div>

            <div class="form-group">
                <label class="form-label">Email Address</label>
                <input type="email" name="email" class="form-control" placeholder="supplier@example.com" value="{{ old('email') }}">
                <span class="form-hint">💡 For purchase orders and invoices</span>
            </div>
        </div>

        <div class="form-group">
            <label class="form-label">Address *</label>
            <textarea name="address" class="form-control" rows="2" placeholder="Complete business address" required>{{ old('address') }}</textarea>
            <span class="form-hint">💡 Warehouse or office location</span>
        </div>

        <div class="form-group">
            <label class="form-label">Payment Terms</label>
            <select name="payment_terms" class="form-control">
                <option value="Cash on Delivery" {{ old('payment_terms') == 'Cash on Delivery' ? 'selected' : '' }}>Cash on Delivery</option>
                <option value="15 Days Credit" {{ old('payment_terms') == '15 Days Credit' ? 'selected' : '' }}>15 Days Credit</option>
                <option value="30 Days Credit" {{ old('payment_terms') == '30 Days Credit' ? 'selected' : '' }}>30 Days Credit</option>
                <option value="45 Days Credit" {{ old('payment_terms') == '45 Days Credit' ? 'selected' : '' }}>45 Days Credit</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Add Supplier</button>
    </form>
</div>

<div class="card" style="margin-top: 1.5rem;">
    <div class="card-header">Supplier List ({{ $suppliers->total() }} suppliers)</div>
    
    <div style="margin-bottom: 1rem;">
        <form action="{{ route('suppliers') }}" method="GET" style="display: flex; gap: 0.5rem;">
            <input type="text" name="search" class="form-control" placeholder="Search by company, contact person, or phone..." value="{{ request('search') }}" style="flex: 1;">
            <button type="submit" class="btn btn-primary">Search</button>
            @if(request('search'))
            <a href="{{ route('suppliers') }}" class="btn btn-danger">Clear</a>
            @endif
        </form>
    </div>

    @if(count($suppliers) > 0)
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Company Name</th>
                <th>Contact Person</th>
                <th>Phone</th>
                <th>Email</th>
                <th>Payment Terms</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($suppliers as $supplier)
            <tr>
                <td>#{{ $supplier->id }}</td>
                <td><strong>{{ $supplier->name }}</strong></td>
                <td>{{ $supplier->contact_person ?? 'N/A' }}</td>
                <td>{{ $supplier->phone }}</td>
                <td>{{ $supplier->email ?? 'N/A' }}</td>
                <td>{{ $supplier->payment_terms ?? 'N/A' }}</td>
                <td>
                    <form action="{{ route('suppliers.destroy', $supplier->id) }}" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" style="padding: 0.5rem 1rem; font-size: 0.9rem;" onclick="return confirm('Are you sure you want to delete this supplier?')">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div style="margin-top: 1rem;">
        {{ $suppliers->links() }}
    </div>
    @else
    <p style="text-align: center; color: #7f8c8d; padding: 1rem;">
        @if(request('search'))
        No suppliers found matching "{{ request('search') }}"
        @else
        No suppliers registered yet
        @endif
    </p>
    @endif
</div>
@endsection