<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Image Metadata</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
</head>


<body>
<div class="container mt-5">
    <h3>Image Metadata</h3>
    
    {{-- Messages --}}
    @if (session('success'))
        <div class="alert alert-success" role="alert">
            {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger" role="alert">
            {{ session('error') }}
        </div>
    @endif
    @error('image')
        <div class="alert alert-danger" role="alert">{{ $message }}</div>
    @enderror

    {{-- Upload Form --}}
    <form action="{{ route('image.temp.upload') }}" method="POST" enctype="multipart/form-data" class="mb-4">
        @csrf
        
        <div class="mb-3">
            <label for="image" class="form-label">Choose Image:</label>
            <input type="file" class="form-control" id="image" name="image" required>
        </div>
        
        <button type="submit" class="btn btn-primary">Upload</button>
    </form>
    <form action="{{ route('image.reset') }}" method="POST" class="d-inline">
    @csrf
    <button type="submit" class="btn btn-danger">Reset</button>
</form>

    <hr class="my-4">

    {{-- Image Preview Card --}}
    @if (session('temp_image_path'))
        @php
            $imagePath = session('temp_image_path');
        @endphp

        <div class="card shadow-sm">
            <div class="card-header bg-secondary text-white">
                Temporary Image Preview
            </div>
            <div class="card-body text-center">
                <img src="{{ route('image.temp.view', ['path' => $imagePath]) }}" 
                     class="card-img-top img-fluid" 
                     alt="Temporary Upload" 
                     style="max-height: 300px; object-fit: contain;">

                <p class="mt-3">The file path: <code>{{ $imagePath }}</code></p>
                
                {{-- EXIF Toggle Button --}}
                @if (session('temp_image_exif_data'))
                    <form action="{{ route('metadata.toggle') }}" method="POST" class="mt-3">
                        @csrf
                        @if (session('show_image_metadata'))
                            <button type="submit" class="btn btn-warning btn-sm">Hide Metadata</button>
                        @else
                            <button type="submit" class="btn btn-info btn-sm">Show Metadata</button>
                        @endif
                    </form>
                @endif
                
                {{-- EXIF Data Table --}}
                @if (session('show_image_metadata'))
                    <div class="metadata-output text-start mt-4 p-3 bg-light rounded">
                        <h5>EXIF Data:</h5>
                        <table class="table table-sm table-striped">
                            <thead>
                                <tr>
                                    <th>Key</th>
                                    <th>Value</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach (session('temp_image_exif_data') as $key => $value)
                                    <tr>
                                        <td>{{ $key }}</td>
                                        <td>{{ is_array($value) ? implode(', ', $value) : $value }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    @endif

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
</body>
</html>