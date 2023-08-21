<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('My Todo List') }}
        </h2>
    </x-slot>

    <div class="max-w-3xl mx-auto p-4 sm:p-6 lg:p-8">
        <form method="POST" action="{{ route('tasks.store') }}" class="mb-4">
        @csrf
            <textarea
                name="message"
                placeholder="{{ __('What\'s on your mind?') }}"
                class="block w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"
            >{{ old('message') }}</textarea>
            <x-input-error :messages="$errors->store->get('message')" class="mt-2" />
            <button class="mt-4 bg-green-500 text-white py-2 px-4 rounded-md hover:bg-green-600">{{ __('Add Task') }}</button>
        </form>

        <div class="mt-6 bg-white shadow-sm rounded-lg divide-y divide-gray-200">
            @foreach ($tasks as $task)
                <div class="p-6 flex flex-col space-y-2">
                    <div class="flex justify-between items-center mb-2">
                        <div>
                            <span class="text-gray-800 font-semibold">{{ $task->user->name }}</span>
                            <small class="ml-2 text-sm text-gray-600">{{ $task->created_at->format('j M Y, g:i a') }}</small>
                            @unless ($task->created_at->eq($task->updated_at))
                                <small class="text-sm text-gray-600"> &middot; {{ __('edited') }}</small>
                            @endunless
                        </div>
                        @if ($task->user->is(auth()->user()))
                        <form method="POST" action="{{ route('tasks.complete', $task) }}">
                             @csrf
                              @method('put')
                              <div class="flex items-center">
                                 <input
                                   type="checkbox"
                                   class="form-checkbox h-4 w-4 text-indigo-600 transition duration-150 ease-in-out"
                                   data-task-id="{{ $task->id }}"
                                  {{ $task->completed ? 'checked' : '' }}>
                                 <span class="ml-2 text-sm text-gray-600">Complete</span>
                             </div>
                        </form>
                    </div>
                    <p class="text-gray-800">{{ $task->message }}</p>
                    
                        <div class="mt-2 flex justify-start space-x-2">
                            <a href="{{ route('tasks.edit', $task) }}" class="bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600">Edit</a>
                            <form method="POST" action="{{ route('tasks.destroy', $task) }}">
                                @csrf
                                @method('delete')
                                <button type="submit" class="bg-red-500 text-white py-2 px-4 rounded-md hover:bg-red-600">Delete</button>
                            </form>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const checkboxes = document.querySelectorAll('.form-checkbox');

        checkboxes.forEach(checkbox => {
            const taskId = checkbox.getAttribute('data-task-id');
            const storageKey = `task-${taskId}`;

            // Check local storage for stored value
            const storedValue = localStorage.getItem(storageKey);
            if (storedValue === 'completed') {
                checkbox.checked = true;
            }

            // Listen for changes and update local storage
            checkbox.addEventListener('change', function() {
                const newValue = this.checked ? 'completed' : '';
                localStorage.setItem(storageKey, newValue);
            });
        });
    });
</script>

</x-app-layout>



