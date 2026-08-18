@include('layouts.admin-header')
	
		<div class="page-wrapper">
				
		@include('layouts.admin-navbar')
			<!-- partial -->

			<div class="page-content container-xxl">

				@include('layouts.breadcrumb-card', [
					'title' => 'Testimonials List',
					'icon' => 'message-square-quote',
				])

				<div class="row">
					<div class="col-md-12 grid-margin stretch-card">
            <div class="card">
              <div class="card-body">
             
               
                <div class="table-responsive">
                 <table id="dataTableExample" class="table">
    <thead>
        <tr>
            <th>Image</th>
            <th>Name</th>
            <th>Designation</th>
            <th>Description</th>
            <th>Action</th>
        </tr>
    </thead>

    <tbody>
        @foreach($testimonials as $item)
        <tr>
            <td>
               
                <img src="{{$item->image}}"
                     style="width:60px; height:60px; border-radius:8px; object-fit:cover;">
            </td>

            <td style="white-space: normal;">{{ $item->name }}</td>
            <td style="white-space: normal;">{{ $item->designation }}</td>
            <td style="white-space: normal;">{{ $item->description }}</td>

            <td>
                <a href="{{ route('testimonial.edit', $item->id) }}" 
                   class="btn btn-sm btn-primary">Edit</a>

                <button type="button" class="btn btn-sm btn-danger btn-delete-testimonial"
                        data-testimonial-id="{{ $item->id }}" data-message="Are you sure you want to delete this testimonial?">Delete</button>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

                </div>
              </div>
            </div>
					</div>
				</div>

			</div>

			<!-- partial:../../partials/_footer.html -->
			<footer class="footer d-flex flex-row align-items-center justify-content-between px-4 py-3 border-top small">
				<p class="text-secondary mb-1 mb-md-0">Copyright © 2025 <a href="https://nobleui.com/" target="_blank">NobleUI</a>.</p>
				<p class="text-secondary">Handcrafted With <i class="mb-1 text-primary ms-1 icon-sm" data-lucide="heart"></i></p>
			</footer>
			<!-- partial -->
	
		</div>
	@include('layouts.delete-confirm-modal')

	<script>
	document.addEventListener('click', function(e) {
	    var btn = e.target.closest('.btn-delete-testimonial');
	    if (!btn) return;
	    e.preventDefault();

	    var id = btn.getAttribute('data-testimonial-id');
	    var confirmBtn = document.getElementById('deleteConfirmSubmit');
	    var originalHandler = confirmBtn.onclick;

	    confirmBtn.onclick = function() {
	        fetch(`/testimonial/delete/${id}`, {
	            method: "DELETE",
	            headers: {
	                "X-CSRF-TOKEN": "{{ csrf_token() }}"
	            }
	        })
	        .then(res => res.json())
	        .then(data => {
	            location.reload();
	        });
	    };

	    document.getElementById('deleteConfirmMessage').textContent = btn.getAttribute('data-message') || 'Are you sure you want to delete this testimonial?';
	    new bootstrap.Modal(document.getElementById('deleteConfirmModal')).show();
	});
	</script>

	@include('layouts.admin-footer')