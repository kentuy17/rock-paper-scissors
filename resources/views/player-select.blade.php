@extends('layouts.app')

@section('content')
  <div class="mx-8 grid gap-6 lg:gap-8">
    <div id="docs-card" class="grid justify-items-around overflow-hidden rounded-lg bg-white p-6 shadow-[0px_14px_34px_0px_rgba(0,0,0,0.08)] ring-1 ring-white/[0.05] transition duration-300 hover:text-black/70 hover:ring-black/20 focus:outline-none focus-visible:ring-[#FF2D20] md:row-span-3 lg:p-10 lg:pb-10 dark:bg-zinc-900 dark:ring-zinc-800 dark:hover:text-white/70 dark:hover:ring-zinc-700 dark:focus-visible:ring-[#FF2D20]">
      <div class="columns-3 gap-6 flex justify-around">
        <form action="/player-select" class="grid gap-6" method="POST">
          @csrf
          <select class="text-xl text-black" name="player" id="player">
            @foreach ($players as $player)
              <option @if ($player->status !== 'Offline') disabled @endif value="{{ $player->id }}">{{ $player->name }} ({{ $player->status }})</option>
            @endforeach

          </select>
          <button type="submit">Select</button>
        </form>

        {{-- @foreach ($players as $player)
          <div class="py-4">
            <h2 id="l-player" class="text-xl">{{ $player->name }}</h2>
          </div>
        @endforeach --}}
      </div>
    </div>
  </div>
@endsection

@section('scripts')
  <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
@endsection
