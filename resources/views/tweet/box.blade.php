<?php $schedules_to_seconds = strtotime($tweet->schedule) ?>

@if($error)
<div class="box">
   <progress class="progress is-danger is-small" value="100" max="100">100%</progress>
@else
<div class="box" data-time-schedule="{{ $tweet->schedule }}">
   <progress class="progress is-small" value="{{ floor(100 - (($schedules_to_seconds - time()) / 60 / 60 / 24)) }}" max="100">15%</progress>
@endif

   <article class="media">
       <div class="media-left">
           <figure class="image is-64x64">
               <img src="{{ $tweet->account->picture_url }}" alt="{{ $tweet->account->handle }}">
           </figure>
       </div>
       <div class="media-content">
           <div class="content">
               @if(!empty($tweet->content))
                   <p>
                       {!! nl2br($tweet->content) !!}
                   </p>

                   @if($tweet->media->isEmpty() == false)
                       <div class="media-showcase">
                           @foreach($tweet->media as $m)
                               <img src="{{ $m->as_data }}">
                           @endforeach
                       </div>
                   @endif
               @else
                   <p>
                       <span class="icon"><i class="fa fa-retweet"></i></span> {{ $tweet->retweet }}
                   </p>
               @endif
           </div>

           @if($error)
               <nav class="level is-mobile">
                   <div class="level-left">
                       <span class="level-item">
                           <p class="has-text-danger">
                               An error occourred! {{ $tweet->error }}
                           </p>
                       </span>
                   </div>
               </nav>
           @endif

           <nav class="level is-mobile">
               <div class="level-left">
                   @if($error)
                       <span class="level-item">
                           <p class="has-text-grey-light">
                               Had to be published on {{ date('d/m/Y', $schedules_to_seconds) }} at {{ date('H:i', $schedules_to_seconds) }}
                           </p>
                       </span>
                   @else
                       <span class="level-item">
                           <p class="has-text-grey-light">
                               To be published on {{ date('d/m/Y', $schedules_to_seconds) }} at {{ date('H:i', $schedules_to_seconds) }}
                           </p>
                       </span>
                       <a class="level-item" href="{{ url('dashboard/?edit=' . $tweet->id) }}">
                           <span class="icon"><i class="fa fa-edit"></i></span>
                       </a>
                   @endif

                   <a class="level-item" href="{{ url('dashboard/?remove=' . $tweet->id) }}">
                       <span class="icon"><i class="fa fa-remove"></i></span>
                   </a>
               </div>
           </nav>
       </div>
   </article>
</div>
