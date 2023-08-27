<div class="relative hidden" style="z-index: 99999;" aria-labelledby="modal-title" role="dialog" aria-modal="true"
    id="settingModal">
    <!--
      Background backdrop, show/hide based on modal state.
  
      Entering: "ease-out duration-300"
        From: "opacity-0"
        To: "opacity-100"
      Leaving: "ease-in duration-200"
        From: "opacity-100"
        To: "opacity-0"
    -->
    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>

    <div class="fixed inset-0 z-10 overflow-y-auto">
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
            <!--
          Modal panel, show/hide based on modal state.
  
          Entering: "ease-out duration-300"
            From: "opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            To: "opacity-100 translate-y-0 sm:scale-100"
          Leaving: "ease-in duration-200"
            From: "opacity-100 translate-y-0 sm:scale-100"
            To: "opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
        -->
            <div
                class="relative transform rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
                <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4 rounded">
                    <form action="{{ route('setting.update') }}" method="POST" id="settingForm">
                        @csrf
                        <div class="flex flex-col mb-3">
                            <label for="api_url" class="text-sm font-medium text-gray-700 mb-1">API
                                Endpoint/Url</label>
                            <input type="text" name="api_url" id="api_url"
                                value="{{ SettingHelper::get('api_url') }}"
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        </div>
                        <div class="flex flex-col mb-3">
                            <label for="api_chat" class="text-sm font-medium text-gray-700 mb-1">API
                                Endpoint/Url API Chat Completion</label>
                            <input type="text" name="api_chat" id="api_chat"
                                value="{{ SettingHelper::get('api_chat') }}"
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        </div>
                        <div class="flex flex-col mb-3">
                            <label for="api_token" class="text-sm font-medium text-gray-700 mb-1">API Token</label>
                            <input type="password" name="api_token" id="api_token"
                                value="{{ SettingHelper::get('api_token') }}"
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        </div>
                        <div class="flex flex-col mb-3">
                            <label for="stream_message" class="text-sm font-medium text-gray-700 mb-1">Stream
                                Message?</label>
                            <label>
                                <input type="checkbox" class="accent-blue-500" id="stream_message" name="stream_message"
                                    value="true" @if (SettingHelper::get('stream_message') == 'true') checked @endif> Stream Response
                            </label>
                        </div>
                        <div class="flex flex-col mb-3">
                            <label for="api_token" class="text-sm font-medium text-gray-700 mb-1">Model <small>Type
                                    custom model if doest exist in selection</small></label>
                            {{-- make select option with tailwind css and alpine js that can search and type custom option if not exist --}}
                            <select name="model" id="model">
                                @php
                                    $models = ['gpt-3.5-turbo', 'gpt-3.5-turbo-16k', 'gpt-4'];
                                @endphp
                                @foreach ($models as $model)
                                    <option value="{{ $model }}"
                                        @if (SettingHelper::get('model') == $model) selected @endif>{{ $model }}</option>
                                @endforeach
                                @if (in_array(SettingHelper::get('model'), $models) == false)
                                    <option value="{{ SettingHelper::get('model') }}" selected>{{ SettingHelper::get('model') }}</option>
                                @endif
                            </select>
                        </div>
                    </form>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                    <button type="button"
                        class="inline-flex w-full justify-center items-center rounded-md bg-blue-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 sm:ml-3 sm:w-auto"
                        onclick="settingForm.submit()"><i class="fas fa-save mr-1"></i> <span>Save</span></button>
                    <button type="button"
                        class="mt-3 inline-flex modal-exit w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto">Cancel</button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('js')
    <script type="module">
        new TomSelect("#model", {
            create: true,
            sortField: {
                field: "text",
                direction: "asc"
            }
        });
    </script>
@endpush
