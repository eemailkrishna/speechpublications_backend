	@include('layouts.admin-header')
	<div class="page-wrapper">
	    	@include('layouts.admin-navbar')
    <div class="page-content container-xxl">
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
        
          <li class="breadcrumb-item active" aria-current="page">Add Testimonial</li>
        </ol>
      </nav>
      <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
          <div class="card">
            <div class="card-body">
            
              <form id="testimonialForm" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                  <label class="form-label">Name</label>
                  <input type="text" name="name" class="form-control" placeholder="Username">
                </div>
                <div class="mb-3">
                  <label class="form-label">Designation</label>
                  <input type="text" name="designation" class="form-control" placeholder="Designation">
                </div>
<div class="mb-3">
    <label class="form-label">Description</label>
    <textarea name="description" class="form-control" rows="4" placeholder="Description"></textarea>
</div>

                <div class="mb-3">
                  <label class="form-label">Image</label>
                  <input type="file" name="image" id="imageInput" class="form-control">
                </div>
                <!-- Preview -->
                <div class="mb-3">
                  <img id="previewImage" src="" style="max-width:180px; display:none; border-radius:10px;" />
                </div>
                <button type="submit" class="btn btn-primary me-2">Submit</button>
                <button class="btn btn-secondary" type="button" onclick="resetForm()">Cancel</button>
              </form>
              <div id="loader" style="display:none; text-align:center; margin-top:15px;">
    <div class="spinner-border text-primary" role="status" style="width:3rem; height:3rem;"></div>
    <p class="mt-2">Submitting...</p>
</div>

            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="page-wrapper">
      @include('layouts.admin-footer')
      <script>
        document.getElementById("imageInput").addEventListener("change", function(e) {
          const file = e.target.files[0];
          if (file) {
            const preview = document.getElementById("previewImage");
            preview.src = URL.createObjectURL(file);
            preview.style.display = "block";
          }
        });
      </script>
      <script>
document.getElementById("testimonialForm").addEventListener("submit", async function(e) {
    e.preventDefault();

    // SHOW LOADER
    document.getElementById("loader").style.display = "block";

    // DISABLE SUBMIT BUTTON
    const submitBtn = document.querySelector("button[type='submit']");
    submitBtn.disabled = true;
    submitBtn.innerText = "Submitting...";

    let formData = new FormData(this);

    try {
        const response = await fetch("{{ route('testimonial.store') }}", {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": document.querySelector('input[name="_token"]').value
            },
            body: formData
        });

        const result = await response.json();

        // HIDE LOADER
        document.getElementById("loader").style.display = "none";

        if (result.status) {
            alert("🎉 Testimonial Added Successfully!");

            // Reset form
            document.getElementById("testimonialForm").reset();

            // Remove preview
            const preview = document.getElementById("previewImage");
            preview.src = "";
            preview.style.display = "none";

            // RESET BUTTON
            submitBtn.disabled = false;
            submitBtn.innerText = "Submit";
        }

    } catch (error) {
        console.error("Error:", error);
        alert("Something went wrong!");

        // HIDE LOADER EVEN ON ERROR
        document.getElementById("loader").style.display = "none";

        submitBtn.disabled = false;
        submitBtn.innerText = "Submit";
    }
});
</script>

      