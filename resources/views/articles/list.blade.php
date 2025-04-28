<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Articles') }}
            </h2>
            <a class="bg-green-700 text-sm rounded-md text-white px-3 py-2 hover:bg-green-500"
                href="{{route('articles.create')}}">Create</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(Session::has('success'))
                <p style="color: green">{{Session::get('success')}}</p>
            @endif

            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr class="border-b">
                        <th class="px-6 py-3 text-left" width='60'>Sn</th>
                        <th class="px-6 py-3 text-left">Name</th>
                        <th class="px-6 py-3 text-left">Author</th>
                        <th class="px-6 py-3 text-left" width='180'>Created</th>
                        <th class="px-6 py-3 text-center" width='180'>Action</th>
                    </tr>
                </thead>
                <?php $i = ($articles->currentpage() - 1) * $articles->perpage() + 1; ?>
                <tbody class="bg-white">
                    @if($articles->isNotEmpty())
                        @foreach ($articles as $article)
                            <tr class="border-b">
                                <td class="px-6 py-3 text-left">{{$i++}}</td>
                                <td class="px-6 py-3 text-left">{{$article->title}}</td>
                                <td class="px-6 py-3 text-left">{{$article->author}}</td>
                                <td class="px-6 py-3 text-left">
                                    {{\Carbon\Carbon::parse($article->created_at)->format('d M ,Y')}}
                                </td>
                                <td class="px-6 py-3 text-center">
                                    @can('Edit articles')
                                        <a class="bg-blue-700 text-sm rounded-md text-white px-3 py-2 hover:bg-blue-500"
                                            href="{{route('articles.edit', $article->id)}}">Edit
                                        </a><br>
                                    @endcan

                                    @can('Delete article')
                                        <form action="{{route('articles.destroy', $article->id)}}" method="post">
                                            @csrf @method('DELETE')
                                            <button class="bg-red-700 text-sm rounded-md text-white px-3 py-2 hover:bg-red-500"
                                                type="submit" name="btn" value="Delete"
                                                onclick=" return confirm('Are you sure you want to delete this item?');"
                                                title="Delete">Delete</button>
                                        </form>
                                    @endcan
                                </td>
                                {{-- <td>
                                    <form action="{{route('permissions.destroy',$permission->id)}}" method="post">
                                        @csrf @method('DELETE')
                                        <button class="bg-red-700 text-sm rounded-md text-white px-3 py-2 hover:bg-red-500"
                                            type="submit" name="btn" value="Delete"
                                            onclick=" return confirm('Are you sure you want to delete this item?');"
                                            title="Delete">Delete</button>
                                    </form>
                                </td> --}}
                            </tr>
                        @endforeach

                    @endif

                </tbody>

            </table>
            <div class="my-3">
                {{$articles->links()}}
            </div>


        </div>
    </div>
    {{-- <x-slot name="script">
        <script type="text/javascript">
            function deletePermission(id) {
                if (confirm("Are you sure want to delete?")) {
                    $.ajax({
                        url: "{{route('permissions.destroy')}}",
                        type: 'delete',
                        data: { id: id },
                        dataType: 'json',
                        headers: {
                            'x-csrf-token': '{{csrf_token()}}'
                        }
                        success: function (response) {
                            window.location.href = '{{route("permissions.list")}}';
                        }
                    })
                }
            }
        </script>
    </x-slot> --}}
</x-app-layout>