@extends('layouts.main')

@section('content')
<div class="flex flex-wrap items-center justify-center min-h-screen">
    <div class="w-full max-w-md p-4 bg-white border border-gray-200 rounded-lg shadow sm:p-6 md:p-8 dark:bg-gray-800 dark:border-gray-700">
        <div class="items-center">
            @if (count($errors) > 0)
            <div class="flex p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400" role="alert">
                <svg class="flex-shrink-0 inline w-4 h-4 me-3 mt-[2px]" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
                </svg>
                <span class="sr-only">Danger</span>
                <div>
                    <span class="font-medium">Ensure that these requirements are met to upload your file:</span>
                    <ul class="mt-1.5 list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
            @endif
        </div>

        <form method="POST" action="{{ route('upload.csv')  }}" enctype="multipart/form-data" class="space-y-6">
        <h1 class="text-xl  font-mono font-bold font-medium text-gray-900 dark:text-white">CSV File Uploader</h1>
            @method('POST')
            @csrf
            <div class="">
                <label for="csv-upload" class="font-mono block mb-2 text-md font-medium text-gray-900 dark:text-white">Select file</label>
                <input name="file" class="font-mono block w-full text-md text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400" id="csv-upload" type="file" accept=".csv">
                <p class="m-1 font-mono text-sm text-gray-500 dark:text-gray-300" id="file_input_help">CSV (MAX Size 2MB)</p>
            </div>
                <button type="submit" value="submit" class="font-mono block w-full text-white font-normal py-2 px-4 rounded transition duration-300 ease-in-out focus:outline-none focus:shadow-outline bg-green-700 border border-green-700 hover:bg-green-900 hover:border-blue-900">Upload</button>
                <a class="font-mono text-center block w-full text-white font-normal py-2 px-4 rounded transition duration-300 ease-in-out focus:outline-none focus:shadow-outline bg-blue-700 border border-blue-700 hover:bg-blue-900 hover:border-blue-900" href="{{ route('welcome') }}">Back to Welcome</a>
        </form>
    </div>
</div>
@endsection