<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bootstrap demo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
  </head>
  <body>

    <div class="container">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between">

                    <h3>Blogs List</h3>
                    <a href="{{url('blogs/create')}}" class="btn btn-primary">Create Blog</a>
                </div>
            </div>
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Date</th>
                            <th>Author</th>
                            <th>Category</th>
                            <th>Title</th>
                            <th>Images</th>
                            <th>Content</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($blogs as $i=>$blog)
                        <tr>
                            <td>{{++$i}}</td>
                            <td>{{date('d/m/Y',strtotime($blog->blog_date))}}</td>
                            <td>{{$blog->author}}</td>
                            <td>{{$blog->category}}</td>
                            <td>{{$blog->title}}</td>
                            <td>
                                @if(count($blog->images))
                                <div class="d-flex ">

                                    @foreach ($blog->images as $image)
                                        <img src="{{$image->url}}" class="pe-3" height="100px" alt="">
                                    @endforeach
                                </div>
                                @endif
                            </td>
                            <td>
                                @if (strlen(strip_tags($blog->content))>150)
                                {!!substr(strip_tags($blog->content), 0, 150)."..."!!}
                                @else
                                {!!strip_tags($blog->content)!!}</td>
                                @endif
                            <td>
                                <form action="{{route('delete_blog',$blog->id)}}" method="POST">
                                    @method("DELETE")
                                    @csrf
                                    <input type="submit" class="btn btn-danger show_confirm" value="Delete">
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

    <script>
        $('.show_confirm').click(function(event) {
          var form =  $(this).closest("form");
          var name = $(this).data("name");
          event.preventDefault();
          let res = confirm('are you sure want to delete?')
          if (res) {
            form.submit();
          }

      });
    </script>
  </body>
</html>
