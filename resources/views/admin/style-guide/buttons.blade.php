@extends('layouts.admin')

@section('title', 'Button Style Guide')

@section('content')
    <div class="space-y-6">
        <div class="flex justify-between items-center">
            <h1 class="text-2xl font-bold text-gray-800">Button Style Guide</h1>
        </div>

        <!-- Standard Buttons -->
        <div class="bg-white rounded-xl shadow-md p-6">
            <h2 class="text-lg font-semibold mb-4">Standard Buttons</h2>
            <div class="flex flex-wrap gap-4 mb-6">
                <button class="btn btn-primary">Primary</button>
                <button class="btn btn-secondary">Secondary</button>
                <button class="btn btn-success">Success</button>
                <button class="btn btn-warning">Warning</button>
                <button class="btn btn-danger">Danger</button>
                <button class="btn btn-info">Info</button>
                <button class="btn btn-dark">Dark</button>
            </div>

            <h3 class="text-sm font-medium text-gray-500 mb-2">Code</h3>
            <div class="bg-gray-100 p-4 rounded-lg overflow-x-auto">
                <code class="text-sm text-blue-600">
                    &lt;button class="btn btn-primary"&gt;Primary&lt;/button&gt;<br>
                    &lt;button class="btn btn-secondary"&gt;Secondary&lt;/button&gt;
                </code>
            </div>
        </div>

        <!-- Outline Buttons -->
        <div class="bg-white rounded-xl shadow-md p-6">
            <h2 class="text-lg font-semibold mb-4">Outline Buttons</h2>
            <div class="flex flex-wrap gap-4 mb-6">
                <button class="btn btn-outline btn-primary">Primary</button>
                <button class="btn btn-outline btn-secondary">Secondary</button>
                <button class="btn btn-outline btn-success">Success</button>
                <button class="btn btn-outline btn-warning">Warning</button>
                <button class="btn btn-outline btn-danger">Danger</button>
                <button class="btn btn-outline btn-info">Info</button>
            </div>
        </div>

        <!-- Button Sizes -->
        <div class="bg-white rounded-xl shadow-md p-6">
            <h2 class="text-lg font-semibold mb-4">Button Sizes</h2>
            <div class="flex items-center gap-4 mb-6">
                <button class="btn btn-primary btn-sm">Small Button</button>
                <button class="btn btn-primary btn-md">Medium Button</button>
                <button class="btn btn-primary btn-lg">Large Button</button>
            </div>

            <h3 class="text-sm font-medium text-gray-500 mb-2">Code</h3>
            <div class="bg-gray-100 p-4 rounded-lg overflow-x-auto">
                <code class="text-sm text-blue-600">
                    &lt;button class="btn btn-primary btn-sm"&gt;Small&lt;/button&gt;<br>
                    &lt;button class="btn btn-primary btn-md"&gt;Medium&lt;/button&gt;<br>
                    &lt;button class="btn btn-primary btn-lg"&gt;Large&lt;/button&gt;
                </code>
            </div>
        </div>

        <!-- Icon Buttons -->
        <div class="bg-white rounded-xl shadow-md p-6">
            <h2 class="text-lg font-semibold mb-4">Icon Buttons</h2>
            <div class="flex flex-wrap gap-4 mb-6">
                <button class="btn btn-primary btn-icon" title="Edit">
                    <i class='bx bx-pencil'></i>
                </button>
                <button class="btn btn-danger btn-icon" title="Delete">
                    <i class='bx bx-trash'></i>
                </button>
                <button class="btn btn-success btn-icon" title="Approve">
                    <i class='bx bx-check'></i>
                </button>
                <button class="btn btn-secondary btn-icon" title="View">
                    <i class='bx bx-show'></i>
                </button>
            </div>

            <h3 class="text-sm font-medium text-gray-500 mb-2">Buttons with Text & Icon</h3>
            <div class="flex flex-wrap gap-4">
                <button class="btn btn-primary">
                    <i class='bx bx-plus-circle mr-2'></i> Add New
                </button>
                <button class="btn btn-danger">
                    <i class='bx bx-trash mr-2'></i> Delete Item
                </button>
                <button class="btn btn-secondary">
                    <i class='bx bx-filter-alt mr-2'></i> Filter
                </button>
            </div>
        </div>

        <!-- Block Buttons -->
        <div class="bg-white rounded-xl shadow-md p-6">
            <h2 class="text-lg font-semibold mb-4">Block Buttons</h2>
            <div class="space-y-4 max-w-md">
                <button class="btn btn-primary btn-block">Block Button</button>
                <button class="btn btn-outline btn-secondary btn-block">Block Outline Button</button>
            </div>
        </div>
    </div>
@endsection