@extends('layouts.app')

@section('content')
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Card -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-semibold mb-2">Total Users</h2>
            <p class="text-2xl font-bold">1,245</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-semibold mb-2">Active Sessions</h2>
            <p class="text-2xl font-bold">352</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-semibold mb-2">Server Load</h2>
            <p class="text-2xl font-bold">65%</p>
        </div>
    </div>

    <div class="mt-10 bg-white rounded-lg shadow p-6">
        <h2 class="text-xl font-semibold mb-4">Recent Activity</h2>
        <ul class="space-y-2">
            <li class="border-b pb-2">✅ User John logged in</li>
            <li class="border-b pb-2">📦 Order #1234 processed</li>
            <li class="border-b pb-2">🛠️ Settings updated by Admin</li>
        </ul>
    </div>
@endsection