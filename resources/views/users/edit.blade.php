<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Users / Edit
            </h2>
            <a class="bg-slate-700 text-sm rounded-md text-white px-3 py-2" href="{{route('users.list')}}">Back</a>
        </div>
    </x-slot>

    {{-- <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div> --}}

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form action="{{route('users.update', $user->id)}}" method="post">

                        @csrf
                        <div>
                            <label for="" class="text-lg font-medium">Name</label>
                            <div class="my-3">
                                <input value="{{old('name', $user->name)}}" type="text"
                                    class="border-gray-300 shadow-sm w-50% rounded-lg" placeholder="Enter name"
                                    name="name">
                                @error('name')
                                    <p class="text-red-400 font-medium">{{$message}} </p>
                                @enderror
                            </div>
                            <label for="" class="text-lg font-medium">Email</label>
                            <div class="my-3">
                                <input value="{{old('email', $user->email)}}" type="email"
                                    class="border-gray-300 shadow-sm w-50% rounded-lg" placeholder="Enter email"
                                    name="email">
                                @error('email')
                                    <p class="text-red-400 font-medium">{{$message}} </p>
                                @enderror
                            </div>

                            <div class="grid grid-cols-4 mb-3">
                                @if($roles->isNotEmpty())
                                    @foreach($roles as $role)
                                        <div class="mt-3">

                                            <input {{($hasRoles->contains($role->id)) ? 'checked' : ''}} type="checkbox"
                                                id="role-{{$role->id}}" class="rounded" name="role[]" value="{{$role->name}}">
                                            <label for="role-{{$role->id}}">{{$role->name}}</label>
                                        </div>
                                    @endforeach
                                @endif


                            </div>

                            <button
                                class="bg-green-700 text-sm rounded-md text-white px-5 py-3 hover:bg-green-500">Update</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>