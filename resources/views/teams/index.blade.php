@section('title', 'Teams')

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Teams') }}
        </h2>
    </x-slot>


    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <x-primary-link href="{{ route('teams.create') }}">
                        <i class="fas fa-plus mr-2"></i>{{ __('New team') }}
                    </x-primary-link>
                    <!-- List of teams -->
                    <div class="mt-3">
                        @foreach ($teams as $team)
                            <div class="border-b-2 py-4">
                                <div class="">
                                    <strong class="font-bold"> {{ ucfirst($team->name) }}</strong>
                                    <!-- start: visibility -->
                                    @if ($team->public)
                                        <span
                                            class="inline-block bg-orange-100 text-orange-800 text-xs font-bold rounded-full px-3 py-1 ml-2 mb-2">
                                            <i class="fas fa-globe"></i>
                                            {{ __('Public') }}
                                        </span>
                                    @else
                                        <span
                                            class="inline-block bg-blue-100 text-blue-800 text-xs font-bold rounded-full px-3 py-1 ml-2 mb-2">
                                            <i class="fas fa-lock"></i>
                                            {{ __('Private') }}
                                        </span>
                                    @endif

                                    <!-- end: visibility -->

                                    <div class="text-sm leading-5 text-gray-500">
                                        {{ $team->created_at->diffForHumans() }}
                                    </div>
                                    <div class="text-sm leading-5 text-gray-500">
                                        12 members
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
