@section('title', "Create a new image post")

<x-guest-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create a new image post') }}
        </h2>
    </x-slot>


    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                     <div class="mt-3">
                        <form method="post" action="{{ route('imagepost.store') }}" enctype="multipart/form-data"                        class="mt-6 space-y-6">
                            @csrf
                            <div>
                                <x-input-label for="title" :value="__('Create a catchy title')" class="font-bold font-medium" />
                                <x-text-input id="title" name="title" type="text" class="mt-1 block w-full"
                                    required autofocus />
                                <x-input-error class="mt-2" :messages="$errors->get('name')" />
                            </div>
                            <x-file-input id="image_file" name="image_file"  class="mt-1 block w-full"
                            required autofocus />

                            <x-primary-button name="submit">{{ __('Create posts') }}</x-primary-button>

                        </form>

                    </div>

                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
