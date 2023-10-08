<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Summernote</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>
</head>

<body>
    <div class="container">

        <div class="card">
            <div class="card-header">
                <h3>Create Blog</h3>
            </div>
            <div class="card-body">
                <form enctype="multipart/form-data" method="POST" action="{{ route('store') }}">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <label for="category">Category</label>

                            <select class="form-control" name="category">
                                <option>Select Category</option>
                                <option value="sport">sport</option>
                                <option value="music">music</option>
                            </select>
                        </div>
                        <div class="col-md-6">

                            <div class="form-group">
                                <label for="date">Date</label>
                                <input type="date" name="date" class="form-control" id="date">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="title">Title</label>
                        <input type="text" required name="title" class="form-control" id="title">
                    </div>

                    <div class="form-group">
                        <label for="author">Author</label>
                        <input type="text" required name="author" class="form-control" id="author">
                    </div>
                    <div class="form-group">
                        <label for="images">Choose images</label>
                        <input type="file" name="images[]" multiple class="form-control-file" id="images">
                    </div>
                    <div class="form-group">
                        <label for="content">Content</label>
                        <textarea id="summernote" name='content'></textarea>
                    </div>
                    <div class="">
                        <a class="btn btn-warning" href="{{ url('/') }}">Back</a>
                        <input class="btn btn-primary" value="Save Blog" type="submit" />
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            $('#summernote').summernote({
                height: 300
            });
        });
    </script>
</body>

</html>
