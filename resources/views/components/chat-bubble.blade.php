@if ($role == 'assistant')
    <div class="chat-bubble flex w-5/6 flex-col items-start space-y-2 lg:w-2/3 xl:w-2/3 ">
        <div class="w-fit block rounded-xl bg-indigo-50 px-4 py-2 font-medium text-indigo-900 message-content">
            <div class="content max-w-6xl overflow-auto">
                {!! @$content !!}
            </div>
        </div>

    </div>
@else
    <div class="chat-bubble ml-auto flex w-5/6 flex-col items-end space-y-2 lg:w-2/3 xl:w-2/3 ">
        <div class="w-fit block rounded-xl bg-indigo-600 px-1 md:px-4 py-2 font-medium text-indigo-50 message-content">
            <div class="content max-w-6xl overflow-auto">
                {{ @$content }}
            </div>
        </div>

    </div>
@endif
