<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Image Resizer</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
</head>


<body>
<div class="container mt-5">
    <h3>Image Resizer</h3>
    
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
    @error('custom_width')
        <div class="alert alert-danger" role="alert">Width Error: {{ $message }}</div>
    @enderror
    @error('custom_height')
        <div class="alert alert-danger" role="alert">Height Error: {{ $message }}</div>
    @enderror


    
    <form action="{{ route('image.temp.upload') }}" method="POST" enctype="multipart/form-data" class="mb-4">
        @csrf
        <div class="mb-3">
            <label for="image" class="form-label">Choose Image:</label>
            <input type="file" class="form-control" id="image" name="image" required>
        </div>
        
        <button type="submit" class="btn btn-primary">Upload</button>
    </form>

    <hr class="my-4">

    
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

                <p class="mt-3">Current path: <code>{{ $imagePath }}</code></p>
                
                
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
                
                
                <div class="mt-4 pt-3 border-top">
                    
                    <form action="{{ route('image.manipulate') }}" method="POST" class="d-inline me-2">
                        @csrf
                        <div class="row justify-content-center g-2 mb-3">
                            <label class="form-label mb-0">Apply Resize Changes:</label>
                            <div class="col-6">
                                <input type="number" name="custom_width" class="form-control" placeholder="Max Width" value="{{ old('custom_width', 600) }}" min="1">
                            </div>
                            <div class="col-6">
                                <input type="number" name="custom_height" class="form-control" placeholder="Max Height" value="{{ old('custom_height', 600) }}" min="1">
                            </div>
                        </div>
                        
                        <button type="submit" class="btn btn-success">Apply Changes</button>
                    </form>

                    
                    @if (session('temp_image_exif_data'))
                        <form action="{{ route('metadata.toggle') }}" method="POST" class="d-inline me-2">
                            @csrf
                            @if (session('show_image_metadata'))
                                <button type="submit" class="btn btn-warning">Hide Metadata</button>
                            @else
                                <button type="submit" class="btn btn-info">Show Metadata</button>
                            @endif
                        </form>
                    @endif
                    
                    
                    <form action="{{ route('image.finalize') }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-danger">Finalize & Save</button>
                    </form>
                    
                </div>
            </div>
        </div>
    @endif
    
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
</body>
</html>