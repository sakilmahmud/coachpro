@extends('layout.student-layout')

@section('space-work')
    <div class="container study-materials-container">
        <h2 class="section-title text-center mb-4">Study Materials: PDFs</h2>
        <div class="row">
            @forelse($pdfs as $pdf)
                <div class="col-md-4 mb-4">
                    <div class="card study-material-card h-100 shadow-sm">
                        <div class="card-body d-flex flex-column">
                            <div class="d-flex align-items-center mb-3">
                                <i class="fa fa-file-pdf-o fa-2x text-danger me-3"></i> <!-- PDF icon -->
                                <h5 class="card-title text-primary m-0">{{ $pdf->name }}</h5>
                            </div>
                            <p class="card-text text-muted flex-grow-1">{{ Str::limit($pdf->description, 100) }}</p>
                            <div class="d-flex justify-content-between align-items-center mt-auto">
                                <div class="d-flex justify-content-center align-items-center mt-auto">
                                <button type="button" class="btn btn-sm btn-outline-light view-pdf-btn"
                                        data-bs-toggle="modal" data-bs-target="#pdfViewerModal"
                                        data-pdf-url="{{ asset('pdfs/' . $pdf->file) }}">
                                    View PDF
                                </button>
                            </div>
                            </div>
                        </div>
                    </div>
                </div>

    <!-- PDF Viewer Modal -->
<div class="modal fade" id="pdfViewerModal" tabindex="-1" aria-labelledby="pdfViewerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="pdfViewerModalLabel">View PDF</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <iframe id="pdfViewerFrame" style="width:100%; height:70vh;" frameborder="0"></iframe>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $('#pdfViewerModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); // Button that triggered the modal
        var pdfUrl = button.data('pdf-url'); // Extract info from data-* attributes

        var modal = $(this);
        // Using Google Docs Viewer for better cross-browser compatibility and features
        modal.find('#pdfViewerFrame').attr('src', 'https://docs.google.com/gview?url=' + encodeURIComponent(pdfUrl) + '&embedded=true');
    });

    // Clear iframe src when modal is hidden to stop PDF loading in background
    $('#pdfViewerModal').on('hidden.bs.modal', function () {
        $(this).find('#pdfViewerFrame').attr('src', '');
    });
});
</script>
            @empty
                <div class="col-12">
                    <p class="alert alert-info text-center">No PDF study materials available at the moment.</p>
                </div>
            @endforelse
        </div>
        <div class="text-center mt-4">
            <a href="{{ url()->previous() }}" class="btn btn-secondary">Go Back</a>
        </div>
    </div>

    <style>
        .study-materials-container {
            padding: 30px 0;
        }
        .section-title {
            font-size: 2.2rem;
            font-weight: 700;
            color: #333;
            margin-bottom: 30px;
        }
        .study-material-card {
            border: none; /* Remove default border */
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            background: linear-gradient(45deg, #FF8C00, #000000); /* Orange to Black gradient */
            color: white; /* Ensure text is visible on dark background */
        }
        .study-material-card .card-title,
        .study-material-card .card-text {
            color: white !important; /* Override text colors to white */
        }
        .study-material-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        }
        .study-material-card .card-body {
            padding: 20px;
        }
        .study-material-card .card-title {
            font-size: 1.4rem;
            margin-bottom: 10px;
        }
        .study-material-card .card-text {
            font-size: 0.95rem;
            line-height: 1.5;
            min-height: 60px; /* Consistent height for description */
        }
    </style>
@endsection