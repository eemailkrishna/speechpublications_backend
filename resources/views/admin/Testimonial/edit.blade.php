@include('layouts.admin-header')

<div class="page-wrapper">

    @include('layouts.admin-navbar')

    <div class="page-content container-xxl">

        <h4>Edit Testimonial</h4>
        <div class="card">

<div
 class="card-body">
      <form id="updateForm" action="{{ route('testimonial.update', $testimonial->id) }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="mb-3">
                <label class="form-label">Name</label>
                <input type="text" name="name" class="form-control" value="{{ $testimonial->name }}">
            </div>

            <div class="mb-3">
                <label class="form-label">Designation</label>
                <input type="text" name="designation" class="form-control" value="{{ $testimonial->designation }}">
            </div>

           <div class="mb-3">
    <label class="form-label">Description</label>
    <textarea name="description" class="form-control" rows="4">{{ $testimonial->description }}</textarea>
</div>


            <div class="mb-3">
                <label class="form-label">Old Image</label><br>
                <img src="{{ asset('public/uploads/testimonials/' . $testimonial->image) }}" 
                     style="width:120px; border-radius:8px;">
            </div>

            <div class="mb-3">
                <label class="form-label">New Image (optional)</label>
                <input type="file" id="imageInput" name="image" class="form-control">
            </div>

            <div class="mb-3">
                <img id="previewImage" style="width:120px; display:none; border-radius:8px;">
            </div>

            <button class="btn btn-primary">Update</button>
            <a href="{{ route('testimonial.list') }}" class="btn btn-secondary">Back</a>

        </form>
</div>            
        </div>

      

    </div>
</div>

@include('layouts.admin-footer')

<script>
document.getElementById("imageInput").addEventListener("change", function(event) {
    const file = event.target.files[0];
    if (file) {
        let preview = document.getElementById("previewImage");
        preview.src = URL.createObjectURL(file);
        preview.style.display = "block";
    }
});
</script>
