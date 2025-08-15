@extends('admin.layout.app')
@section('admin_content')


<div>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Img</th>
                <th scope="col">Title</th>
                <th scope="col">Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse($blogs as $index => $blog)
                <tr>
                    <th scope="row">{{ $index + 1 }}</th>
                    <td>
                        @if($blog->featured_image)
                        <img src="{{ asset('storage/app/public/' . $blog->featured_image) }}" alt="img" width="60">
                        @else
                            <span>No Image</span>
                        @endif
                    </td>
                    <td>{{ $blog->title }}</td>
                    <td>
                        <a href="{{ route('blog.details', $blog->slug) }}" class="btn-theme">View</a>
                        <a href="{{ route('admin.blog.edit', $blog->id) }}" class="btn-theme">Edit</a>
                        <form action="{{ route('admin.blog.destroy', $blog->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button class="btn-theme" onclick="return confirm('Delete this blog?')">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center">No blogs found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection