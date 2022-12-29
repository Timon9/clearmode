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

                    <header>
                        <h2 class="font-semibold text-xl text-gray-900">
                            {{ __('Create a new team') }}
                        </h2>

                    </header>

                    <form method="post" action="{{ route('teams.store') }}" class="mt-6 space-y-6">
                        @csrf
                        <div>
                            <x-input-label for="name" :value="__('Team name')" class="font-bold font-medium" />
                            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full"
                                required autofocus />
                            <x-input-error class="mt-2" :messages="$errors->get('name')" />
                        </div>

                        <!-- Start: Visibility -->
                        <div class="mt-1 rounded-md bg-white shadow-xs border-t border-b border-gray-200 py-3">
                            <div class="mt-1">
                                  <!-- Private -->
                                  <div class="flex items-center mt-4">
                                    <label for="private" class="relative flex items-start">
                                        <div class=" inset-y-0 left-0 flex items-center pl-3">
                                            <input type="radio" id="private" name="visibility" value="private" required checked class="form-radio h-4 w-4 text-indigo-600 transition duration-150 ease-in-out" />
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-md font-medium leading-5 text-gray-900">
                                                <i class="fas fa-lock"></i>
                                                <span class="font-bold text-sm">
                                                    {{ __('Private') }}
                                                </span>
                                            </div>
                                            <div class="text-sm leading-5 text-gray-500">
                                                {{ __('Only invited users are allowed to join.') }}
                                            </div>
                                        </div>
                                    </label>
                                </div>
                                <!-- Public -->
                                <div class="flex items-center mt-3">
                                    <label for="public" class="relative flex items-start">
                                        <div class=" inset-y-0 left-0 flex items-center pl-3">
                                            <input type="radio" id="public" name="visibility" value="public" required class="form-radio h-4 w-4 text-indigo-600 transition duration-150 ease-in-out" />
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-md font-medium leading-5 text-gray-900">
                                                <i class="fas fa-globe"></i>
                                                <span class="font-bold text-sm">
                                                    {{ __('Public') }}
                                                </span>
                                            </div>
                                            <div class="text-sm leading-5 text-gray-500">
                                                {{ __('Anyone on the internet can join this team.') }}
                                            </div>
                                        </div>
                                    </label>
                                </div>

                            </div>
                        </div>

                        <!-- End: Visibility -->


                        <x-primary-button>{{ __('Create team') }}</x-primary-button>

                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
