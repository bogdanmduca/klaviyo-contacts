@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="d-flex justify-content-end">
            <a href="{{route('contacts.create')}}" class="btn btn-primary"> Add new contact </a>
            <a href="{{route('uploaded-contacts.create')}}" class="btn btn-secondary mx-1"> Upload contacts </a>
            <form action="{{ route('tracker.store') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-dark">Track</button>
            </form>
        </div>

        <table class="table">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Remote Id</th>
                    <th scope="col">First Name</th>
                    <th scope="col">Email</th>
                    <th scope="col">Phone</th>
                </tr>
            </thead>
            <tbody>
                @foreach($contacts as $contact)
                <tr>
                    <th scope="row">{{ $loop->index + 1}} </th>
                    <td>{{ $contact->remote_id}}</td>
                    <td>{{ $contact->first_name}}</td>
                    <td>{{ $contact->email}}</td>
                    <td>{{ $contact->phone}}</td>
                    <td class="d-flex justify-content-end">
                        <a href="{{route('contacts.edit', $contact->id)}}" class="btn btn-info mx-1">Edit</a>
                        <form action="{{ route('contacts.destroy', $contact->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger mx-1">Remove</button>
                        </form>
                    </td>
                </tr>

                @endforeach
            </tbody>
        </table>
        <div class="d-flex justify-content-center mt-3">
            {{ $contacts->links() }}
        </div>


    </div>
</div>
@endsection
