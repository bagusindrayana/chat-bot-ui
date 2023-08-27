@extends('layouts.base', ['chatId' => @$chat->id ?? 0])

@section('content')
    <div class="container mx-auto space-y-6 px-8 xl:px-4 py-24 lg:p-8 lg:pb-28 max-w-3xl xl:max-w-7xl" id="listMessage">
        @if (isset($chat))
            @foreach ($chat->chat_histories as $item)
                <x-chat-bubble :role="$item->from" :content="$item->from == 'assistant' ? $item->markdown_message : $item->message" />
            @endforeach
        @endif
    </div>
@endsection

@section('footer')
    <form class="container mx-auto flex h-20 items-center space-x-2 px-4 lg:px-8 xl:max-w-7xl" id="chatInputForm">
        <input type="text" id="chat-message"
            class="mx-5 block w-full rounded-lg border-0 px-5 py-4 leading-6 focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-75"
            placeholder="Type a new message and hit enter.." />
        <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-3 px-5 rounded mx-4" id="sendButton"><i
                class="fas fa-paper-plane"></i></button>
    </form>
@endsection

@push('js')
    <template id="assistantMessage">
        <x-chat-bubble role="assistant" content="" />
    </template>
    <template id="userMessage">
        <x-chat-bubble role="user" content="" />
    </template>

    <script type="module">
        var streamMessage = "{{ SettingHelper::get('stream_message') }}";
        var chatId = {{ @$chat->id ?? 0 }};
        var assistantMessageElementId = null;
        const listMessage = document.getElementById('listMessage');
        const inputMessage = document.getElementById('chat-message');
        window.scrollTo({
            top: document.body.scrollHeight,
            behavior: 'smooth'
        });
        // document.getElementById('chatItem'+chatId).scrollIntoView();

        //send message with xhr and return response as stream
        function sendMessage(message) {
            sendButton.disabled = true;
            inputMessage.disabled = true;
            assistantMessageElementId = null;
            var userMessageElement = document.getElementById('userMessage').content.cloneNode(true);
            userMessageElement.querySelector('.message-content').innerHTML = message;
            listMessage.appendChild(userMessageElement);

            var responseMessage = "";
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "{{ url('api/chat') }}/" + chatId + "/send-message", true);
            xhr.setRequestHeader('Content-Type', 'application/json');
            xhr.setRequestHeader('X-CSRF-TOKEN', '{{ csrf_token() }}');
            xhr.onreadystatechange = function() {

                if (xhr.status == 500) {
                    sendButton.disabled = false;
                    inputMessage.disabled = false;
                    alert("Something went wrong, please try again later");
                    return;
                }
                if (xhr.status == 200 && (xhr.readyState == 3 || xhr.readyState == 4) && xhr.responseText != "") {
                    var pieces = "";
                    if (streamMessage == "true") {
                        //split new line or break
                        const readlines = xhr.responseText.split(/\r?\n/);

                        for (let i = 0; i < readlines.length; i++) {
                            const readline = readlines[i];
                            var datas = readline.split("data: ");
                            if (datas.length > 1) {
                                if (datas[1] == "[DONE]") {
                                    window.scrollTo({
                                        top: document.body.scrollHeight,
                                        behavior: 'smooth'
                                    });
                                    saveMessage(responseMessage);
                                } else {
                                    try {
                                        var dataJson = JSON.parse(datas[1]);
                                        if (dataJson.choices != undefined && dataJson.choices.length > 0) {
                                            if (dataJson.choices[0].delta != undefined) {
                                                pieces += dataJson.choices[0].delta.content;
                                            } else {
                                                pieces += dataJson.choices[0].message.content;
                                            }


                                            // responseMessage += dataJson.choices[0].delta.content;
                                            if (assistantMessageElementId == null) {
                                                var clone = document.getElementById('assistantMessage').content
                                                    .cloneNode(
                                                        true);
                                                //change id
                                                clone.querySelector('.message-content').id = dataJson['id'];
                                                assistantMessageElementId = dataJson['id'];
                                                clone.querySelector('.message-content').setAttribute('id', dataJson[
                                                    'id']);
                                                clone.querySelector('.message-content').setAttribute('data-id',
                                                    dataJson[
                                                        'id']);

                                                clone.querySelector('.message-content').innerHTML = responseMessage;
                                                listMessage.appendChild(clone);
                                            } else {
                                                var m = document.getElementById(assistantMessageElementId);
                                                console.log(assistantMessageElementId);
                                                if (m) {

                                                    m.innerHTML = window.marked.parse(responseMessage);
                                                }
                                            }
                                        }
                                    } catch (error) {
                                        console.log(error);
                                    }
                                }

                            }
                        }
                    } else {

                        try {
                            var dataJson = JSON.parse(xhr.responseText);
                            console.log(dataJson);
                            if (dataJson.choices != undefined && dataJson.choices.length > 0) {
                                if (dataJson.choices[0].delta != undefined) {
                                    pieces += dataJson.choices[0].delta.content;
                                } else if (dataJson.choices[0].message != undefined) {
                                    pieces += dataJson.choices[0].message.content;
                                }


                                // responseMessage += dataJson.choices[0].delta.content;
                                if (assistantMessageElementId == null) {
                                    var clone = document.getElementById('assistantMessage').content.cloneNode(
                                        true);
                                    //change id
                                    clone.querySelector('.message-content').id = dataJson['id'];
                                    assistantMessageElementId = dataJson['id'];
                                    clone.querySelector('.message-content').setAttribute('id', dataJson['id']);
                                    clone.querySelector('.message-content').setAttribute('data-id', dataJson[
                                        'id']);

                                    clone.querySelector('.message-content').innerHTML = responseMessage;
                                    listMessage.appendChild(clone);
                                } else {
                                    var m = document.getElementById(assistantMessageElementId);
                                    console.log(assistantMessageElementId);
                                    if (m) {

                                        m.innerHTML = window.marked.parse(responseMessage);
                                    }
                                }

                                window.scrollTo({
                                    top: document.body.scrollHeight,
                                    behavior: 'smooth'
                                });
                            }
                        } catch (error) {
                            console.log(xhr.responseText);
                            console.log(error);
                        }
                    }


                    if (pieces != responseMessage) {
                        responseMessage = pieces;
                    }


                } else {
                    console.log(xhr.readyState);
                    console.log(xhr.responseText);
                }
                window.history.pushState(null, message, "{{ url('chat') }}/" + chatId);
                chatId = xhr.getResponseHeader("Chat-Id");
            };
            var data = JSON.stringify({
                message: message
            });

            xhr.send(data);

            const newChat = document.getElementById("newChat");
            if (newChat) {
                newChat.setAttribute('href', "{{ url('chat') }}/" + chatId);
                newChat.querySelector('.title-chat').innerHTML = message;
            }
            window.scrollTo({
                top: document.body.scrollHeight,
                behavior: 'smooth'
            });




        }

        function saveMessage(message) {
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "{{ url('api/chat') }}/" + chatId + "/save-message", true);
            xhr.setRequestHeader('Content-Type', 'application/json');
            xhr.setRequestHeader('X-CSRF-TOKEN', '{{ csrf_token() }}');
            xhr.onreadystatechange = function() {

                if (xhr.readyState == 4 && xhr.status == 200) {
                    console.log(xhr.responseText);
                }
                sendButton.disabled = false;
                inputMessage.disabled = false;

            };
            var data = JSON.stringify({
                message: message
            });

            xhr.send(data);
        }

        //listen submit from chatInputForm
        document.getElementById('chatInputForm').addEventListener('submit', function(e) {
            e.preventDefault();
            var message = document.getElementById('chat-message').value;
            sendMessage(message);
            document.getElementById('chat-message').value = '';
        });
    </script>
@endpush
