<div class="w-full chat-item" data-id="{{$chat->id}}" data-title="{{$chat->title}}" id="chatItem{{$chat->id}}">
    @if (isset($chatId) && $chatId == $chat->id)
        <a href="#" class="flex items-center space-x-3 rounded-l border-l-4 chat selected">

            <div class="grow">
                <p class="mb-0.5 line-clamp-1 text-sm font-bold">{{ $chat->title }}</p>

            </div>
            <div class="flex self-start items-center">
                <p class="text-xs font-medium text-slate-400">{{ $chat->created_at->diffForHumans() }}</p>
                <button class="mx-2 text-indigo-500 delete-button" type="button" onclick="deleteChat{{$chat->id}}.submit(); event.stopPropagation();"><i class="fas fa-trash"></i></button>
            </div>
        </a>
    @else
        <a href="#" class="flex items-center space-x-3 rounded-l border-l-4 chat">

            <div class="grow">
                <p class="mb-0.5 line-clamp-1 text-sm font-bold">{{ $chat->title }}</p>

            </div>
            <div class="flex self-start items-center">
                <p class="text-xs font-medium text-slate-400">{{ $chat->created_at->diffForHumans() }}</p>
                <button class="mx-2 text-indigo-500 delete-button" type="button" onclick="deleteChat{{$chat->id}}.submit(); event.stopPropagation();"><i class="fas fa-trash"></i></button>
            </div>
        </a>
    @endif
    <form action="{{ route('chat.destroy',$chat->id) }}" class="hidden" id="deleteChat{{$chat->id}}" method="POST">
        @csrf
        @method('DELETE')
    </form>
</div>
