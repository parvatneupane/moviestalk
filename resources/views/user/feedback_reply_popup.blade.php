<div id="feedbackReplyModal" class="feedback-reply-modal">
    <div class="feedback-reply-content">
        <button id="closeFeedbackModal" class="close-modal">&times;</button>
        <h3>Admin Reply</h3>
        @if($reply)
            <p>{{ $reply->reply }}</p>
        @else
            <p>No reply yet.</p>
        @endif
    </div>
</div>




