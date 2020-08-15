<h3 class="font-bold text-xl mb-4">Friends</h3>

<ul>
    @foreach (range(1, 8) as $index)
        <li class="mb-4">
            <div class="flex items-center text-sm">
                <img 
                    src="https://i.pravatar.cc/40" 
                    alt=""
                    class="rounded-full mr-2"
                >

                John Doe
            </div>
        </li>
    @endforeach
</ul>








{{-- <div class="bg-gray-200 border border-gray-300 rounded-lg py-4 px-6">
    <h3 class="font-bold text-xl mb-4">Following</h3>

    <ul>
        @forelse (current_user()->follows as $user)
            <li class="{{ $loop->last ? '' : 'mb-4' }}">
                <div>
                    <a href="{{ $user->path() }}" class="flex items-center text-sm">
                        <img
                            src="{{ $user->avatar }}"
                            alt=""
                            class="rounded-full mr-2"
                            width="40"
                            height="40"
                        >

                        {{ $user->name }}
                    </a>
                </div>
            </li>
        @empty
            <li>No friends yet!</li>
        @endforelse
    </ul>
</div>
© 2020 GitHub, Inc. --}}