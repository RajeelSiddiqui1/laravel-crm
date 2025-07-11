<div class="d-flex mb-3 justify-content-end" id="message-{{ $msg->id }}">
    <div>
        <div class="p-3 rounded bg-primary text-white shadow-sm">
            @if ($msg->content)
                <p class="mb-0">{{ $msg->content }}</p>
            @endif
        </div>
        <div class="text-muted small mt-1 text-end">
            {{ $msg->created_at->format('h:i A, M d') }}
        </div>
        <div class="message-actions position-absolute" style="top: 10px; right: 10px;">
            <button class="btn btn-sm btn-outline-primary edit-message" data-id="{{ $msg->id }}" data-content="{{ $msg->content }}" title="Edit">
                <i class="fas fa-edit"></i>
            </button>
            <form action="{{ route('team_lead.message.delete', $msg->id) }}" method="POST" class="d-inline delete-message-form">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                    <i class="fas fa-trash"></i>
                </button>
            </form>
        </div>
    </div>
    <img src="{{ $imagePath }}" class="rounded-circle ms-2" width="40" height="40" alt="Sender" style="object-fit: cover;">
</div>
