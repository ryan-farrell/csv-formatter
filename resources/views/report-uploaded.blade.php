@extends('layouts.main')

@section('content')
    <div class='p-5 flex flex-wrap items-center justify-center min-h-screen bg-gradient-to-br'>
        <div class='w-full min-w-xl px-10 py-8 mx-auto bg-white rounded-lg shadow-xl dark:bg-gray-800 dark:border-gray-700 '>
            <h1 class="text-center font-mono font-bold text-gray-900 dark:text-white text-lg leading-tight border-b pb-4">Homeowner Report</h1>
            <div class="mt-2 p-5" >
                <h5 class="mb-3 text-white font-mono">Check your Homeowner CSV upload below</h5>
                <div class="p-3 relative overflow-x-auto">
                    <table class="font-mono w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="px-6 py-3">
                                    {{$headers[0]}}
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    {{$headers[1]}}
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    {{$headers[2]}}
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    {{$headers[3]}}
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($homeowners as $person)
                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                    <td scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                        {{$person[0]}}
                                    </th>
                                    <td class="px-6 py-4">
                                        {{$person[1]}}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{$person[2]}}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{$person[3]}}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-5 space-y-3">
                <a href="{{ route('csv-upload') }}" class="font-mono text-center block w-full text-white font-normal py-2 px-4 rounded transition duration-300 ease-in-out focus:outline-none focus:shadow-outline bg-green-700 border border-green-700 hover:bg-green-900 hover:border-blue-900">Format Another CSV</a>
                <a href="{{ route('welcome') }}" class="font-mono text-center block w-full text-white font-normal py-2 px-4 rounded transition duration-300 ease-in-out focus:outline-none focus:shadow-outline bg-blue-700 border border-blue-700 hover:bg-blue-900 hover:border-blue-900">Back to Welcome</a>
                </div>
            </div>
        </div>
    </div>
@endsection
