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

                    <!-- Toolbar -->
                    <div class="border-b-2 py-4">
                        <!-- Start: Search -->
                        <form action="{{ route('teams.index') }}" method="GET" class="flex items-center mb-4">
                            <x-text-input id="search" class="block mt-1 w-full" type="text" name="search"
                                placeholder="{{ __('Search teams...') }}" :value="$search" />
                        </form>
                        @if (!empty($search))
                            <p class="mb-4 font-bold text-sm text-gray-700">{{ $teams->total() }}
                                {{ __('results for') }}
                                "{{ $search }}". <a href="{{ route('teams.index') }}"
                                    class="text-gray-700 hover:text-blue-800 underline">Clear filter</a></p>
                        @endif
                        <!-- End: Search -->

                        <x-primary-link href="{{ route('teams.create') }}">
                            <i class="fas fa-plus mr-2"></i>{{ __('New team') }}
                        </x-primary-link>

                    </div>

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
                                        {{ $team->users->count() }} @choice('member|members', $team->users->count())
                                    </div>

                                    <!-- Start: Team members -->
                                    <div class="flex flex-wrap w-400">
                                        @foreach ($team->users as $user)
                                            <div class="mr-2">
                                                <img src="{{ $user->avatar(36, 36, 23) }}" alt="{{ $user->name }}"
                                                    class="w-9 h-9 rounded-full">
                                            </div>
                                        @endforeach
                                    </div>




                                    <!-- End: Team members -->

                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-3">
                        {{ $teams->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
