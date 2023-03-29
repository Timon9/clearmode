@section('title', $imagePost->title)

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $imagePost->title }}
        </h2>
    </x-slot>


    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                     <div class="mt-3">
                      <h1>{{$imagePost->title}}</h1>
                      <img src="{{$imagePost->url}}" alt="{{$imagePost->title}}" id="imagePost"/>
                    </div>
                    {{-- <x-danger-button id="delete_post" >{{ __('Delete') }}</x-danger-button> --}}
                    <x-delete-dialog />


                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
