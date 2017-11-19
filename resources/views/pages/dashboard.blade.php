@extends('layouts.app')

@section('contents')

@if ($errors->any())
    <div class="notification is-danger floating-notification">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@else
    @if(Session::has('feedback'))
        <div class="notification is-primary floating-notification">
            {{ Session::get('feedback') }}
        </div>
    @endif
@endif

<section class="section">
    <div class="container">
        <form method="POST" action="{{ url('dashboard/save') }}" enctype="multipart/form-data">
            {{ csrf_field() }}
            <input type="hidden" name="tweet" value="{{ $edit ? $edit->id : '' }}">

            <div class="field">
                <label class="label">
                    @if($edit == null)
                        Schedule new Tweet
                    @else
                        Edit Tweet
                    @endif
                </label>
                <div class="control">
                    <textarea class="textarea" name="content">{{ $edit ? $edit->content : '' }}</textarea>
                    <div id="charnum"><span>{{ $edit ? 280 - strlen($edit->content) : 280 }}</span> / 280</div>
                </div>
            </div>

            <div class="columns">
                <div class="column">
                    <div class="field">
                        <div class="control">
                            <input id="datetimepicker" type="text" name="schedule" value="{{ $edit ? $edit->schedule : date('Y-m-d H:i:s') }}">
                        </div>
                    </div>
                </div>

                <div class="column">
                    @if($edit)
                        @foreach($edit->media as $media)
                            <div class="media-preview">
                                <input type="hidden" name="keep_media[]" value="{{ $media->id }}">
                                <img class="preview" src="{{ $media->as_data }}">
                                <a class="button is-danger is-small">
                                    <i class="fa fa-remove"></i>
                                </a>
                            </div>
                        @endforeach
                    @endif

                    <div class="media-preview is-invisible">
                        <input class="file-input" type="file" name="media[]">
                        <img class="preview" src="{{ $media->as_data }}">
                        <a class="button is-danger is-small">
                            <i class="fa fa-remove"></i>
                        </a>
                    </div>

                    <div class="file">
                        <label class="file-label">
                            <input class="file-input" type="file" name="trigger">
                            <span class="file-cta">
                                <span class="file-icon">
                                    <i class="fa fa-upload"></i>
                                </span>
                                <span class="file-label">
                                    Attach Image
                                </span>
                            </span>
                        </label>
                    </div>
                </div>

                <div class="column">
                    <div class="field">
                        <div class="control">
                            @foreach($user->accounts as $index => $account)
                                <label class="label account-select">
                                    <input type="radio" name="account" value="{{ $account->id }}" {{ $edit != null && $edit->account_id == $account->id ? 'checked' : ($index == 0 ? 'checked' : '') }}>
                                    <img src="{{ $account->picture_url }}" alt="{{ $account->handle }}"> {{ $account->handle }}
                                    <a href="{{ url('dashboard/account/remove/' . $account->id) }}" class="button is-danger is-pulled-right is-small">Remove Account</a>
                                </label>
                            @endforeach

                            <br/>
                            <br/>
                            <label class="label">
                                <a href="{{ url('login') }}">Add Another Account</a>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="field is-grouped">
                <div class="control">
                    <button class="button is-primary">Submit</button>
                </div>
                @if($edit)
                    <div class="control">
                        <a href="{{ url('dashboard') }}" class="button">Cancel</a>
                    </div>
                @endif
            </div>
        </form>
    </div>
</section>

@if($user->errorTweets()->count() != 0)
    <section class="section">
        <div class="container">
             @foreach($user->errorTweets as $tweet)
                 <?php $schedules_to_seconds = strtotime($tweet->schedule) ?>
                 <div class="box">
                    <progress class="progress is-danger is-small" value="100" max="100">100%</progress>

                    <article class="media">
                        <div class="media-left">
                            <figure class="image is-64x64">
                                <img src="{{ $tweet->account->picture_url }}" alt="{{ $tweet->account->handle }}">
                            </figure>
                        </div>
                        <div class="media-content">
                            <div class="content">
                                <p>
                                    {!! nl2br($tweet->content) !!}
                                </p>
                            </div>
                            <nav class="level is-mobile">
                                <div class="level-left">
                                    <span class="level-item">
                                        <p class="has-text-danger">
                                            An error occourred! {{ $tweet->error }}
                                        </p>
                                    </span>
                                </div>
                            </nav>
                            <nav class="level is-mobile">
                                <div class="level-left">
                                    <span class="level-item">
                                        <p class="has-text-grey-light">
                                            Had to be published on {{ date('d/m/Y', $schedules_to_seconds) }} at {{ date('H:i', $schedules_to_seconds) }}
                                        </p>
                                    </span>
                                    <a class="level-item" href="{{ url('dashboard/?remove=' . $tweet->id) }}">
                                        <span class="icon"><i class="fa fa-remove"></i></span>
                                    </a>
                                </div>
                            </nav>
                        </div>
                    </article>
                </div>
             @endforeach
        </div>
    </section>
@endif

<section class="section">
    <div class="container">
        @if($user->tweets()->count() == 0)
            <div class="notification is-primary">
                There are no scheduled messages.
            </div>
        @else
             @foreach($user->tweets as $tweet)
                 <?php $schedules_to_seconds = strtotime($tweet->schedule) ?>
                 <div class="box" data-time-schedule="{{ $tweet->schedule }}">
                    <progress class="progress is-small" value="{{ floor(100 - (($schedules_to_seconds - time()) / 60 / 60 / 24)) }}" max="100">15%</progress>

                    <article class="media">
                        <div class="media-left">
                            <figure class="image is-64x64">
                                <img src="{{ $tweet->account->picture_url }}" alt="{{ $tweet->account->handle }}">
                            </figure>
                        </div>
                        <div class="media-content">
                            <div class="content">
                                <p>
                                    {!! nl2br($tweet->content) !!}
                                </p>
                            </div>
                            <nav class="level is-mobile">
                                <div class="level-left">
                                    <span class="level-item">
                                        <p class="has-text-grey-light">
                                            To be published on {{ date('d/m/Y', $schedules_to_seconds) }} at {{ date('H:i', $schedules_to_seconds) }}
                                        </p>
                                    </span>
                                    <a class="level-item" href="{{ url('dashboard/?edit=' . $tweet->id) }}">
                                        <span class="icon"><i class="fa fa-edit"></i></span>
                                    </a>
                                    <a class="level-item" href="{{ url('dashboard/?remove=' . $tweet->id) }}">
                                        <span class="icon"><i class="fa fa-remove"></i></span>
                                    </a>
                                </div>
                            </nav>
                        </div>
                    </article>
                </div>
             @endforeach
        @endif
    </div>
</section>

@endsection
