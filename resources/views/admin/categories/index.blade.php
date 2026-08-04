@extends('layouts.app')
@section('title', 'Manage Categories')

@section('content')

<div class="pagetitle">
    <h1>Manage Categories</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
            <li class="breadcrumb-item">Admin</li>
            <li class="breadcrumb-item active">Categories</li>
        </ol>
    </nav>
</div>

<section class="section">
    <livewire:category-manager />
</section>

@endsection
