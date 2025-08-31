@extends('layout.student-layout')

@section('space-work')
<style>
    .video_item i{
        background: #fff;
        padding: 5px;
        border-radius: 5px;
    }
</style>
    <div class="container study-materials-container">
        <h2 class="section-title text-center mb-4">Study Materials: Videos</h2>
        <div class="row">
            @forelse($videolinks as $video)
                <div class="col-md-4 mb-4">
                    <div class="card study-material-card h-100 shadow-sm">
                        <div class="card-body d-flex flex-column">
                            <div class="video_item d-flex justify-content-center align-items-center mb-3">
                                <i class="fa fa-play-circle fa-2x text-danger me-3"></i> <!-- Video icon -->
                                <h5 class="card-title text-primary m-0">{{ $video->topic }}</h5>
                            </div>
                            <div class="d-flex justify-content-center align-items-center mt-auto">
                                <button type="button" class="btn btn-sm btn-outline-light view-video-btn"
                                        data-bs-toggle="modal" data-bs-target="#videoViewerModal"
                                        data-video-url="{{ $video->link }}">
                                    Watch Video
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

<!-- Video Viewer Modal -->
<div class="modal fade" id="videoViewerModal" tabindex="-1" aria-labelledby="videoViewerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="videoViewerModalLabel">Watch Video</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="embed-responsive embed-responsive-16by9">
                    <iframe class="embed-responsive-item" id="videoViewerFrame" allowfullscreen></iframe>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    function getEmbedUrl(url) {
        var embedUrl = '';
        if (url.includes('youtube.com/watch?v=')) {
            embedUrl = url.replace('watch?v=', 'embed/');
        } else if (url.includes('youtu.be/')) {
            embedUrl = url.replace('youtu.be/', 'youtube.com/embed/');
        } else if (url.includes('vimeo.com/')) {
            embedUrl = url.replace('vimeo.com/', 'player.vimeo.com/video/');
        } else {
            embedUrl = url; // Fallback for other direct video links
        }
        return embedUrl;
    }

    $('#videoViewerModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); // Button that triggered the modal
        var videoUrl = button.data('video-url'); // Extract info from data-* attributes

        var embedSrc = getEmbedUrl(videoUrl);

        var modal = $(this);
        modal.find('#videoViewerFrame').attr('src', embedSrc);
    });

    // Clear iframe src when modal is hidden to stop video playing in background
    $('#videoViewerModal').on('hidden.bs.modal', function () {
        $(this).find('#videoViewerFrame').attr('src', '');
    });
});
</script>
            @empty
                <div class="col-12">
                    <p class="alert alert-info text-center">No video study materials available at the moment.</p>
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
        #videoViewerModal .modal-body {
            padding: 0; /* Remove padding from modal body */
        }

        #videoViewerFrame {
            width: 100%;
            height: 100%;
            min-height: 400px; /* Ensure a minimum height for visibility */
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