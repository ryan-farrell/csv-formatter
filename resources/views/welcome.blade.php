@extends('layouts.main')

@section('content')
    <div class='flex flex-wrap items-center justify-center min-h-screen bg-gradient-to-br'>
        <div class='w-full max-w-xl px-10 py-8 mx-auto bg-white rounded-lg shadow-xl dark:bg-gray-800 dark:border-gray-700 '>
            <h1 class="text-center font-mono font-bold text-gray-900 dark:text-white text-lg leading-tight border-b pb-4">Homeowner CSV Uploader</h1>
            <div class="pt-8">
                <p class="font-mono font-bold text-gray-900 dark:text-white text-lg  pb-4">Want to reformat your Homeowner CSV file?</p>
                <div class="flex space-x-2 ">
                    <a href="{{ route('csv-upload') }}" type="button" class="font-mono text-center block w-full text-white font-normal py-2 px-4 rounded transition duration-300 ease-in-out focus:outline-none focus:shadow-outline bg-blue-700 border border-blue-700 hover:bg-blue-900 hover:border-blue-900">Click Here</a>
                </div>
            </div>
        </div>
    </div>
@endsection
