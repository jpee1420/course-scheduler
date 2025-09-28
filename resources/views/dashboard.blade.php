<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<title>Smart Schedule - Dashboard</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
	<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <style>
    .sidebar{background:#343a40;min-height:100vh;color:#fff}
    .sidebar a{color:#adb5bd}
    .sidebar a.active,.sidebar a:hover{color:#fff}
    .sidebar-right{background:#f8f9fa;min-height:100vh;border-left:1px solid #dee2e6}
    .schedule-card{height:100%}
    .professor-image{width:56px;height:56px;border-radius:50%;object-fit:cover}
    .professor-image-small{width:36px;height:36px;border-radius:50%;object-fit:cover}
    .scroll-buttons{position:fixed;right:16px;bottom:16px;z-index:1030;display:flex;flex-direction:column;gap:8px}
    .scroll-btn{width:40px;height:40px;border-radius:50%;border:none;background:#0d6efd;color:#fff}
    .view-toggle{margin-left:12px;border:none;background:#f8f9fa;padding:6px 10px;border-radius:6px}
    .view-toggle.active{background:#e9ecef}
    .grid-item{border:1px solid #e9ecef;border-radius:8px;padding:12px;margin-bottom:12px}
    .filter-heading{font-weight:600;margin-bottom:6px}
    .filter-group{max-height:200px;overflow:auto;border:1px solid #eee;border-radius:6px;padding:8px}
    .status-present{background:#198754;color:#fff}
    .status-absent{background:#dc3545;color:#fff}
    .status-on-leave{background:#ffc107;color:#212529}
    .status-on-meeting{background:#0dcaf0;color:#212529}
    
    /* Individual option colors for dropdowns */
    .professor-status-select option[value="Present"]{background:#198754;color:#fff}
    .professor-status-select option[value="Absent"]{background:#dc3545;color:#fff}
    .professor-status-select option[value="On Leave"]{background:#ffc107;color:#212529}
    .professor-status-select option[value="On Meeting"]{background:#0dcaf0;color:#212529}
    </style>
</head>
<body>
<div class="container-fluid p-0">
	<div class="row g-0">
		<div class="col-md-2 sidebar p-0">
			<div class="p-3">
				<h4>Admin Panel</h4>
			</div>
			<nav class="nav flex-column px-3 pb-3">
				<a class="nav-link active" href="#schedules" data-bs-toggle="tab"><i class="fas fa-calendar-alt me-2"></i>Schedules</a>
				<a class="nav-link" href="#rooms" data-bs-toggle="tab"><i class="fas fa-door-open me-2"></i>Rooms</a>
				<a class="nav-link" href="#professors" data-bs-toggle="tab"><i class="fas fa-chalkboard-teacher me-2"></i>Professors</a>
				<a class="nav-link" href="#courses" data-bs-toggle="tab"><i class="fas fa-book me-2"></i>Courses</a>
			</nav>
		</div>
		<div class="col-md-8 p-4">
			<div class="tab-content">
				<div class="tab-pane fade show active" id="schedules">
					<div class="d-flex justify-content-between align-items-center mb-4">
						<h2>Class Schedules</h2>
						<div>
							<a href="{{ route('view-schedules') }}" class="btn btn-info me-2"><i class="fas fa-tv"></i> View Slideshow</a>
							<button class="btn btn-primary"><i class="fas fa-plus"></i> Add Schedule</button>
						</div>
					</div>
					<div class="mb-3">
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-search"></i></span>
                                <input type="text" id="scheduleSearch" class="form-control" placeholder="Search schedules by course, professor, or room...">
                            </div>
						<div class="mt-1 small text-muted">Type to search</div>
					</div>

					<div class="card">
						<div class="card-body">
							<div class="row">
								@foreach($schedules as $schedule)
								<div class="col-md-4 schedule-card-container mb-3">
                                        <div class="card schedule-card" data-course-code="{{ $schedule->course?->course_code }}" data-course-name="{{ $schedule->course?->course_name }}" data-professor="{{ $schedule->professor?->name }}" data-room="{{ $schedule->room?->name }}">
										<div class="card-body d-flex flex-column">
											<div class="d-flex align-items-center mb-3">
												<img src="{{ asset('uploads/' . ($schedule->professor?->profile_image && $schedule->professor?->profile_image !== 'placeholder.png' ? $schedule->professor?->profile_image : 'placeholder.png')) }}" class="professor-image me-3" alt="Professor">
												<div>
													<h5 class="card-title mb-0">{{ $schedule->course?->course_code }}</h5>
													<div class="card-subtitle mb-1">{{ $schedule->course?->course_name }}</div>
													<small class="text-muted">Prof. {{ $schedule->professor?->name }}</small>
												</div>
											</div>
											<p class="card-text"><i class="fas fa-clock me-2"></i>{{ \Carbon\Carbon::parse($schedule->start_time)->format('h:i A') }} - {{ \Carbon\Carbon::parse($schedule->end_time)->format('h:i A') }}</p>
											<p class="card-text"><i class="fas fa-calendar me-2"></i>{{ $schedule->day }}</p>
											<p class="card-text"><i class="fas fa-door-open me-2"></i>Room {{ $schedule->room?->name }}</p>
											<div class="d-flex justify-content-between align-items-center mt-auto">
                                                    <span class="badge schedule-status {{ $schedule->professor_status === 'Absent' ? 'status-absent' : ($schedule->professor_status === 'On Leave' ? 'status-on-leave' : ($schedule->professor_status === 'On Meeting' ? 'status-on-meeting' : 'status-present')) }}">{{ $schedule->professor_status }}</span>
												<div>
													<button class="btn btn-sm btn-primary"><i class="fas fa-edit"></i></button>
													<button class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
												</div>
											</div>
										</div>
									</div>
								</div>
								@endforeach
							</div>
						</div>
					</div>
				</div>

				<div class="tab-pane fade" id="rooms">
					<div class="d-flex justify-content-between align-items-center mb-4">
						<div class="d-flex align-items-center">
							<h2>Room Management</h2>
							<button class="view-toggle active" data-view="list" data-target="rooms"><i class="fas fa-list"></i> List</button>
							<button class="view-toggle" data-view="grid" data-target="rooms"><i class="fas fa-th"></i> Grid</button>
						</div>
						<button class="btn btn-primary"><i class="fas fa-plus"></i> Add Room</button>
					</div>
					<div class="card">
						<div class="card-body">
							<div class="mb-3">
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                                        <input type="text" id="roomSearch" class="form-control" placeholder="Search rooms by name...">
                                    </div>
								<div class="mt-1 small text-muted">Type to search</div>
							</div>
							<div class="table-responsive">
								<table class="table table-hover">
									<thead><tr><th>ID</th><th>Room Name</th><th>Actions</th></tr></thead>
									<tbody>
									@foreach($rooms as $room)
									<tr>
										<td>{{ $room->id }}</td>
										<td>{{ $room->name }}</td>
										<td>
											<button class="btn btn-sm btn-primary"><i class="fas fa-edit"></i></button>
											<button class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
										</td>
									</tr>
									@endforeach
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>

				<div class="tab-pane fade" id="professors">
					<div class="d-flex justify-content-between align-items-center mb-4">
						<div class="d-flex align-items-center">
							<h2>Professor Management</h2>
							<button class="view-toggle active" data-view="list" data-target="professors"><i class="fas fa-list"></i> List</button>
							<button class="view-toggle" data-view="grid" data-target="professors"><i class="fas fa-th"></i> Grid</button>
						</div>
						<button class="btn btn-primary"><i class="fas fa-plus"></i> Add Professor</button>
					</div>
					<div class="card">
						<div class="card-body">
							<div class="mb-3">
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                                        <input type="text" id="professorSearch" class="form-control" placeholder="Search professors by name...">
                                    </div>
								<div class="mt-1 small text-muted">Type to search</div>
							</div>
							<div class="table-responsive">
								<table class="table table-hover">
									<thead><tr><th>ID</th><th>Image</th><th>Name</th><th>Actions</th></tr></thead>
									<tbody>
									@foreach($professors as $professor)
									<tr>
										<td>{{ $professor->id }}</td>
										<td><img src="{{ asset('uploads/' . ($professor->profile_image && $professor->profile_image !== 'placeholder.png' ? $professor->profile_image : 'placeholder.png')) }}" class="professor-image-small" alt="{{ $professor->name }}"></td>
										<td>{{ $professor->name }}</td>
										<td>
											<button class="btn btn-sm btn-primary me-2"><i class="fas fa-edit"></i></button>
											<button class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
										</td>
									</tr>
									@endforeach
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>

				<div class="tab-pane fade" id="courses">
					<div class="d-flex justify-content-between align-items-center mb-4">
						<div class="d-flex align-items-center">
							<h2>Course Management</h2>
							<button class="view-toggle active" data-view="list" data-target="courses"><i class="fas fa-list"></i> List</button>
							<button class="view-toggle" data-view="grid" data-target="courses"><i class="fas fa-th"></i> Grid</button>
						</div>
						<button class="btn btn-primary"><i class="fas fa-plus"></i> Add Course</button>
					</div>
					<div class="card">
						<div class="card-body">
							<div class="mb-3">
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                                        <input type="text" id="courseSearch" class="form-control" placeholder="Search courses by code or name...">
                                    </div>
								<div class="mt-1 small text-muted">Type to search</div>
							</div>
							<div class="table-responsive">
								<table class="table table-hover">
									<thead><tr><th>ID</th><th>Course Code</th><th>Course Name</th><th>Type</th><th>Actions</th></tr></thead>
									<tbody>
									@foreach($courses as $course)
									<tr>
										<td>{{ $course->id }}</td>
										<td>{{ $course->course_code }}</td>
										<td>{{ $course->course_name }}</td>
										<td><span class="badge bg-{{ $course->lab ? 'info' : 'primary' }}">{{ $course->lab ? 'Laboratory' : 'Lecture' }}</span></td>
										<td>
											<button class="btn btn-sm btn-primary me-2"><i class="fas fa-edit"></i></button>
											<button class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
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
		<div class="col-md-2 sidebar-right p-0">
			<div class="p-3 text-white bg-dark">
				<h4>Professor Status</h4>
			</div>
			<div class="p-3">
				@foreach($professors as $professor)
				<div class="professor-status-card mb-3">
					<div class="d-flex align-items-center">
						<img src="{{ asset('uploads/' . ($professor->profile_image && $professor->profile_image !== 'placeholder.png' ? $professor->profile_image : 'placeholder.png')) }}" alt="{{ $professor->name }}" class="professor-image-small me-2">
						<div class="flex-grow-1">
							<h6 class="mb-1">{{ $professor->name }}</h6>
                                    <select class="form-select form-select-sm professor-status-select" data-professor-id="{{ $professor->id }}">
                                        <option value="Present" {{ ($professor->schedules->first()->professor_status ?? 'Present') == 'Present' ? 'selected':'' }}>Present</option>
                                        <option value="Absent" {{ ($professor->schedules->first()->professor_status ?? 'Present') == 'Absent' ? 'selected':'' }}>Absent</option>
                                        <option value="On Leave" {{ ($professor->schedules->first()->professor_status ?? 'Present') == 'On Leave' ? 'selected':'' }}>On Leave</option>
                                        <option value="On Meeting" {{ ($professor->schedules->first()->professor_status ?? 'Present') == 'On Meeting' ? 'selected':'' }}>On Meeting</option>
                                    </select>
						</div>
					</div>
				</div>
				@endforeach
			</div>
		</div>
	</div>
</div>

<!-- CRUD Modals -->
<div class="modal fade" id="entityModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="entityModalTitle">Create</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="entityForm">
                    <input type="hidden" id="entityType">
                    <input type="hidden" id="entityId">
                    <div id="formFields"></div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" id="saveEntityBtn" class="btn btn-primary">Save</button>
            </div>
        </div>
    </div>
    </div>

<div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this item?
                <input type="hidden" id="deleteEntityType">
                <input type="hidden" id="deleteEntityId">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" id="confirmDeleteBtn" class="btn btn-danger">Delete</button>
            </div>
        </div>
    </div>
    </div>

<div class="scroll-buttons">
	<button class="scroll-btn" id="scrollTopBtn" title="Scroll to Top"><i class="fas fa-arrow-up"></i></button>
	<button class="scroll-btn" id="scrollBottomBtn" title="Scroll to Bottom"><i class="fas fa-arrow-down"></i></button>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
const entityModal = new bootstrap.Modal(document.getElementById('entityModal'));
const deleteModal = new bootstrap.Modal(document.getElementById('confirmDeleteModal'));

document.getElementById('scrollTopBtn').addEventListener('click',()=>window.scrollTo({top:0,behavior:'smooth'}));
document.getElementById('scrollBottomBtn').addEventListener('click',()=>window.scrollTo({top:document.body.scrollHeight,behavior:'smooth'}));

function openCreateModal(type){
    document.getElementById('entityType').value = type;
    document.getElementById('entityId').value = '';
    document.getElementById('entityModalTitle').innerText = `Create ${capitalize(type)}`;
    renderFormFields(type, null);
    entityModal.show();
}

function openEditModal(type, id, data){
    document.getElementById('entityType').value = type;
    document.getElementById('entityId').value = id;
    document.getElementById('entityModalTitle').innerText = `Edit ${capitalize(type)}`;
    renderFormFields(type, data);
    entityModal.show();
}

function openDeleteModal(type, id){
    document.getElementById('deleteEntityType').value = type;
    document.getElementById('deleteEntityId').value = id;
    deleteModal.show();
}

function renderFormFields(type, data){
    const fields = document.getElementById('formFields');
    const value = (k, def='') => (data && data[k] !== undefined ? data[k] : def);
    if(type==='rooms'){
        fields.innerHTML = `<div class="mb-3"><label class="form-label">Room Name</label><input class="form-control" id="name" value="${escapeHtml(value('name'))}"></div>`;
    } else if(type==='professors'){
        const currentImage = value('profile_image') || 'placeholder.png';
        const hasCustomImage = currentImage && currentImage !== 'placeholder.png';
        
        fields.innerHTML = `<div class=\"mb-3\"><label class=\"form-label\">Name</label><input class=\"form-control\" id=\"name\" value=\"${escapeHtml(value('name'))}\"></div>`+
            `<div class=\"mb-3\">
                <label class=\"form-label\">Profile Image</label>
                <div class=\"d-flex align-items-start gap-3\">
                    <div class=\"position-relative\">
                        <img id=\"imagePreview\" src=\"/uploads/${currentImage}\" class=\"professor-image\" style=\"width: 80px; height: 80px; object-fit: cover; border-radius: 50%; border: 2px solid #dee2e6;\" alt=\"Preview\">
                        ${hasCustomImage ? `<button type=\"button\" id=\"removeImageBtn\" class=\"btn btn-sm btn-danger position-absolute\" style=\"top: -5px; right: -5px; width: 24px; height: 24px; border-radius: 50%; padding: 0; display: flex; align-items: center; justify-content: center;\" title=\"Remove image\">
                            <i class=\"fas fa-times\" style=\"font-size: 12px;\"></i>
                        </button>` : ''}
                    </div>
                    <div class=\"flex-grow-1\">
                        <input class=\"form-control\" id=\"profile_image\" type=\"file\" accept=\"image/*\">
                        <input type=\"hidden\" id=\"remove_image\" value=\"0\">
                        <small class=\"form-text text-muted\">Upload a new image or use the × button to remove current image</small>
                    </div>
                </div>
            </div>`;
        
        // Add event listeners after DOM is updated
        setTimeout(() => {
            const fileInput = document.getElementById('profile_image');
            const imagePreview = document.getElementById('imagePreview');
            const removeBtn = document.getElementById('removeImageBtn');
            const removeInput = document.getElementById('remove_image');
            
            // Handle file selection
            if (fileInput) {
                fileInput.addEventListener('change', function(e) {
                    if (e.target.files && e.target.files[0]) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            imagePreview.src = e.target.result;
                            removeInput.value = '0';
                            // Add remove button if it doesn't exist
                            if (!document.getElementById('removeImageBtn')) {
                                const removeButton = document.createElement('button');
                                removeButton.type = 'button';
                                removeButton.id = 'removeImageBtn';
                                removeButton.className = 'btn btn-sm btn-danger position-absolute';
                                removeButton.style.cssText = 'top: -5px; right: -5px; width: 24px; height: 24px; border-radius: 50%; padding: 0; display: flex; align-items: center; justify-content: center;';
                                removeButton.title = 'Remove image';
                                removeButton.innerHTML = '<i class="fas fa-times" style="font-size: 12px;"></i>';
                                imagePreview.parentElement.appendChild(removeButton);
                                
                                removeButton.addEventListener('click', function() {
                                    imagePreview.src = '/uploads/placeholder.png';
                                    fileInput.value = '';
                                    removeInput.value = '1';
                                    this.remove();
                                });
                            }
                        };
                        reader.readAsDataURL(e.target.files[0]);
                    }
                });
            }
            
            // Handle remove button
            if (removeBtn) {
                removeBtn.addEventListener('click', function() {
                    imagePreview.src = '/uploads/placeholder.png';
                    fileInput.value = '';
                    removeInput.value = '1';
                    this.remove();
                });
            }
        }, 100);
    } else if(type==='courses'){
        fields.innerHTML = `<div class=\"mb-3\"><label class=\"form-label\">Course Code</label><input class=\"form-control\" id=\"course_code\" value=\"${escapeHtml(value('course_code'))}\"></div>`+
            `<div class=\"mb-3\"><label class=\"form-label\">Course Name</label><input class=\"form-control\" id=\"course_name\" value=\"${escapeHtml(value('course_name'))}\"></div>`+
            `<div class=\"form-check\"><input class=\"form-check-input\" type=\"checkbox\" id=\"lab\" ${value('lab')? 'checked':''}><label class=\"form-check-label\" for=\"lab\">Laboratory</label></div>`;
    } else if(type==='schedules'){
        fields.innerHTML = `
        <div class=\"mb-3\">
            <label class=\"form-label\">Course</label>
            <div class=\"position-relative\">
                <input type=\"text\" class=\"form-control\" id=\"course_search\" placeholder=\"Search courses...\" autocomplete=\"off\">
                <select class=\"form-select d-none\" id=\"course_id\">${buildCourseOptions(@json($courses), value('course_id'))}</select>
                <div class=\"dropdown-menu w-100\" id=\"course_dropdown\" style=\"max-height: 200px; overflow-y: auto;\"></div>
            </div>
        </div>
        <div class=\"mb-3\"><label class=\"form-label\">Professor</label><select class=\"form-select\" id=\"professor_id\">${buildOptions(@json($professors),'id','name', value('professor_id'))}</select></div>
        <div class=\"mb-3\"><label class=\"form-label\">Room</label><select class=\"form-select\" id=\"room_id\">${buildOptions(@json($rooms),'id','name', value('room_id'))}</select></div>
        <div class=\"row\"><div class=\"col\"><label class=\"form-label\">Start</label><input class=\"form-control\" id=\"start_time\" type=\"time\" value=\"${escapeHtml(value('start_time_formatted') || value('start_time',''))}\"></div>
        <div class=\"col\"><label class=\"form-label\">End</label><input class=\"form-control\" id=\"end_time\" type=\"time\" value=\"${escapeHtml(value('end_time_formatted') || value('end_time',''))}\"></div></div>
        <div class=\"mb-3 mt-3\"><label class=\"form-label\">Day</label><select class=\"form-select\" id=\"day\">${['MWF','TTH','Sat'].map(d=>`<option ${value('day')===d?'selected':''} value=\"${d}\">${d}</option>`).join('')}</select></div>`;
        
        // Initialize course search functionality
        setTimeout(() => initializeCourseSearch(@json($courses), value('course_id')), 100);
    }
}

function buildOptions(list, valueKey, labelKey, selected){
    return list.map(item=>`<option value=\"${item[valueKey]}\" ${String(item[valueKey])===String(selected)?'selected':''}>${escapeHtml(item[labelKey])}</option>`).join('');
}

function buildCourseOptions(courses, selected){
    return courses.map(course=>`<option value=\"${course.id}\" ${String(course.id)===String(selected)?'selected':''}>${escapeHtml(course.course_code)} - ${escapeHtml(course.course_name)}</option>`).join('');
}

function escapeHtml(str){
    if(str===undefined||str===null) return '';
    return String(str).replace(/[&<>"']/g, s=>({"&":"&amp;","<":"&lt;",">":"&gt;","\"":"&quot;","'":"&#39;"}[s]));
}

function capitalize(s){return s.charAt(0).toUpperCase()+s.slice(1)}

function initializeCourseSearch(courses, selectedId){
    const searchInput = document.getElementById('course_search');
    const hiddenSelect = document.getElementById('course_id');
    const dropdown = document.getElementById('course_dropdown');
    
    if(!searchInput || !hiddenSelect || !dropdown) return;
    
    let filteredCourses = courses;
    
    // Set initial value if editing
    if(selectedId){
        const selectedCourse = courses.find(c => String(c.id) === String(selectedId));
        if(selectedCourse){
            searchInput.value = `${selectedCourse.course_code} - ${selectedCourse.course_name}`;
            hiddenSelect.value = selectedId;
        }
    }
    
    function renderDropdown(coursesToShow){
        dropdown.innerHTML = '';
        if(coursesToShow.length === 0){
            dropdown.classList.remove('show');
            return;
        }
        
        coursesToShow.forEach(course => {
            const item = document.createElement('div');
            item.className = 'dropdown-item';
            item.style.cursor = 'pointer';
            item.innerHTML = `<strong>${escapeHtml(course.course_code)}</strong> - ${escapeHtml(course.course_name)}`;
            item.addEventListener('click', () => {
                searchInput.value = `${course.course_code} - ${course.course_name}`;
                hiddenSelect.value = course.id;
                dropdown.classList.remove('show');
            });
            dropdown.appendChild(item);
        });
        dropdown.classList.add('show');
    }
    
    searchInput.addEventListener('input', (e) => {
        const term = e.target.value.toLowerCase();
        filteredCourses = courses.filter(course => 
            course.course_code.toLowerCase().includes(term) || 
            course.course_name.toLowerCase().includes(term)
        );
        renderDropdown(filteredCourses);
    });
    
    searchInput.addEventListener('focus', () => {
        renderDropdown(filteredCourses);
    });
    
    // Hide dropdown when clicking outside
    document.addEventListener('click', (e) => {
        if(!searchInput.contains(e.target) && !dropdown.contains(e.target)){
            dropdown.classList.remove('show');
        }
    });
}

document.getElementById('saveEntityBtn').addEventListener('click', async ()=>{
    const type = document.getElementById('entityType').value;
    const id = document.getElementById('entityId').value;
    const method = id? 'PUT':'POST';
    const url = id? `/${type}/${id}`:`/${type}`;
    let res;
    if(type==='professors'){
        const formData = new FormData();
        const nameInput = document.getElementById('name');
        if(nameInput && nameInput.value.trim()){
            formData.append('name', nameInput.value.trim());
        }
        const fileInput = document.getElementById('profile_image');
        const removeImageInput = document.getElementById('remove_image');
        
        if(removeImageInput && removeImageInput.value === '1'){
            formData.append('remove_image', '1');
        } else if(fileInput && fileInput.files && fileInput.files[0]){
            formData.append('profile_image', fileInput.files[0]);
        }
        // Add method spoofing for PUT requests
        if(method === 'PUT'){
            formData.append('_method', 'PUT');
        }
        res = await fetch(url, {method: method === 'PUT' ? 'POST' : method, headers:{'X-CSRF-TOKEN':csrfToken,'Accept':'application/json'}, body: formData});
    } else {
        const body = collectForm(type);
        res = await fetch(url, {method, headers:{'Content-Type':'application/json','X-CSRF-TOKEN':csrfToken,'Accept':'application/json'}, body: JSON.stringify(body)});
    }
    if(res.ok){ location.reload(); } else { await showModalError(res); }
});

document.getElementById('confirmDeleteBtn').addEventListener('click', async ()=>{
    const type = document.getElementById('deleteEntityType').value;
    const id = document.getElementById('deleteEntityId').value;
    const res = await fetch(`/${type}/${id}`, {method:'DELETE', headers:{'X-CSRF-TOKEN':csrfToken, 'Accept':'application/json'}});
    if(res.ok){ location.reload(); } else { await showModalError(res); }
});

function collectForm(type){
    if(type==='rooms') return {name: document.getElementById('name').value};
    if(type==='professors') return {name: document.getElementById('name').value, profile_image: document.getElementById('profile_image').value || null};
    if(type==='courses') return {course_code: document.getElementById('course_code').value, course_name: document.getElementById('course_name').value, lab: document.getElementById('lab').checked ? 1:0};
    if(type==='schedules') return {course_id: +document.getElementById('course_id').value, professor_id: document.getElementById('professor_id').value? +document.getElementById('professor_id').value : null, room_id: document.getElementById('room_id').value? +document.getElementById('room_id').value : null, start_time: document.getElementById('start_time').value, end_time: document.getElementById('end_time').value, day: document.getElementById('day').value};
}

async function showModalError(res){
    let text = await res.text();
    try { const json = JSON.parse(text); if(json.message){ text = json.message; } } catch(e){}
    const form = document.getElementById('entityForm');
    let alert = form.querySelector('.alert');
    if(!alert){
        alert = document.createElement('div');
        alert.className = 'alert alert-danger alert-dismissible';
        alert.innerHTML = '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';
        form.prepend(alert);
    }
    alert.innerHTML = '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>' + text;
    
    // Auto-hide after 5 seconds
    setTimeout(() => {
        if(alert && alert.parentNode){
            alert.style.transition = 'opacity 0.5s ease-out';
            alert.style.opacity = '0';
            setTimeout(() => {
                if(alert && alert.parentNode){
                    alert.remove();
                }
            }, 500);
        }
    }, 5000);
}

// Wire existing buttons
document.querySelectorAll('#rooms .btn-primary').forEach(btn=>{
    if(btn.innerText.includes('Add')) btn.addEventListener('click', ()=>openCreateModal('rooms'));
});
document.querySelectorAll('#rooms .table .btn-primary').forEach(btn=>{
    btn.addEventListener('click', (e)=>{
        const tr = e.target.closest('tr');
        const id = tr.children[0].innerText.trim();
        const name = tr.children[1].innerText.trim();
        openEditModal('rooms', id, {name});
    });
});
document.querySelectorAll('#rooms .table .btn-danger').forEach(btn=>{
    btn.addEventListener('click', (e)=>{
        const tr = e.target.closest('tr');
        openDeleteModal('rooms', tr.children[0].innerText.trim());
    });
});

document.querySelectorAll('#professors .btn-primary').forEach(btn=>{
    if(btn.innerText.includes('Add')) btn.addEventListener('click', ()=>openCreateModal('professors'));
});
document.querySelectorAll('#professors .table .btn-primary').forEach(btn=>{
    btn.addEventListener('click', async (e)=>{
        const tr = e.target.closest('tr');
        const id = tr.children[0].innerText.trim();
        
        // Fetch the professor data to get the current profile_image
        try {
            const response = await fetch(`/professors/${id}`, {
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                }
            });
            
            if (response.ok) {
                const professorData = await response.json();
                openEditModal('professors', id, professorData);
            } else {
                // Fallback to basic data if fetch fails
                const name = tr.children[2].innerText.trim();
                openEditModal('professors', id, {name, profile_image: 'placeholder.png'});
            }
        } catch (error) {
            console.error('Error fetching professor data:', error);
            // Fallback to basic data
            const name = tr.children[2].innerText.trim();
            openEditModal('professors', id, {name, profile_image: 'placeholder.png'});
        }
    });
});
document.querySelectorAll('#professors .table .btn-danger').forEach(btn=>{
    btn.addEventListener('click', (e)=>{
        const tr = e.target.closest('tr');
        openDeleteModal('professors', tr.children[0].innerText.trim());
    });
});

document.querySelectorAll('#courses .btn-primary').forEach(btn=>{
    if(btn.innerText.includes('Add')) btn.addEventListener('click', ()=>openCreateModal('courses'));
});
document.querySelectorAll('#courses .table .btn-primary').forEach(btn=>{
    btn.addEventListener('click', (e)=>{
        const tr = e.target.closest('tr');
        const id = tr.children[0].innerText.trim();
        const course_code = tr.children[1].innerText.trim();
        const course_name = tr.children[2].innerText.trim();
        const lab = tr.children[3].innerText.includes('Laboratory');
        openEditModal('courses', id, {course_code, course_name, lab});
    });
});
document.querySelectorAll('#courses .table .btn-danger').forEach(btn=>{
    btn.addEventListener('click', (e)=>{
        const tr = e.target.closest('tr');
        openDeleteModal('courses', tr.children[0].innerText.trim());
    });
});

document.querySelectorAll('#schedules .btn-primary').forEach(btn=>{
    if(btn.innerText.includes('Add')) btn.addEventListener('click', ()=>openCreateModal('schedules'));
});
document.querySelectorAll('#schedules .schedule-card .btn-primary').forEach(btn=>{
    btn.addEventListener('click', (e)=>{
        const card = e.target.closest('.schedule-card');
        const container = e.target.closest('.schedule-card-container');
        const idx = Array.from(container.parentElement.children).indexOf(container);
        const s = @json($schedules);
        const data = s[idx];
        openEditModal('schedules', data.id, data);
    });
});
document.querySelectorAll('#schedules .schedule-card .btn-danger').forEach(btn=>{
    btn.addEventListener('click', (e)=>{
        const container = e.target.closest('.schedule-card-container');
        const idx = Array.from(container.parentElement.children).indexOf(container);
        const s = @json($schedules);
        openDeleteModal('schedules', s[idx].id);
    });
});

// Professor status change
document.querySelectorAll('.professor-status-select').forEach(select=>{
    styleStatusSelect(select);
    select.addEventListener('change', async (e)=>{
        styleStatusSelect(select);
        const professorId = select.getAttribute('data-professor-id');
        const status = select.value;
        const res = await fetch(`/professors/${professorId}/status`, {method:'POST', headers:{'X-CSRF-TOKEN':csrfToken,'Accept':'application/json','Content-Type':'application/json'}, body: JSON.stringify({status})});
        if(res.ok){
            // Update badges for cards of this professor
            document.querySelectorAll('#schedules .schedule-card').forEach(card=>{
                if((card.getAttribute('data-professor')||'') === select.closest('.professor-status-card').querySelector('h6').innerText){
                    const badge = card.querySelector('.schedule-status');
                    badge.innerText = status;
                    badge.classList.remove('status-present','status-absent','status-on-leave','status-on-meeting');
                    if(status==='Present') badge.classList.add('status-present');
                    else if(status==='Absent') badge.classList.add('status-absent');
                    else if(status==='On Leave') badge.classList.add('status-on-leave');
                    else if(status==='On Meeting') badge.classList.add('status-on-meeting');
                }
            });
        }
    });
});

function styleStatusSelect(select){
    select.classList.remove('status-present','status-absent','status-on-leave','status-on-meeting');
    const v = select.value;
    if(v==='Present') select.classList.add('status-present');
    else if(v==='Absent') select.classList.add('status-absent');
    else if(v==='On Leave') select.classList.add('status-on-leave');
    else if(v==='On Meeting') select.classList.add('status-on-meeting');
}

// Live search filters
document.getElementById('scheduleSearch').addEventListener('input', (e)=>{
    const term = e.target.value.toLowerCase();
    document.querySelectorAll('#schedules .schedule-card').forEach(card=>{
        const cc = (card.getAttribute('data-course-code')||'').toLowerCase();
        const cn = (card.getAttribute('data-course-name')||'').toLowerCase();
        const p = (card.getAttribute('data-professor')||'').toLowerCase();
        const r = (card.getAttribute('data-room')||'').toLowerCase();
        card.parentElement.style.display = (cc.includes(term)||cn.includes(term)||p.includes(term)||r.includes(term))? '' : 'none';
    });
});
document.getElementById('roomSearch').addEventListener('input', (e)=>{
    const term = e.target.value.toLowerCase();
    document.querySelectorAll('#rooms tbody tr').forEach(tr=>{
        const name = tr.children[1].innerText.toLowerCase();
        tr.style.display = name.includes(term)? '' : 'none';
    });
});
document.getElementById('professorSearch').addEventListener('input', (e)=>{
    const term = e.target.value.toLowerCase();
    document.querySelectorAll('#professors tbody tr').forEach(tr=>{
        const name = tr.children[2].innerText.toLowerCase();
        tr.style.display = name.includes(term)? '' : 'none';
    });
});
document.getElementById('courseSearch').addEventListener('input', (e)=>{
    const term = e.target.value.toLowerCase();
    document.querySelectorAll('#courses tbody tr').forEach(tr=>{
        const code = tr.children[1].innerText.toLowerCase();
        const name = tr.children[2].innerText.toLowerCase();
        tr.style.display = (code.includes(term)||name.includes(term))? '' : 'none';
    });
});
</script>
</body>
</html>


