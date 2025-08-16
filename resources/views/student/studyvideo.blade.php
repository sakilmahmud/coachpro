@extends('layout/student-layout')

@section('space-work')
<div class="text-right"><img src="{{ asset('image/logost.png') }}" alt="Image" width="20%" height="20%"></div>
<h2>Videos</h2>

<table class="table">
    <thead>
        <th>#</th>
        <th>Topic</th>
        <th>Video</th>
       
    </thead>
    <tbody>
            @php
            $counter = 1;
            @endphp

        @foreach($subjects as $subject)
        <tr>
            <td>{{ $counter ++}}</td>
            <td>{{$subject->topic}}</td>
            <td>
                <!-- Display the video player when the link is clicked -->
                <a href="javascript:void(0);" onclick="playVideo('{{ asset($subject->link) }}')">Watch</a>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

<!-- Video player modal -->
<div id="videoModal" class="modal" style="display: none;">
    <div class="modal-content">
        <span class="close" onclick="closeModal()">&times;</span>
        <div class="video-wrapper">
            <iframe id="videoPlayer" width="640" height="360" src="" frameborder="0" allowfullscreen></iframe>
        </div>
    </div>
</div>

<style>
    /* CSS to make the video container responsive */
    .video-wrapper {
        position: relative;
        padding-bottom: 56.25%; /* 16:9 aspect ratio (change as needed) */
        height: 0;
        overflow: hidden;
    }

    .video-wrapper iframe {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
    }

    /* CSS for the modal */
    .modal {
        display: none;
        position: fixed;
        z-index: 1;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0, 0, 0, 0.7);
    }

    .modal-content {
        background-color: #fefefe;
        margin: 15% auto;
        padding: 20px;
        border: 1px solid #888;
        width: 80%;
        max-width: 800px;
    }

    .close {
        color: #aaa;
        float: right;
        font-size: 28px;
        font-weight: bold;
        cursor: pointer;
    }

    .close:hover,
    .close:focus {
        color: black;
        text-decoration: none;
        cursor: pointer;
    }
</style>

<script>
    // Function to play the video in the modal
    function playVideo(videoLink) {
        const videoModal = document.getElementById('videoModal');
        const videoPlayer = document.getElementById('videoPlayer');

        // Set the source of the iframe to the YouTube video link
        videoPlayer.src = videoLink;

        // Display the video modal
        videoModal.style.display = 'block';
    }

    // Function to close the modal
    function closeModal() {
        const videoModal = document.getElementById('videoModal');
        const videoPlayer = document.getElementById('videoPlayer');

        // Clear the source of the iframe to stop the video
        videoPlayer.src = '{{ asset($subject->link) }}';

        // Hide the video modal
        videoModal.style.display = 'none';
    }
</script>
@endsection
