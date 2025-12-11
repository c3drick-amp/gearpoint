@extends('layouts.app')

@section('title', 'Customers - Motorshop POS')

@section('content')
<h2 style="margin-bottom: 1.5rem;">Customer Management</h2>

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
    <div class="card-header">Add New Customer</div>
    
    <form action="{{ route('customers.store') }}" method="POST">
        @csrf
        <div class="form-row">
            <div class="form-group">
                <label class="form-label">First Name *</label>
                <input type="text" name="first_name" class="form-control" placeholder="e.g., Juan" value="{{ old('first_name') }}" required>
                <span class="form-hint">💡 Customer's first name</span>
            </div>

            <div class="form-group">
                <label class="form-label">Last Name *</label>
                <input type="text" name="last_name" class="form-control" placeholder="e.g., Dela Cruz" value="{{ old('last_name') }}" required>
                <span class="form-hint">💡 Customer's last name</span>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label class="form-label">Contact Number *</label>
                <input type="tel" name="phone" class="form-control" placeholder="e.g., 09123456789" value="{{ old('phone') }}" required>
                <span class="form-hint">💡 Mobile or telephone number</span>
            </div>

            <div class="form-group">
                <label class="form-label">Email Address</label>
                <input type="email" name="email" class="form-control" placeholder="customer@example.com" value="{{ old('email') }}">
                <span class="form-hint">💡 For receipts and notifications (optional)</span>
            </div>
        </div>

        <div class="form-group">
            <label class="form-label">Address</label>
            <textarea name="address" class="form-control" rows="2" placeholder="Street, Barangay, City, Province">{{ old('address') }}</textarea>
            <span class="form-hint">💡 Complete address for delivery</span>
        </div>

        <div class="form-group">
            <label class="form-label">Vehicle Information</label>
            <input type="text" name="vehicle_info" class="form-control" placeholder="e.g., Honda Wave 125, ABC-1234" value="{{ old('vehicle_info') }}">
            <span class="form-hint">💡 Customer's motorcycle details (model, plate number)</span>
        </div>

        <button type="submit" class="btn btn-primary">Add Customer</button>
    </form>
</div>

<div class="card" style="margin-top: 1.5rem;">
    <div class="card-header">Customer List ({{ $customers->total() }} customers)</div>
    
    <div style="margin-bottom: 1rem;">
        <form action="{{ route('customers') }}" method="GET" style="display: flex; gap: 0.5rem;">
            <input type="text" name="search" class="form-control" placeholder="Search by name, phone, or vehicle..." value="{{ request('search') }}" style="flex: 1;">
            <button type="submit" class="btn btn-primary">Search</button>
            @if(request('search'))
            <a href="{{ route('customers') }}" class="btn btn-danger">Clear</a>
            @endif
        </form>
    </div>

    @if(count($customers) > 0)
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Contact</th>
                <th>Email</th>
                <th>Vehicle</th>
                <th>Joined</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($customers as $customer)
            <tr>
                <td>#{{ $customer->id }}</td>
                <td><strong>{{ $customer->first_name }} {{ $customer->last_name }}</strong></td>
                <td>{{ $customer->phone }}</td>
                <td>{{ $customer->email ?? 'N/A' }}</td>
                <td>{{ $customer->vehicle_info ?? 'N/A' }}</td>
                <td>{{ $customer->created_at->format('M d, Y') }}</td>
                <td>
                    <a href="{{ route('customers.edit', $customer->id) }}" class="btn btn-primary" style="padding: 0.5rem 1rem; font-size: 0.9rem;">Edit</a>
                    <form action="{{ route('customers.destroy', $customer->id) }}" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" style="padding: 0.5rem 1rem; font-size: 0.9rem;" onclick="return confirm('Are you sure you want to delete this customer?')">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div style="margin-top: 1rem;">
        {{ $customers->links() }}
    </div>
    @else
    <p style="text-align: center; color: #7f8c8d; padding: 1rem;">
        @if(request('search'))
        No customers found matching "{{ request('search') }}"
        @else
        No customers registered yet
        @endif
    </p>
    @endif
</div>
@endsection