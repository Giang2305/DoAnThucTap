@extends('Admin.index')
@section('page-title', 'Lectures')
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
                <h6 class="text-white text-capitalize ps-3">Lectures table</h6>
                <label for="text" class="label-search">
                  <!--<img src="{{asset('public/images/search-vector.jpg')}}" class="img-search">-->
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
                      <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ">Lecture Number</th>
                      <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Title</th>
                      <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Chapter</th>
                      <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Course</th>
                      <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Type</th>
                      <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Content Url</th>
                      <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Text content</th>
                      <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Video Url</th>
                      <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity">Action</th>
                      <th>
                        <div class="page-title-actions" style="display: inline-block; margin: -135px;">
                        <a href="{{route('create_lecture') }}" class="btn bg-gradient-info w-100 mb-0 toast-btn btn2-zoom">
                            <span class="btn-icon-wrapper pr-2 opacity-7">
                                <i class="fa fa-plus fa-w-20"></i>
                            </span>
                            Create
                          </a>
                        </div>
                      </th>
                    </tr>
                  </thead>
                  <tbody id="course-list">
                    @foreach($all_lectures as $lectures)
                    <tr>
                      <td class="align-middle text-center">
                        <span class="text-secondary text-xs font-weight-bold">Bài {{$lectures->lecture_number}}</span>
                      </td>
                      <td class="align-middle text-center" style="max-width: 150px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                        <span class="text-secondary text-xs font-weight-bold">{{$lectures->title}}</span>
                      </td>
                      <td class="align-middle text-center">
                        <span class="text-secondary text-xs font-weight-bold">{{$lectures->chapter->title}}</span>
                      </td>
                      <td class="align-middle text-center">
                        <span class="text-secondary text-xs font-weight-bold">Khoá học {{$lectures->chapter->course->Name}}</span>
                      </td>
                      <td class="align-middle text-center text-sm">
                        <span class="badge badge-sm 
                            {{$lectures->type == 'Video' ? 'bg-gradient-success' : ($lectures->type == 'Text' ? 'bg-gradient-warning' : 'bg-gradient-info')}}">
                            {{ $lectures->type }}
                        </span>
                      </td>
                      <td class="align-middle text-center" style="max-width: 120px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                        <span class="text-secondary text-xs font-weight-bold">{{$lectures->content_url}}</span>
                      </td>
                      <td class="align-middle text-center" style="max-width: 150px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                        <span class="text-secondary text-xs font-weight-bold" title="{{$lectures->text_content}}">
                            {{$lectures->text_content}}
                        </span>
                      </td>


                      <td class="align-middle text-center text-sm" style="max-width: 120px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                        <span class="text-secondary text-xs font-weight-bold">{{$lectures->video_url }}</span>
                      </td>
                      <td class="align-middle">
                        <div class="col-lg-3 col-md-6 special-grid bulbs brand-id" style="padding-left: 50px;">
                          <div class="products-single fix">
                              <div class="mt-2">
                                  <!-- Edit button -->
                                  <a style="width:110px" href="{{route('edit_lecture', $lectures->id)}}" class="btn bg-gradient-warning btn-sm btn1-zoom">
                                    <i class="fa fa-edit fa-w-20"></i>
                                      Edit
                                  </a>
                                  <!-- Delete button -->
                                  <form action="{{route('delete_lecture', $lectures->id)}}" method="POST" style="display:inline-block;">
                                      @csrf
                                      @method('DELETE')
                                      <button style="width:110px" type="submit" class="btn btn-danger btn-sm btn2-zoom" onclick="return confirm('Are you sure you want to delete this leture?');">
                                        <i class="fa fa-trash fa-w-20"></i>
                                          Delete
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
                    const rows = document.querySelectorAll("#course-list tr");

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
                <script>
                  document.querySelectorAll('.zoom').forEach(row => {
                    row.addEventListener('mouseenter', () => {
                        document.querySelectorAll('thead th:not(:last-child)').forEach(th => {
                            th.classList.add('hovered');
                        });
                    });

                    row.addEventListener('mouseleave', () => {
                        document.querySelectorAll('thead th:not(:last-child)').forEach(th => {
                            th.classList.remove('hovered');
                        });
                    });
                  });
                </script> 
              </div>
            </div>
          </div>
        </div>
      </div>
      <footer class="footer py-4  ">
        <div class="container-fluid">
          <div class="row align-items-center justify-content-lg-between">
            <div class="col-lg-6 mb-lg-0 mb-4">
              <div class="copyright text-center text-sm text-muted text-lg-start">
                © <script>
                  document.write(new Date().getFullYear())
                </script>,
                made with <i class="fa fa-heart"></i> by
                <a href="https://www.creative-tim.com" class="font-weight-bold" target="_blank">Creative Tim</a>
                for a better web.
              </div>
            </div>
            <div class="col-lg-6">
              <ul class="nav nav-footer justify-content-center justify-content-lg-end">
                <li class="nav-item">
                  <a href="https://www.creative-tim.com" class="nav-link text-muted" target="_blank">Creative Tim</a>
                </li>
                <li class="nav-item">
                  <a href="https://www.creative-tim.com/presentation" class="nav-link text-muted" target="_blank">About Us</a>
                </li>
                <li class="nav-item">
                  <a href="https://www.creative-tim.com/blog" class="nav-link text-muted" target="_blank">Blog</a>
                </li>
                <li class="nav-item">
                  <a href="https://www.creative-tim.com/license" class="nav-link pe-0 text-muted" target="_blank">License</a>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </footer>
    </div>
 
@endsection