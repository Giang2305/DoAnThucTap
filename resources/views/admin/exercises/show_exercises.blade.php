@extends('Admin.index')
@section('page-title', 'Exercises')
@section('content')
@if(session('message'))
    <div class="alert alert-success">
        {{ session('message') }}
    </div>
@endif
@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif
@if(session('info'))
    <div class="alert alert-info">
        {{ session('info') }}
    </div>
@endif
@if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card my-4">
                    <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                        <div class="d-flex bg-gradient-primary shadow-primary border-radius-lg pt-4 pb-3">
                            <h6 class="text-white text-capitalize ps-3">Exercises Table</h6>
                            <label for="text" class="label-search">
                                <i class="bi bi-search icon-search"></i>
                                <input type="search" id="text" class="input-search" placeholder="Search..." oninput="onSearch()">       
                            </label>
                        </div>       
                    </div>
                    <div class="card-body px-0 pb-2">
                        <div class="table-responsive p-0">
                            <table class="table align-items-center mb-0 table-hover">
                                <thead>
                                    <tr>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Exercise ID</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Lecture</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Title</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Description</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">File</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Due Date</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Action</th>
                                        <th>
                                            <div class="page-title-actions" style="display: inline-block; margin: -135px;">
                                                <a href="{{ route('create_exercise') }}" class="btn bg-gradient-info w-100 mb-0 toast-btn btn2-zoom">
                                                    <span class="btn-icon-wrapper pr-2 opacity-7">
                                                        <i class="fa fa-plus fa-w-20"></i>
                                                    </span>
                                                    Create
                                                </a>
                                            </div>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody id="exercise-list">
                                    @foreach($all_exercises as $exercise)
                                    <tr>
                                        <td class="align-middle text-center">
                                            <span class="text-secondary text-xs font-weight-bold">{{ $exercise->id }}</span>
                                        </td>
                                        <td class="align-middle text-center" style="max-width: 150px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                            <span class="text-secondary text-xs font-weight-bold">{{ $exercise->lecture->title ?? 'No Lecture' }}</span>
                                        </td>
                                        <td class="align-middle text-center" style="max-width: 120px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;"> 
                                            <span class="text-secondary text-xs font-weight-bold">{{ $exercise->title }}</span>
                                        </td>
                                        <td class="align-middle text-center" style="max-width: 150px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                            <span class="text-secondary text-xs font-weight-bold" style="max-width: 200px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">{{ $exercise->description }}</span>
                                        </td>
                                        <td class="align-middle text-center" style="max-width: 150px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                            @if($exercise->file_path)
                                                 <span class="text-secondary text-xs font-weight-bold">{{$exercise->file_path}}</span>
                                            @else
                                                <span class="text-secondary">No File</span>
                                            @endif
                                        </td>
                                        <td class="align-middle text-center">
                                            <span class="text-secondary text-xs font-weight-bold">{{ $exercise->due_date }}</span>
                                        </td>
                                        <td class="align-middle text-center" >
                                            <span class="badge badge-sm 
                                                {{$exercise->status == 'Active' ? 'bg-gradient-success' : 'bg-gradient-primary'}}">
                                               {{$exercise->status}}
                                            </span>
                                        </td>
                                        <td class="align-middle">
                                            <div class="col-lg-3 col-md-6 special-grid bulbs brand-id" style="padding-left: 50px;">
                                                <div class="products-single fix">
                                                    <div class="mt-2">
                                                        <!-- Edit button -->
                                                        <a style="width:90px" href="{{ route('edit_exercise', $exercise->id) }}" class="btn bg-gradient-warning btn-sm btn1-zoom">
                                                            <i class="fa fa-edit fa-w-20"></i> Edit
                                                        </a>
                                                        <a style="width:90px" href="{{route('exercise.submissions', $exercise->id)}}" class="btn bg-gradient-success btn-sm btn1-zoom">
                                                            <i class="fa fa-edit fa-w-20"></i>
                                                              Score
                                                        </a>
                                                        <!-- Delete button -->
                                                        <form action="{{ route('delete_exercise', $exercise->id) }}" method="POST" style="display:inline-block;">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button style="width:90px" type="submit" class="btn btn-danger btn-sm btn2-zoom" onclick="return confirm('Are you sure you want to delete this exercise?');">
                                                                <i class="fa fa-trash fa-w-20"></i> Delete
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach 
                                </tbody>
                            </table>
                            <script>
                                const onSearch = () => {
                                    const input = document.querySelector("#text");
                                    const filter = input.value.toUpperCase();
                                    const rows = document.querySelectorAll("#exercise-list tr");

                                    rows.forEach(row => {
                                        const cells = row.querySelectorAll("td");
                                        let match = false;
                                        cells.forEach(cell => {
                                            if (cell.textContent.toUpperCase().includes(filter)) {
                                                match = true;
                                            }
                                        });
                                        row.style.display = match ? "" : "none";
                                    });
                                }
                            </script>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
