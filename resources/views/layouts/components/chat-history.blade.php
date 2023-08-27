@foreach ($chat->chat_histories as $item)
    <x-chat-bubble :role="$item->from" :content="$item->from == 'assistant' ? $item->markdown_message : $item->message" />
@endforeach
