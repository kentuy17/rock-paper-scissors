@extends('layouts.app')

@section('content')
  <div class="mx-8 grid gap-6 lg:gap-8">
    <div id="docs-card" class="grid justify-items-around overflow-hidden rounded-lg bg-white p-6 shadow-[0px_14px_34px_0px_rgba(0,0,0,0.08)] ring-1 ring-white/[0.05] transition duration-300 hover:text-black/70 hover:ring-black/20 focus:outline-none focus-visible:ring-[#FF2D20] md:row-span-3 lg:p-10 lg:pb-10 dark:bg-zinc-900 dark:ring-zinc-800 dark:hover:text-white/70 dark:hover:ring-zinc-700 dark:focus-visible:ring-[#FF2D20]">

      <div class="columns-3 gap-6 flex justify-around">
        <div class="py-4">
          <p>{{ session('player_name') }}</p>
          <h2 id="l-player" class="text-9xl">??</h2>
        </div>
        <div class="flex items-center py-4 flex-col">
          <h4 id="round-no" class="text-2xl font-bold pb-4">Round# {{ $round }}</h4>
          <h2 id="result-text" class="text-9xl">--</h2>
          <p id="match-record" class="text-xl">0-0-0</p>
        </div>
        <div class="py-4">
          <h2 id="r-player" class="text-9xl">??</h2>
        </div>
      </div>
    </div>

    <div class="min-h-80 flex items-start p-8 gap-8 rounded-lg bg-white shadow-[0px_14px_34px_0px_rgba(0,0,0,0.08)] ring-1 ring-white/[0.05] transition duration-300 hover:text-black/70 hover:ring-black/20 focus:outline-none focus-visible:ring-[#FF2D20] lg:pb-10 dark:bg-zinc-900 dark:ring-zinc-800 dark:hover:text-white/70 dark:hover:ring-zinc-700 dark:focus-visible:ring-[#FF2D20]">
      <div class="px-8 gap-6 cursor-pointer flex justify-center">
        <input type="hidden" name="player_move" id="player_move" value="">
        <input type="hidden" name="game-mode" id="game-mode" value="{{ session('game_mode') }}">
        @foreach ($actions as $action)
          <button onclick="moveHandler('{{ $action->code }}')" @if (session('game_mode') == 'random') disabled @endif data-move="{{ $action->code }}" class="move-opt flex size-12 shrink-0 items-center justify-center rounded-full sm:size-16">
            <h3 class="text-5xl">{{ $action->icon }}</h3>
          </button>
        @endforeach
        {{-- ✊✋✌️🤜 --}}
      </div>
      <div class="px-8 gap-6 cursor-pointer flex justify-center">

        <button onclick="manualBet()" @if (session('game_mode') == 'random') disabled @endif class=" size-12 move-opt flex items-center justify-center text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300  rounded-lg px-8 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800" type="button">
          <h2 class="font-bold text-xl">GO</h2>
        </button>
      </div>
    </div>
    <!-- Modal toggle -->
  </div>
  <!-- Main modal -->
  <div id="default-modal" tabindex="-1" class="@if ($round == 10) {{ 'flex' }}
    @else
    {{ 'hidden' }} @endif overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative p-4 w-full max-w-2xl max-h-full">
      <!-- Modal content -->
      <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
        <!-- Modal header -->
        <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
          <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
            Player Stats
          </h3>
          <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="default-modal">
            <svg class="w-3 h-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
              <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
            </svg>
            <span class="sr-only">Close modal</span>
          </button>
        </div>
        <!-- Modal body -->
        <div class="p-4 md:p-5 space-y-4">
          <p class="text-base leading-relaxed text-gray-500 dark:text-gray-400">
            @include('results-table')
          </p>
        </div>
        <!-- Modal footer -->
        <div class="flex items-center p-4 md:p-5 border-t border-gray-200 rounded-b dark:border-gray-600">
          <button id="play-again" data-modal-hide="default-modal" type="button" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Play again</button>
          <button data-modal-hide="default-modal" type="button" class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">Quit</button>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('scripts')
  <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
  <script>
    axios.defaults.headers = {
      'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
    };
    const lPlayer = document.getElementById("l-player")
    const rPlayer = document.getElementById("r-player")
    const resultTxt = document.getElementById('result-text')
    const handSigns = ["👊", "✋", "✌️", ]
    const handArr = [{
        icon: "👊",
        code: "ROC"
      },
      {
        icon: "✋",
        code: "PAP"
      },
      {
        icon: "✌️",
        code: "SCI"
      },
    ]

    function ready(fn) {
      if (document.readyState !== 'loading') {
        fn();
        return;
      }
      document.addEventListener('DOMContentLoaded', fn);
    }

    function clear() {
      const moves = document.querySelectorAll('.move-opt')
      moves.forEach((el) => el.classList.remove('bg-slate-600'))
    }

    function moveHandler(val) {
      const mode = document.getElementById('game-mode')
      if (mode.value == 'random') {
        alert('cannot be selected manually')
        return
      }

      clear();
      let playerMove = document.getElementById('player_move')
      playerMove.value = val

      let btnSelected = document.querySelector(`[data-move="${val}"]`)
      btnSelected.classList.add('bg-slate-600')

      let index = handArr.findIndex(x => x.code === val);
      lPlayer.innerHTML = handArr[index].icon
    }

    function manualBet() {
      let playerMove = document.getElementById('player_move')

      if (playerMove.value == "") {
        alert('Please select a move')
        return;
      }

      const randomized = setInterval(() => {
        let randAnimation = handSigns[Math.floor(Math.random() * handSigns.length)];
        rPlayer.innerHTML = randAnimation
        resultTxt.innerHTML = '.......'
      }, 100);

      setTimeout(() => {
        axios.post('/manual-move', {
          playerMove: playerMove.value,
        }, {
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
          }
        }).then(({
          data
        }) => {
          clearInterval(randomized)
          let xIndex = handArr.findIndex(x => x.code === data.match_info.opponent_move);
          rPlayer.innerHTML = handArr[xIndex].icon
          resultTxt.innerHTML = data.result

          const matchRecord = document.getElementById('match-record')
          matchRecord.innerHTML = `${data.tally.win}-${data.tally.lose}-${data.tally.draw}`

          const roundNum = document.getElementById('round-no')
          roundNum.innerHTML = `Round #${data.match_info.round_no}`

          console.log(data.tally);
          if (data.match_info.round_no == 10) {
            const modalTally = document.getElementById('default-modal')
            modalTally.classList.remove('hidden')
            modalTally.classList.add('flex')

            let sPlayerName = document.getElementById('stat_player_name')
            let sPlayerRounds = document.getElementById('stat_player_rounds')
            let sPlayerWins = document.getElementById('stat_player_wins')
            let sPlayerTies = document.getElementById('stat_player_ties')
            let sPlayerPercent = document.getElementById('stat_player_percent')

            let sOpponentName = document.getElementById('stat_opponent_name')
            let sOpponentRounds = document.getElementById('stat_opponent_rounds')
            let sOpponentWins = document.getElementById('stat_opponent_wins')
            let sOpponentTies = document.getElementById('stat_opponent_ties')
            let sOpponentPercent = document.getElementById('stat_opponent_percent')

            sPlayerName.innerHTML = data.stats.player.player_id == 1 ? 'Player 1' : 'Player 2';
            sPlayerRounds.innerHTML = data.stats.player.total_rounds_played
            sPlayerWins.innerHTML = data.stats.player.total_wins;
            sPlayerTies.innerHTML = data.stats.player.total_ties;
            sPlayerPercent.innerHTML = `${((data.stats.player.total_wins / data.stats.player.total_rounds_played) *100)
              .toFixed(2)}%`

            sOpponentName.innerHTML = data.stats.opponent.player_id == 1 ? 'Player 1' : 'Player 2';
            sOpponentRounds.innerHTML = data.stats.opponent.total_rounds_played
            sOpponentWins.innerHTML = data.stats.opponent.total_wins;
            sOpponentTies.innerHTML = data.stats.opponent.total_ties;
            sOpponentPercent.innerHTML = `${((data.stats.opponent.total_wins / data.stats.opponent.total_rounds_played) *100)
              .toFixed(2)}%`
          }
        })
      }, 2000);
    }

    const btnPlayAgain = document.getElementById('play-again')
    btnPlayAgain.addEventListener('click', function() {
      let baseUrl = window.location.origin;
      window.location.href = `${baseUrl}/new-match`;
    })

    ready(function() {
      console.log(handSigns)



    })
  </script>
@endsection
