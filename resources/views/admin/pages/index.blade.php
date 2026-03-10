@extends('layouts.admin')

@section('title', 'Pages Management')
@section('page-title', 'Pages Management')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="bg-gradient-to-r from-indigo-600 to-purple-600 rounded-2xl p-6 text-white shadow-lg relative overflow-hidden">
            <!-- Decorative background elements -->
            <div class="absolute top-0 right-0 -mt-4 -mr-4 w-32 h-32 bg-white/10 rounded-full blur-2xl"></div>
            <div class="absolute bottom-0 left-0 -mb-8 -ml-8 w-40 h-40 bg-white/10 rounded-full blur-3xl"></div>
        <div class="flex items-center gap-4">
    <div class="shrink-0 w-12 h-12 bg-white/20 backdrop-blur-md rounded-xl flex items-center justify-center shadow-inner border border-white/20 transform transition-transform hover:scale-105">
        <i class='hgi-stroke hgi-file-01 text-2xl'></i>
    </div>
    <div>
        <h2 class="text-2xl font-bold">Pages</h2>
        <p class="text-indigo-100 text-sm mt-1">Module Visibility Control</p>
    </div>
</div>
    </div>

    <!-- Module Visibility Section -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-5 border-b border-gray-100 bg-gray-50/50">
            <div class="flex items-center gap-2">
                <i class='hgi-stroke hgi-toggle-off text-indigo-600'></i>
                <h3 class="font-semibold text-gray-700">Module Visibility Control</h3>
            </div>
            <p class="text-sm text-gray-500 mt-1">Control which modules appear on the public website</p>
        </div>
        <div class="p-5">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($modules as $module)
                    @php
                        $type = $module['type'];
                        $config = $module['config'];
                        $page = $module['page'];
                        $isPublished = $page ? $page->is_published : false;
                    @endphp
                    <div class="border border-gray-200 rounded-xl p-4 hover:shadow-md transition {{ $isPublished ? 'bg-green-50/50 border-green-200' : 'bg-gray-50/50' }}">
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-lg bg-{{ $config['color'] }}-100 flex items-center justify-center">
                                    <i class='hgi-stroke {{ $config['icon'] }} text-xl text-{{ $config['color'] }}-600'></i>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-gray-900">{{ $config['name'] }}</h4>
                                    <p class="text-xs text-gray-500">Module</p>
                                </div>
                            </div>
                            @if($isPublished)
                                <span class="px-2 py-1 rounded-lg text-xs font-semibold bg-green-100 text-green-700">
                                    <i class='hgi-stroke hgi-checkmark-circle-02'></i> Visible
                                </span>
                            @else
                                <span class="px-2 py-1 rounded-lg text-xs font-semibold bg-gray-100 text-gray-600">
                                    <i class='hgi-stroke hgi-cancel-circle'></i> Hidden
                                </span>
                            @endif
                        </div>
                        
                        <div class="space-y-2">
                            @if($page)
                                <form action="{{ route('admin.pages.toggle-status', $page->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" 
                                        class="w-full px-3 py-2 rounded-lg text-sm font-medium transition-all
                                        {{ $isPublished ? 'bg-red-100 text-red-700 hover:bg-red-200' : 'bg-green-100 text-green-700 hover:bg-green-200' }}">
                                        <i class='hgi-stroke {{ $isPublished ? 'hgi-view-off-slash' : 'hgi-eye' }} mr-1'></i>
                                        {{ $isPublished ? 'Hide' : 'Show' }} Module
                                    </button>
                                </form>
                                
                                <!-- Order Control -->
                                <form action="{{ route('admin.pages.update-order', $page->id) }}" method="POST" class="flex gap-2">
                                    @csrf
                                    <input type="number" name="order" value="{{ $page->order ?? 0 }}" min="0" 
                                        class="flex-1 px-3 py-2 rounded-lg border border-gray-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition-all text-sm">
                                    <button type="submit" 
                                        class="px-4 py-2 bg-indigo-100 text-indigo-700 rounded-lg text-sm font-medium hover:bg-indigo-200 transition">
                                        <i class='hgi-stroke hgi-checkmark-circle-02 mr-1'></i> Update
                                    </button>
                                </form>
                            @else
                                <p class="text-xs text-gray-500 text-center py-2">Page not created yet</p>
                            @endif
                            
                            <div class="flex gap-2">
                                @if($type !== 'about')
                                    <a href="{{ route($config['admin_route']) }}" 
                                       class="flex-1 px-3 py-2 bg-{{ $config['color'] }}-100 text-{{ $config['color'] }}-700 rounded-lg text-sm font-medium hover:bg-{{ $config['color'] }}-200 transition text-center">
                                        <i class='hgi-stroke hgi-settings-01 mr-1'></i> Manage
                                    </a>
                                @else
                                    <a href="{{ route($config['admin_route']) }}" 
                                       class="flex-1 px-3 py-2 bg-blue-100 text-blue-700 rounded-lg text-sm font-medium hover:bg-blue-200 transition text-center">
                                        <i class='hgi-stroke hgi-pencil-edit-01 mr-1'></i> Edit
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
