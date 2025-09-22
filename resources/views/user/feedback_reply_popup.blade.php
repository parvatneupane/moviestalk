<div class="feedback-reply-content">
    <button class="close-modal">&times;</button>
    <h3>Admin Reply</h3>
    @if($reply)
        <p>{{ $reply->reply }}</p>
    @else
        <p>No reply yet.</p>
    @endif
</div>
<style>
    .feedback-reply-content {
    position: relative;         /* needed for absolute positioning of close button */
    background: #352f2f;        /* dark background */
    padding: 20px 30px;         /* inner spacing */
    border-radius: 8px;         /* rounded corners */
    max-width: 500px;           /* max width */
    width: 90%;                 /* responsive width */
    box-shadow: 0 5px 15px rgba(0,0,0,0.3); /* subtle shadow */
}

.feedback-reply-content h3 {
    margin-top: 0;
    color: #fff;
}

.feedback-reply-content p {
    color: #ddd;
}

/* Close button */
.feedback-reply-content .close-modal {
    position: absolute;
    top: 10px;
    right: 10px;
    background: transparent;
    border: none;
    color: #fff;
    font-size: 24px;
    font-weight: bold;
    cursor: pointer;
    transition: color 0.2s ease;
}

.feedback-reply-content .close-modal:hover {
    color: #ff4d4d; /* changes color on hover */
}

</style>